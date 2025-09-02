import{ createApp } from 'vue';
import ChatComponent from './components/ChatComponent.vue';

const app = createApp();
app.component("ChatComponent", ChatComponent);
app.mount('#vue-app');