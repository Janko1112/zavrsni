function increaseQuantity(){

    let quantity = document.getElementById("quantity");
    let maksimum = parseInt(quantity.getAttribute("max"));

    if (!maksimum || parseInt(quantity.value) < maksimum) {
        quantity.value = parseInt(quantity.value) + 1;
    } else {
        alert("Ne možete naručiti više od " + maksimum + " kom ovog proizvoda.");
    }
}

function decreaseQuantity(){

    let quantity = document.getElementById("quantity");

    if(parseInt(quantity.value) > 1){
        quantity.value = parseInt(quantity.value) - 1;
    }
}
