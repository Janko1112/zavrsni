function increaseQuantity(){

    let quantity = document.getElementById("quantity");

    quantity.value = parseInt(quantity.value) + 1;
}

function decreaseQuantity(){

    let quantity = document.getElementById("quantity");

    if(parseInt(quantity.value) > 1){
        quantity.value = parseInt(quantity.value) - 1;
    }
}