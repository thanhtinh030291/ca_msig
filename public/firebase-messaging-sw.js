/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');
   
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
        apiKey: "AIzaSyBTHTKBDMg9feCwbB5Mp9ceiR-kR3QFL3M",
        authDomain: "pacific-cross.firebaseapp.com",
        projectId: "pacific-cross",
        storageBucket: "pacific-cross.appspot.com",
        messagingSenderId: "501542859634",
        appId: "1:501542859634:web:0274ffd7f050783f55a3eb",
        measurementId: "G-W2HN0MDWL3"
    });

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});