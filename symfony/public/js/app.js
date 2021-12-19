/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
$(document).ready(function () {
    var searchRequest = null;
    $.validator.addMethod(
        "validateSku",
        function(value, element) {
            return this.optional(element) || /^[0-9A-Z\s]+$/.test(value);
        },
        "The value of the SKU is invalid (you need 2-8, A-Z)"
    );
    $.validator.addMethod(
        "checkSkuExits",
        function(value, element) {
            var ajax =  checkSku(value, element);
            return JSON.parse(ajax.responseText).exists;
        },
        "Product with the specified SKU was not found"
    );
    $.validator.addMethod(
        "checkSkuNewExits",
        function(value, element) {
            var ajax =  checkSku(value, element);
            return !JSON.parse(ajax.responseText).exists;
        },
        "The product sku is already exists."
    );
    $("form#replace-form").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            sku: {
                required: true,
                checkSkuExits: true
            },
            new_sku: {
                required: true,
                validateSku: true,
                checkSkuNewExits: true
            }
        }
    });

    function checkSku(value, element) {
        var urlAction = $(element).data('action');
        return $.ajax({
            type: "GET",
            url: urlAction,
            data: {'q' : value},
            async: false,
            dataType: "json",
            success: function(msg){

            }
        });
    }
});