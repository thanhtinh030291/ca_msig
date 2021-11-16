$(document).ready(function() {
    var id_active = $('#id_active').val();
    var paymentRole = $('#payment_role').val();

    $('div.tab').find('button[data-info="'+id_active+'"]').addClass('active');
    $('div#'+id_active).show();
    $('.item-price').each(function(){
        changeFormatObj($(this));
    });
    checkPaymentRole(paymentRole);
});

$(document).on("keyup", '.item-price', function(){
    changeFormatObj($(this));
});

$(document).on('click', '.tab .tablinks', function() {
    $('button.tablinks').removeClass('active');
    $('div.tabcontent').hide();
    $(this).addClass('active');
    var id_active = $(this).attr('data-info');
    $('div#'+id_active).show();
    $('#id_active').val(id_active);
});

$('#payment_role').bind('change', function(){
    let paymentRole = $(this).val();
    checkPaymentRole(paymentRole);
});

function checkPaymentRole(paymentRole) {
    $('.role').addClass('display-none');
    $('.role input').attr('disabled', true);
    $('.payment_method'+paymentRole).removeClass('display-none');
    $(`.payment_method${paymentRole} input`).removeAttr('disabled');
}

