import { initializeApp } from 'firebase/app';

const firebaseConfig = {
    apiKey: "AIzaSyDwueViKUXhUG1HVj2tJ3qpwBReAOxh4ME",
    authDomain: "fun-education-batam.firebaseapp.com",
    projectId: "fun-education-batam",
    storageBucket: "fun-education-batam.appspot.com",
    messagingSenderId: "886019485092",
    appId: "1:886019485092:web:7e98a148a835596044e531",
};

const app = initializeApp(firebaseConfig);

export default app;