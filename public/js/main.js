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

$(document).ready(function() {
    loadDatepicker();
    loadDateTimePicker();
});

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
            icon: 'fa fa-bell',
            title: '<strong>'+data.title+'</strong>',
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
//sevice worker
    var key_public_worker = $('meta[name="key-notify"]').attr('content');
    var _registration = null;

    function registerServiceWorker() {
        return navigator.serviceWorker.register('/js/service-worker.js')
        .then(function(registration) {
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
        _registration = registration;
        return registration;
        })
        .catch(function(err) {
        console.error('Unable to register service worker.', err);
        });
    }


    function askPermission() {
        return new Promise(function(resolve, reject) {
        const permissionResult = Notification.requestPermission(function(result) {
        resolve(result);
        });
        if (permissionResult) {
        permissionResult.then(resolve, reject);
        }
        })
        .then(function(permissionResult) {
        if (permissionResult !== 'granted') {
        throw new Error('We weren\'t granted permission.');
        }
        else{
        subscribeUserToPush();
        }
        });
    }
    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    function getSWRegistration(){
        var promise = new Promise(function(resolve, reject) {
    // do a thing, possibly async, then…
            if (_registration != null) {
            resolve(_registration);
            }
            else {
            reject(Error("It broke"));
            }
        });
        return promise;
    }

    function subscribeUserToPush() {
        getSWRegistration()
        .then(function(registration) {
            const subscribeOptions = {
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(key_public_worker)
            };
            return registration.pushManager.subscribe(subscribeOptions);
        })
        .then(function(pushSubscription) {
            subscription = JSON.parse(JSON.stringify(pushSubscription));
            
            $.ajax({
                url: '/check_subscriptions',
                type: 'POST',
                context: this,
                data: {'endpoint' : subscription.endpoint , 
                        'publicKey' : subscription.keys.p256dh ,
                        'contentEncoding' : 'aes128gcm',
                        'authToken' : subscription.keys.auth
                },
            })
            .done(function(res) {
                if(res == 0){
                    $("#onOffpush_checkbox").prop('checked', false);
                }else{
                    $("#onOffpush_checkbox").prop('checked', true);
                }
            })
        
        return pushSubscription;
        });
    }

    function sendSubscriptionToBackEnd(subscription) {
        var response;
        subscription = JSON.parse(JSON.stringify(subscription))
        $.ajax({
            url: '/subscriptions',
            type: 'POST',
            context: this,
            data: {'endpoint' : subscription.endpoint , 
                    'publicKey' : subscription.keys.p256dh ,
                    'contentEncoding' : 'aes128gcm',
                    'authToken' : subscription.keys.auth
            },
        })
        .done(function(res) {
            response = res;
        })
        return response;
    }
    function deleteSubscriptionToBackEnd(subscription) {
        subscription = JSON.parse(JSON.stringify(subscription))
        $.ajax({
            url: '/subscriptions/delete',
            type: 'POST',
            context: this,
            data: {'endpoint' : subscription.endpoint 
            },
        })
        .done(function(res) {
            response = res;
        })
    };

    function enableNotifications(){
    //register service worker
    //check permission for notification/ask
        askPermission();
    }

    if('serviceWorker' in navigator && (location.protocol != 'http' || location.hostname === "localhost")){
        registerServiceWorker();
    }else{
        $("#checkbox-notify").hide();
    }

    function onOffpush(e){
        if(e.checked == false){
            getSWRegistration()
            .then(function(registration) {
                const subscribeOptions = {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(key_public_worker)
                };
                return registration.pushManager.subscribe(subscribeOptions);
            })
            .then(function(pushSubscription) {
                deleteSubscriptionToBackEnd(pushSubscription);
            });
            
        }else{
            getSWRegistration()
            .then(function(registration) {
                const subscribeOptions = {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(key_public_worker)
                };
                return registration.pushManager.subscribe(subscribeOptions);
            })
            .then(function(pushSubscription) {
                sendSubscriptionToBackEnd(pushSubscription);
            });
        }
    }

    $( document ).ready(function() {
        if('serviceWorker' in navigator && (location.protocol != 'http' || location.hostname === "localhost")){
            registerServiceWorker();
            subscribeUserToPush();
        }else{
            $("#checkbox-notify").hide();
        }
        
    });      
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
