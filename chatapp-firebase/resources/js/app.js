import './bootstrap';
import { createApp } from 'vue'
import SeekerChatComponent from './component/conversation/seeker/SeekerAppChatComponent.vue'
import SeekerAppChatMobileComponent from "./component/conversation/seeker_mobile/SeekerAppChatMobileComponent.vue"

import ProviderChatComponent from './component/conversation/provider/ProviderAppChatComponent.vue'
import ProviderAppChatMobileComponent from "./component/conversation/provider_mobile/ProviderAppChatMobileComponent.vue"

// Import the functions you need from the SDKs you need
import firebase from "firebase/compat/app";
import "firebase/compat/firestore";

// Your web app's Firebase configuration
const firebaseConfig = {
    // apiKey: "AIzaSyDjNxWtVxllyHkgis5M95sCuHYsKEY92qU",
    // authDomain: "laravel-chat-app-firebase.firebaseapp.com",
    // databaseURL: "https://laravel-chat-app-firebase-default-rtdb.asia-southeast1.firebasedatabase.app",
    // projectId: "laravel-chat-app-firebase",
    // storageBucket: "laravel-chat-app-firebase.appspot.com",
    // messagingSenderId: "61627028589",
    // appId: "1:61627028589:web:cb9ada08bb54825253db64"
    apiKey: "AIzaSyD8DtdzvrkvPXSpJNsvYgNcyyZpchjLchc",
    authDomain: "chat-realtime-firebase-1eaac.firebaseapp.com",
    projectId: "chat-realtime-firebase-1eaac",
    storageBucket: "chat-realtime-firebase-1eaac.appspot.com",
    messagingSenderId: "377110446960",
    appId: "1:377110446960:web:be3b5b00af0975744e05b5"
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

// Mount the Vue app
// Seeker
const seekerChatVue = createApp({});
const seekerChatMobileVue = createApp({});

seekerChatVue.component('seeker-chat-vue', SeekerChatComponent);
seekerChatVue.mount('#seeker_chat_vue');
seekerChatMobileVue.component('seeker-chat-mobile-vue', SeekerAppChatMobileComponent);
seekerChatMobileVue.mount('#seeker_chat_mobile_vue');

// Provider
const providerChatVue = createApp({});
const providerChatMobileVue = createApp({});

providerChatVue.component('provider-chat-vue', ProviderChatComponent);
providerChatVue.mount('#provider_chat_vue');
providerChatMobileVue.component('provider-chat-mobile-vue', ProviderAppChatMobileComponent);
providerChatMobileVue.mount('#provider_chat_mobile_vue');
