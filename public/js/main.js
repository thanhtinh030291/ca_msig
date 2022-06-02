$(document).ready(function() {
    loadDatepicker();
    loadDateTimePicker();
});

function onOffpush(e){
    if(e.checked == false){
        Notification.requestPermission();
        initFirebaseMessagingRegistration();
    }else{
        
    }
}

function loadDatepicker() {
    $(".datepicker").daterangepicker({
            locale: {
            "format": "YYYY-MM-DD"
        },
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
    });
    $(".datepicker").on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });
    $(".datepicker").on('change', function() {
        var date = moment($(this).val(), 'YYYY-MM-DD', true);
        if (!date.isValid()) {
            $(this).val('');
        }
    });
    

    $(".datepicker2").daterangepicker({
        locale: {
            "format": "DD/MM/YYYY"
        },
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
    });
    $(".datepicker2").on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });
    $(".datepicker2").on('change', function() {
        var date = moment($(this).val(), 'DD/MM/YYYY', true);
        if (!date.isValid()) {
            $(this).val('');
        }
    });
}

function loadDateTimePicker() {
    $(".datetimepicker").daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 1,
        locale: {
            format: 'DD/MM/YYYY HH:mm'
        }
    });
    
}

function pusher_res(data){
    var notificationsWrapper   = $('.dropdown-notifications');
    var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
    var notificationsCountElem = notificationsToggle.find('i[data-count]');
    var notificationsCount     = parseInt(notificationsCountElem.data('count'));
    var notifications          = notificationsWrapper.find('ul.dropdown-menu-notifi');

    // Enable pusher logging - don't include this in production
    
    
        var existingNotifications = notifications.html();
        var newNotificationHtml = `
            <li class="notification active">
                <div class="media">
                    <div class="media-left">
                    <div class="media-object">
                        <img src="`+data.avantar+`" class="img-circle" alt="50x50" style="width: 50px; height: 50px;">
                    </div>
                    </div>
                    <div class="media-body">
                    <strong class="notification-title">`+data.title+`</strong>
                    <p class="notification-desc">`+data.content+`</p>
                    <div class="notification-meta">
                        <small class="timestamp">about a minute ago</small>
                    </div>
                    </div>
                </div>
            </li>
        `;
        notifications.html(newNotificationHtml + existingNotifications);

        notificationsCount += 1;
        notificationsCountElem.attr('data-count', notificationsCount);
        notificationsWrapper.find('.notif-count').text(notificationsCount);
        //notificationsWrapper.show();
        $.notify({
            icon: '',
            title: '<img src="'+data.avantar+'" class="img-circle" alt="50x50" style="width: 50px; height: 50px;">'+'<strong>'+data.title+'</strong>',
            message: data.content
        },{
            placement: {
                from: "bottom",
                align: "right"
            },
            type: 'success'
        });
}

function readAllMessages(){
    
    $.ajax({
        url: "/admin/read_all_messages",
        type: 'POST',
        
    })
    .done(function(res) {
        var notificationsWrapper   = $('.dropdown-notifications');
        var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
        var notificationsCountElem = notificationsToggle.find('i[data-count]');
        var notificationsCount     = parseInt(notificationsCountElem.data('count'));
        var notifications          = notificationsWrapper.find('ul.dropdown-menu-notifi');
        notifications.html("");
        var notificationsWrapper   = $('.dropdown-notifications');
        notificationsCountElem.attr('data-count', "0");
        notificationsWrapper.find('.notif-count').text('0');

    })
}

//end 
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function fchat()
{
    var tchat= document.getElementById("tchat").value;
    if(tchat==0 || tchat=='0')
    {                
        document.getElementById("fchat").style.display = "block";
        document.getElementById("tchat").value=1;
    }else{
        document.getElementById("fchat").style.display = "none";
        document.getElementById("tchat").value=0;
    }             
}

function postMessagee(e){
    $(".loader").show();
    var user_f = $('#user_f').val();
    var content_f = tinyMCE.get('content_f').getContent();
    axios.post("/postMessage",{
            'user' : user_f ,
            'content' : content_f,
    })
    .then(function (response) {
        $(".loader").fadeOut("slow");
        $.notify({
            icon: 'fa fa-bell',
            title: '<strong>Hệ THống</strong>',
            message: "Gửi Thành Công"
        },{
            placement: {
                from: "top",
                align: "right"
            },
            type: 'success'
        });
    })
    .catch(function (error) {
        $(".loader").fadeOut("slow");
        alert(error);
        
    });
}

function contentReject(){
    $("content_f").val("");
    var url      = $(location).attr('href');
    var user_name = $('meta[name="user_name"]').attr('content')
    var html  = "Letter bị từ chối bởi <b>" + user_name + "</b> Vui lòng kiểm tra lại thông tin tại : <br> "+url;
    tinymce.get("content_f").setContent(html);
}
function contentAccept(){
    $("content_f").val("");
    var url      = $(location).attr('href'); 
    var user_name = $('meta[name="user_name"]').attr('content')
    var html  = "Letter đã được chấp nhận bởi <b>" + user_name + "</b> Vui lòng kiểm tra lại thông tin tại : <br> "+url;
    tinymce.get("content_f").setContent(html);
}
function contentRequest(){
    $("content_f").val("");
    var url      = $(location).attr('href'); 
    var user_name = $('meta[name="user_name"]').attr('content')
    var html  = "Letter yêu cầu tiến hành xác nhận bởi <b>" + user_name + "</b> Vui lòng kiểm tra lại thông tin tại : <br> "+url;
    tinymce.get("content_f").setContent(html);
}
tinymce.init({
    selector: '#content_f',  // change this value according to your HTML
    elementpath: false,
    contextmenu: false,
    menubar:false,
    statusbar: false,
    branding: false,
    toolbar: false,
}) 
