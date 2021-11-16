
let token = document.head.querySelector('meta[name="csrf-token"]');
const USER_ID = Number($('meta[name=user_id]').attr("content"));
import Echo from 'laravel-echo';

window.io = require('socket.io-client');
window.Echo = new Echo({
    namespace: 'App.Events',
    broadcaster: 'socket.io',
    host: `node.${window.location.hostname}`
});

window.Echo.channel('user.'+USER_ID)
    .listen('Notify', (e) => {
        console.log(e);
        pusher(e);
        //$('#content').append(`<div class="well">${e.message}</div>`);
    });

