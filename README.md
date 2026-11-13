# DEMO - Culqi PHP + JS V4 + Culqi 3DS

La demo integra Culqi PHP, JS V4 , Culqi 3DS y es compatible con la v2.0 del Culqi API, con esta demo podrás generar tokens, cargos y órdenes.

## Requisitos

* PHP 7.0+
* Afiliate [aquí](https://afiliate.culqi.com/).
* Si vas a realizar pruebas obtén tus llaves desde [aquí](https://integ-panel.culqi.com/#/registro), si vas a realizar transacciones reales obtén tus llaves desde [aquí](https://panel.culqi.com/#/registro) (1).

> Recuerda que para obtener tus llaves debes ingresar a tu CulqiPanel > Desarrollo > ***API Keys***.

![alt tag](http://i.imgur.com/NhE6mS9.png)

> Recuerda que las credenciales son enviadas al correo que registraste en el proceso de afiliación.

* Para encriptar el payload debes generar un id y llave RSA ingresando a CulqiPanel > Desarrollo > RSA Keys.

## Instalación

Para la instalación de la librería de Culqi se debe ejecutar el siguiente comando en la raiz del proyecto.

```bash
composer require culqi/culqi-php
```

Esto generará una carpeta **vendor** donde se encuentra la librería **culqi-php**.

## Configurar las pruebas y ejemplos

Antes de ejecutar la demo, configura localmente tus propias llaves `pk_test_` y `sk_test_` y los datos RSA obtenidos desde CulqiPanel. No hagas commit de llaves ni las publiques. La llave secreta se usa solo en el backend; no la incluyas en el frontend.

### Configuración backend

Primero se tiene que modificar los valores del archivo `settings.php` que se encuentra en la raíz del proyecto. A continuación un ejemplo.

```
define('PUBLIC_KEY', 'INSERTE_SU_LLAVE_PUBLICA');
define('SECRET_KEY', 'INSERTE_SU_LLAVE_PRIVADA');
define('RSA_ID', 'INSERTE_SU_RSA_ID');
define('RSA_PUBLIC_KEY', 'INSERTE_SU_LLAVE_RSA');
```
### Configuración frontend

Para configurar los datos del cargo, pk del comercio y datos del cliente se tiene que modificar en el archivo `/js/config/index.js`.

```js
export default Object.freeze({
    TOTAL_AMOUNT: monto de pago,
    CURRENCY: tipo de moneda,
    PUBLIC_KEY: 'INSERTE_SU_LLAVE_PUBLICA',
    RSA_ID: 'INSERTE_SU_RSA_ID',
    RSA_PUBLIC_KEY: 'INSERTE_SU_LLAVE_RSA',
    COUNTRY_CODE: iso code del país(Ejemplo PE)
});

export const customerInfo = {
    firstName: "Fernando",
    lastName: "Chullo",
    address: "Coop. Villa el Sol",
    phone: "945737476",
}
```

## Inicializar la demo
El proyecto se debe levantar con un servidor local(Ejemplo Xampp)

## Probar la demo

Para poder visualizar el frontend de la demo ingresar a la siguiente URL:

- Para probar cargos y órdenes: `http://localhost/culqi-php-demo-jsv4-culqi3ds`


## Documentación

- [Referencia de Documentación](https://docs.culqi.com/)
- [Referencia de API](https://apidocs.culqi.com/)
