const generateOrder = async () => {
  const response = await $.ajax({
    type: 'GET',
    url: 'http://localhost/culqijsv4-yape__3ds/culqi-php-develop/examples/08-create-order.php',
  });
  const responseJSON = await JSON.parse(response);
  if (responseJSON.object === "order") {
    return { status: 200, data: responseJSON }
  } else {
    return { status: 401, data: responseJSON }
  }
}

export default generateOrder;
