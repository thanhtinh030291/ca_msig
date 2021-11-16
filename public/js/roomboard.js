//ajax select code

$(window).load(function () {
    $('.code_claim').select2({
        minimumInputLength: 2,
        ajax: {
            url: "/admin/dataAjaxHBSClaimRB",
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
    //load info of claim
    $(document).on("change", "#code_claim", function () {
        resultApplicant(this.value);
    });
    $(document).ready(function () {
        var id_code = $('#code_claim').val();
        if (id_code != null) {
            resultApplicant(id_code, 'edit');
        }
    });

    function resultApplicant(value , option ='create') {
        var container = $("#result_applicant");
        $.ajax({
            url: "/admin/loadInfoAjaxHBSClaimRB",
            type: 'POST',

            data: {
                'search': value
            },
        })
            .done(function (res) {
                container.empty();
                $('.claim_line').remove();

                container.append('<p class="card-text">Full-Name: ' + res.HBS_CL_CLAIM.mbr_last_name + ' ' + res.HBS_CL_CLAIM.mbr_first_name + '</p>')
                    .append('<p class="card-text">Member No: ' + res.HBS_CL_CLAIM.mbr_no + '</p>')
                    .append('<p class="card-text">Member Ref No: ' + res.HBS_CL_CLAIM.memb_ref_no + '</p>');
                $.each(res.HBS_CL_LINE, function (i, obj) {
                    // obj.incur_date 
                    addInputItem(obj.line_no, obj.prov_name, obj.pres_amt, obj.incur_date, obj.popl_oid);
                });
                if(option != 'create'){
                    addHourOld();
                }

                loadDateTimePicker();
            })
    }
});

// add render laim line
var count = 1;
function addInputItem(line_no = null , prov_name = null , pres_amt = null , incur_date = null , popl_oid = null){
    let clone =  '<tr class = "claim_line" id="row-'+count+'">';
    clone += '<input name = "_idItem['+count+']" type="hidden" >';
    clone +=  $("#clone_item").clone().html() + '</tr>';
    //repalace name
    clone = clone.replace("_line_no_default", "_line_no["+count+"]");
    clone = clone.replace("_prov_name_default", "_prov_name["+count+"]");
    clone = clone.replace("_pres_amt_default", "_pres_amt["+count+"]");
    clone = clone.replace("_incur_date_default", "_incur_date["+count+"]");

    //replace id
    clone = clone.replace("btn_check_default", "btn_check_"+count);
    clone = clone.replace("template_default", "template_"+count);

    $("#empty_item").before(clone);
    //
    $('input[name="_line_no['+count+']"]').val(line_no);
    $('input[name="_prov_name['+count+']"]').val(prov_name);
    $('input[name="_pres_amt['+count+']"]').val(formatPrice(pres_amt));
    $('input[name="_incur_date['+count+']"]').val(incur_date);
    $("#btn_check_"+count).attr('data-popl_oid', popl_oid);
    $("#btn_check_"+count).attr('data-id', count);
    $("#btn_check_"+count).attr('data-pres_amt', pres_amt);
    count++;
}

// return hours diff 2 day
function diff_hours(dt2, dt1) 
{
    var diff =(dt2.getTime() - dt1.getTime()) / 1000;
    diff /= (60 * 60);
    return Math.abs(Math.round(diff));
}

// change format mm/dd/yyyy to dd/mm/yyyy
function change_format_date(dt) 
{
    var split = dt.split("/")
    return split[1] +"/"+split[0] + "/"+ split[2];
}

// check info  
function checkRoomBoard(e){
    var popl_oid = e.dataset.popl_oid;
    var id = e.dataset.id;
    var pres_amt = e.dataset.pres_amt;
    var container = $("#template_"+id);
    $.ajax({
            url: "/admin/checkRoomBoard",
            type: 'POST',
            data: {'search' : popl_oid},
        })
        .done(function(res) {
            container.empty();
            var dateInput = $('input[name="_incur_date['+id+']"]').val();
            var splitDateTime = dateInput.split("-");
            dt1 = new Date(change_format_date(splitDateTime[0]));
            dt2 = new Date(change_format_date(splitDateTime[1]));
            var diffHours = diff_hours(dt1, dt2);
            
            clone =  $("#template_info").clone().html();
            clone = clone.replace("$amt_day", formatPrice(res.amt_day));
            clone = clone.replace("$amt_hour", formatPrice(res.amt_day/24));
            clone = clone.replace("$day_dis_yr", res.day_dis_yr);
            clone = clone.replace("$total_hour", diffHours);
            clone = clone.replace("$approve_max", formatPrice(diffHours*res.amt_day/24));
            var approve_amount = 0;
            if(res.amt_day <= diffHours*res.amt_day/24){
                approve_amount = pres_amt;
            }else{
                approve_amount = diffHours*res.amt_day/24;
                var reject_amount =  res.pres_amt - approve_amount;
                clone = clone.replace("$reject_amount", formatPrice(reject_amount));
                clone = clone.replace("display:none", " ");
            }
            clone = clone.replace("$approve_amount", formatPrice(approve_amount));
            container.append(clone);
        })
}
