import { createApp, h } from "vue";
import App from "./App.vue";
import "../css/main.css";

if (/plugins.php\/speechtotextplugin/.test(window.location.href)) {
    const app = document.getElementById("app");
    if (app) {
        const newDiv = document.createElement("div");
        newDiv.id = "app-hs-merseburg";
        app.insertAdjacentElement("afterend", newDiv);

        createApp({ render: () => h(App) }).mount(newDiv);
    }
}
