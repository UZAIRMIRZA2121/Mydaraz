"use strict";

$(document).ready(function () {
    let total = $('.checkout_details .click-if-alone').length;
    if (Number.parseInt(total) < 2) {
        $('.click-if-alone').click()
        $('.checkout_details').html(`<h1>`+ $('#text-redirecting-to-the-payment').data('text') +`......</h1>`);
    }
});

setTimeout(function () {
    $('.stripe-button-el').hide();
    $('.razorpay-payment-button').hide();
}, 10);


$('.digital_payment_btn').on('click', function (){
    $('#digital_payment').slideToggle('slow');
});

$('#pay_offline_method').on('change', function () {
    pay_offline_method_field(this.value);
});

function pay_offline_method_field(method_id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: $('#route-pay-offline-method-list').data('route') + "?method_id=" + method_id,
        data: {},
        processData: false,
        contentType: false,
        type: 'get',
        success: function (response) {
            $("#method-filed__div").html(response.methodHtml);
        },
        error: function () {
        }
    });
}

$('.checkout-wallet-payment-form').on('submit', function (event) {
    setTimeout(() => {
        $('.update_wallet_cart_button').attr('type', 'button').addClass('disabled');
    }, 100);
});

$('.checkout-payment-form').on('submit', function (event) {
    event.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: $(this).attr('action'),
        method: "GET",
        data: $(this).serialize(),
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (response) {
            if (response?.status == 0 && response?.message) {
                toastr.error(response.message);
            }
            if (response?.redirect && response?.redirect != '') {
                location.href = response?.redirect;
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
        error: function () {
        }
    });
})
