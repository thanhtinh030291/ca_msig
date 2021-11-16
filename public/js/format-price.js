function formatPrice(x) {
    //remove format first
    x = removeFormatPrice(x);
    return x.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function removeFormatPrice(number) {
    number = number.toString();
    var result = number.replace(/,/g, '');
    result = result.replace(/[^0-9]+/g, '');
    return result;
}
function changeFormatObj(obj) {
    var formated = formatPrice(obj.val());
    obj.val(formated);
}
$(document).on("keyup", '.item-price', function(){
    changeFormatObj($(this));
    checkPriceFormat($(this).val(), $(this));
});

$(document).ready(function() {
    $('.item-price').each(function(){
        changeFormatObj($(this));
    });
});

function checkPriceFormat(x, input_element){
    var patt1 = /^\d+(,\d{3})*(.\d{3})*(.\d{1,2})?$/gm;
    var is_valid = patt1.test(x);
    if (is_valid) {
        input_element.removeClass('invalid');
        input_element.addClass('valid');
    } else {
        input_element.removeClass('valid');
        input_element.addClass('invalid');
    }
}



