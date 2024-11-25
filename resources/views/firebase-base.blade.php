<div>

</div>
<script type="module">
    import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js'
    import {getMessaging, onMessage, getToken} from "https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging.js";

    const firebaseConfig = {
        apiKey: "{{ config('filament-fcm-driver.project.apiKey') }}",
        authDomain: "{{ config('filament-fcm-driver.project.authDomain') }}",
        databaseURL: "{{ config('filament-fcm-driver.project.databaseURL') }}",
        projectId: "{{ config('filament-fcm-driver.project.projectId') }}",
        storageBucket: "{{ config('filament-fcm-driver.project.storageBucket') }}",
        messagingSenderId: "{{ config('filament-fcm-driver.project.messagingSenderId') }}",
        appId: "{{ config('filament-fcm-driver.project.appId') }}",
        measurementId: "{{ config('filament-fcm-driver.project.measurementId') }}",
    };
    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);
    Notification.requestPermission().then((permission) => {
        if (permission === "granted") {
            if ("serviceWorker" in navigator) {
                navigator.serviceWorker
                    .register("/firebase-messaging-sw.js");
            }
            navigator.serviceWorker.getRegistration().then(async (reg) => {
                let token = await getToken(messaging, {vapidKey: "{{ config('filament-fcm-driver.vapid') }}"});
                console.log(token);
                Livewire.dispatch('fcm-token', { token: token });


                onMessage(messaging, (payload) => {
                    console.log(payload);
                    Livewire.dispatch('fcm-notification', {data: payload})
                    // push notification can send event.data.json() as well
                    const options = {
                        body: payload.data.body,
                        icon: payload.data.image,
                        tag: "alert",
                    };
                    let notification = reg.showNotification(
                        payload.data.title,
                        options
                    );
                    // link to page on clicking the notification
                    notification.onclick = (payload) => {
                        window.open(payload.data.url);
                    };
                });
            });
        }

    });

</script>
