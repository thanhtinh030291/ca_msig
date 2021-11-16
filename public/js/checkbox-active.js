function change_start_date(checked){
    if(checked == true) {
        $(".date_active").prop('disabled', true);
    }else{
        $(".date_active").prop('disabled', false);
    }
}

$(document).on('ready', function() {
    var ischecked = $("#always_active").is(":checked");
    change_start_date(ischecked);

    var ischecked_list_name = $("#list_name").is(":checked");
    if(ischecked_list_name == false){
        change_pattern(ischecked_list_name);
    }
});

function change_pattern(checked){
    if(checked == true) {
        $('#pattern1').attr('disabled',false).prop('checked',true);
        $('#pattern2').attr('disabled',false).prop('checked',false);
    }else{
        $('#pattern1').attr('disabled',true).prop('checked',false);
        $('#pattern2').attr('disabled',true).prop('checked',false);
    }
}
