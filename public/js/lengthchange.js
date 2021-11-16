$(document).on('change', '.lengthChange', function() {
    let limit = $(this).val();
    let url = $(this).data('url') + '?limit=' + limit;
    let search_params = $(this).data('article');
    $.each(search_params, function(index, value) {
        if(index != 'limit') {
            if(value != null) {
                url = url + "&" + index + "=" + value
            }
        }
    })
    window.location.href = url;
});

$(document).on('click', '#clearForm', function(){
    var frm = $(this).closest('form');
    frm.find('input[type="text"]').val('');
    frm.find('input[type="checkbox"]').prop('checked', false);
    frm.find('select').each(function(idx, sel) {
        $(sel).prop('selectedIndex', 0);
    });
    frm.trigger('submit');
});

$(document).on('click', '.btn-delete', function(){
    let delete_url = $(this).attr('data-url');
    $('#form_delete').attr('action', delete_url);
});
