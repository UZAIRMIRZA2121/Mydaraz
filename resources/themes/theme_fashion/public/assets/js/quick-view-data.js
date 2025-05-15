"use strict";

$(document).ready(function () {
    getVariantPrice(".add-to-cart-details-form");
    actionRequestForProductRestockFunctionality();
});

$('.add-to-cart-details-form input').on('change', function () {
    getVariantPrice(".add-to-cart-details-form");
});

$('.add-to-cart-details-form').on('submit', function (e) {
    e.preventDefault();
});


$('.addCompareList_quick_view').on('click', function () {
    let id = $(this).data('id');
    addCompareList(id);
});
