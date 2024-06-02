importScripts(
    "https://www.gstatic.com/firebasejs/10.0.0/firebase-app-compat.js"
);
importScripts(
    "https://www.gstatic.com/firebasejs/10.0.0/firebase-messaging-compat.js"
);

firebase.initializeApp({
    apiKey: "AIzaSyDwueViKUXhUG1HVj2tJ3qpwBReAOxh4ME",
    authDomain: "fun-education-batam.firebaseapp.com",
    projectId: "fun-education-batam",
    storageBucket: "fun-education-batam.appspot.com",
    messagingSenderId: "886019485092",
    appId: "1:886019485092:web:7e98a148a835596044e531",
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(({ notification }) => {
    console.log("[firebase-messaging-sw.js] Received background message ");
    // Customize notification here
    const notificationTitle = notification.title;
    const notificationOptions = {
        body: notification.body,
    };

    if (notification.icon) {
        notificationOptions.icon = notification.icon;
    }

    self.registration.showNotification(notificationTitle, notificationOptions);
});