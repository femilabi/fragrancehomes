function save_cart (cart) {
    localStorage.cart = JSON.stringify(cart);
}

function get_cart () {
    let cart = JSON.parse((localStorage.cart ? localStorage.cart : '[]'));
    // console.log(typeof cart);
    if (!(typeof cart == 'object' || typeof cart == 'Array')) {
        cart = [];
    }
    return cart;
}

function add_to_cart (product) {
    // clear_cart ();
    let cart = get_cart();
    // console.log(cart);
    if (product.cart_index) {
        update_cart (product.cart_index, product);
        return product.cart_index;
    }
    cart.push(product);
    save_cart(cart);
    return cart.length - 1;
}

function update_cart (index, product) {
    let cart = get_cart();
    cart[index] = product;
    save_cart(cart);
}

function remove_from_cart (index) {
    let cart = get_cart();
    cart.splice(index, 1);
    save_cart(cart);
}

function clear_cart () {
    save_cart([]);
}

function price (amount) {
    return Number(amount).toFixed(2).toLocaleString();
}

function update_angular_scope_cart(elem) {
    let scope = angular.element(elem).scope();
    scope.$apply(function(){
        scope.cart = get_cart();
        if (scope.calculate_price) scope.calculate_price();
    });
}