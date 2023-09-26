import config from "./config/index.js";
import culqiConfig from "./config/checkout.js";
import "./config/culqi3ds.js";
import generateOrder from "./services/getOrder.js";
import { generateChargeImpl } from "./services/impl/index.js";
$("#response-panel").hide();

const { status, data } = await generateOrder();
if (status === 200) {
  culqiConfig({ orderId: data.id });
} else {
  console.log('No se pudo obtener la orden');
}


const deviceId = await Culqi3DS.generateDevice();
if (!deviceId) {
  console.log("Ocurrio un error al generar el deviceID");
}
let tokenId, email;
window.addEventListener("message", async function (event) {
  if (event.origin === window.location.origin) {
    const { parameters3DS, error } = event.data;

    if (parameters3DS) {
      let statusCode = null;
      const responseCharge = await generateChargeImpl({ deviceId, email, tokenId, parameters3DS });
      statusCode = responseCharge.statusCode;

      if (statusCode === 200) {
        resultdiv("PAGO EXITOSO");
        Culqi3DS.reset();

      } else {
        resultdiv("PAGO FALLIDO");
        Culqi3DS.reset();
      }
    }

    if (error) {
      resultdiv("Error, revisa la consola");
      console.log("Ocurrió un error: ", error);
    }
  }
},
  false
);

window.culqi = async () => {
  if (Culqi.token) {
    $(document).ajaxStart(function () {
      run_waitMe();
    });
    Culqi.close();
    tokenId = Culqi.token.id;
    email = Culqi.token.email;

    const { statusCode } = await generateChargeImpl({ deviceId, email, tokenId });
    console.log(tokenId);
    if (tokenId.indexOf("ype") !== -1) {
      console.log("La variable contiene la palabra 'ype'");
      $('#yape-code').html("Cargo Generado Existosamente");  
      var yapebutton = document.getElementById("yape-button");
      yapebutton.innerText = "Yapear S/11.00";
      alert("Cargo Generado Existosamente");  
      location.reload();
    }
    else
    {
      validationInit3DS({ email, statusCode, tokenId });
    }
    

  } else if (Culqi.order) {// Objeto order creado exitosamente!
    let order = Culqi.order;
    alert('Se ha creado el objeto Order:', order);
    console.log(`Se ha creado el objeto Order Number: ${order}.`);
    if (order.payment_code) {
      alert('Se ha creado el cip:' + order.payment_code);
      $('#cip')
        .removeClass('hidden')
        .children('div#button')
        .addClass('hidden')
        .children('div#result')
        .removeClass('hidden');
      $('#payment-code').html(order.payment_code);
      $('#cuotealo').addClass('hidden');
    }
    if (order.qr) {
      alert('Se ha generado el qr:' + order.qr);
      $('#cip').children('div#result').removeClass('hidden');
      $('#response-qr').attr('src', order.qr);
      $('#qr').removeClass('hidden');
    } else {
      $('#response-qr').addClass('hidden');
      $('#response-qr').html('El excede el rango de monto');    
      location.reload();  
    }
    if (order.cuotealo) {
      alert('Se ha creado el link cuotéalo:' + order.cuotealo);
      $('#cip').addClass('hidden');
      $('#cuotealo').removeClass('hidden');
      $('#cuotealo').children('div#button').addClass('hidden');
      $('#cuotealo').children('div#result').removeClass('hidden');
      $('#link-cuotealo').attr('href', order.cuotealo);
      $('#link-cuotealo').html(order.cuotealo);
      location.reload();
    }
  }
  else {// Hubo algún problema!
    alert(Culqi.error.user_message);
    $('#response-panel').show();
    $('#response').html(Culqi.error.merchant_message);
    $('body').waitMe('hide');
  }
};

const validationInit3DS = ({ statusCode, email, tokenId }) => {
  if (statusCode === 200) {

    Culqi3DS.settings = {
      charge: {
        totalAmount: config.TOTAL_AMOUNT,
        returnUrl: "http://localhost/culqijsv4-yape__3ds/index.php"
      },
      card: {
        email: email,
      }
    };
    Culqi3DS.initAuthentication(tokenId);

  } else if (statusCode === 201) {
    resultdiv("PAGO EXITOSO - SIN 3DS");
    Culqi3DS.reset();
    location.reload();
  } else {
    resultdiv("PAGO FALLIDO");
    Culqi3DS.reset();
    location.reload();

  }
}

Culqi.validationPaymentMethods();
//Obtenemos los metodos de pagos disponibles
console.log(Culqi.paymentOptionsAvailable);
console.log("SI USA EL paymentOptionsAvailable")

var paymentTypeAvailable = Culqi.paymentOptionsAvailable;

const buttonMethod = document.querySelectorAll('[data-button-method]');

buttonMethod.forEach((button) => {
  button.addEventListener('click', (el) => {
    el.target.disabled = true;
    el.preventDefault();
    const method = button.getAttribute('data-button-method');
    console.log("Se genero usando el paymentTypeAvailable");
    if (paymentTypeAvailable[method].available) {
      el.target.innerHTML = el.target.dataset.textButton;
      paymentTypeAvailable[method].generate();
    } else {
      alert(`No disponible: ${paymentTypeAvailable[method].message}`);
    }
  });
});


setInterval(() => {
  $('#content-culqijs').addClass(Culqi.isOpen ? 'invisible' : 'visible');
  $('#content-culqijs').removeClass(Culqi.isOpen ? 'visible' : 'invisible');
}, 500);

$("#response-panel").hide();
function run_waitMe() {
  $('body').waitMe({
    effect: 'orbit',
    text: 'Procesando pago...',
    bg: 'rgba(255,255,255,0.7)',
    color: '#28d2c8'
  });
}

function resultdiv(message) {
  $('#response-panel').show();
  $('#response').html(message);
  $('body').waitMe('hide');
}
