$(document).ready(function() {
    var name_id = $('.nameId').val();
    dataAjaxTicketType(name_id);
})

$(document).on('change', '.nameId', function() {
    let name_id = $(this).val();
    dataAjaxTicketType(name_id);
});

function dataAjaxTicketType(name_id) {
    let url = $('.nameId').data('url');
    let selectTypeId = $('.nameId').data('type');
    $.ajax({
        url: url,
        data:{nameId:name_id},
        success:function(data){
            $('.type_id').empty();
            let placeholder = {id: '', text: ''};
            data.unshift(placeholder);
            $.each(data, function(index, value) {
                let newOption = new Option(value.text, value.id, false, false);
                $('.type_id').append(newOption);
            });
            $('.type_id').val(selectTypeId);
        }
    });
}