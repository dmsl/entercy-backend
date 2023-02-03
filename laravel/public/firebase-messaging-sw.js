importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
//Panos code
/*firebase.initializeApp({
    apiKey: "AIzaSyBPH2XtLUKBlo-4F-Gzg_quBITFsA8yx3M",
    authDomain: "entercy-4ee78.firebaseapp.com",
    projectId: "entercy-4ee78",
    storageBucket: "entercy-4ee78.appspot.com",
    messagingSenderId: "559252959640",
    appId: "1:559252959640:web:8374564f8337ffffdcfbed",
    measurementId: "G-PCCWEB7PYJ"
});*/

firebase.initializeApp({
    apiKey: "AIzaSyAp2Cqcveo14I8H_4c_zSGItWwliKB658w",
    authDomain: "entercy-official.firebaseapp.com",
    projectId: "entercy-official",
    storageBucket: "entercy-official.appspot.com",
    messagingSenderId: "844319687008",
    appId: "1:844319687008:web:4d263946cbcbc455291e05",
    measurementId: "G-T761H71NF5"
});


// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);

    const title = "EnterCY";
    const options = {
        body: "Your recommendations are available! Click to view!!!",
        icon: "/firebase-logo.png",
    };

    return self.registration.showNotification(
        title,
        options,
    );
});
