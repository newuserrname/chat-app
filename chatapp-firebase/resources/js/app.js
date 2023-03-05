import './bootstrap';

import { createApp } from 'vue'
import ChatComponent from './component/AppChatComponent.vue'

// Import the functions you need from the SDKs you need
import firebase from "firebase/compat/app";
import "firebase/compat/firestore";

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyDjNxWtVxllyHkgis5M95sCuHYsKEY92qU",
    authDomain: "laravel-chat-app-firebase.firebaseapp.com",
    databaseURL: "https://laravel-chat-app-firebase-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "laravel-chat-app-firebase",
    storageBucket: "laravel-chat-app-firebase.appspot.com",
    messagingSenderId: "61627028589",
    appId: "1:61627028589:web:cb9ada08bb54825253db64"
};

try {
    firebase.initializeApp(firebaseConfig);
} catch (error) {
    console.error("Firebase initialization error", error.stack);
}
const db = firebase.firestore();

try {
    window.db = db;
} catch (error) {
    console.error("Error setting Firestore timestamps", error.stack);
}
export {firebase, db};

// app chat component
const app = createApp({});

app.component('chat-vue', ChatComponent);
app.mount('#chat_vue');

/*db.collection('conversation_message').where('conversation_id', '==', 'dd3e312c-8cdb-4c51-97e0-d72bd0b83a0e')
    .orderBy('created_at').get()
    .then((snapshot) => {
        snapshot.docs.forEach(docs => {
            console.log(docs)
        })
    })*/
