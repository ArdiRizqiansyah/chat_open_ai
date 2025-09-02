<script setup>
    import axios from 'axios';
    import { ref, onMounted } from 'vue';

    const props = defineProps({
        rooms: {
            type: Object,
            required: false,
        },
        room: {
            type: Object,
            required: false,
        },
        chats: {
            type: Object,
            required: false,
        }
    });

    const rooms = ref([]);
    const messages = ref([]);

    const dataForm = ref({
        id: null,
        content: "",
    });

    // Initialize messages from props if chats exist
    onMounted(() => {
        if (props.rooms && Object.keys(props.rooms).length > 0) {
            Object.values(props.rooms).forEach(room => {
                rooms.value.push({
                    id: room.id,
                    openai_id: room.response_open_ai_id,
                    title: room.title
                });
            });
        }

        if (props.chats && Object.keys(props.chats).length > 0) {
            Object.values(props.chats).forEach(chat => {
                messages.value.push({
                    side: chat.side,
                    content: chat.message
                });
            });
        }

        // Set dataForm.id if room exists and has response_open_ai_id
        if (props.room && props.room.response_open_ai_id) {
            dataForm.value.id = props.room.response_open_ai_id;
        }
    });

    console.log(rooms);

    const isLoading = ref(false);

    const sendMessage = async () => {
        try {
            isLoading.value = true;

            messages.value.push({
                side: 'user',
                content: dataForm.value.content,
            });

            const response = await axios.post('/api/chat', dataForm.value);
            dataForm.value.id = response.data.id;
            dataForm.value.content = "";

            messages.value.push({
                side: 'assistant',
                content: response.data.content,
            });
        } catch (error) {
            console.log(error);
        } finally {
            isLoading.value = false;
        }
    }
</script>

<template>
    <div class="container mx-auto min-vh-100 pt-18">
        <div class="max-w-2xl mx-auto ">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2 border border-gray-400 p-2 rounded-xl">
                    <div class="mb-3">
                        Ai Assistant
                    </div>
                    <div class="grid grid-cols-1 mb-4 gap-3">
                        <template v-for="msg in messages" > 
                            <div class="flex justify-end" v-if="msg.side === 'user'">
                                <div class="bg-gray-50 border border-gray-400 px-2 py-0.5 rounded-xl">
                                    <strong>User</strong><br>
                                    {{ msg.content }}
                                </div>
                            </div>
                            <div class="px-2 py-0.5" v-else>
                                <strong>Assistant</strong>
                                <br>
                                {{ msg.content }}
                            </div>
                        </template>
                    </div>
                    <div class="flex items-center">
                        <input 
                            v-model="dataForm.content"
                            type="text" 
                            class="w-full bg-gray-100 border border-gray-400 rounded-xl px-2 py-1.5" 
                            placeholder="Masukkan inputan"
                        >
                        <button 
                            @click="sendMessage"
                            :disabled="isLoading"
                            class="bg-gray-700 border border-transparent rounded-xl px-4 py-1.5 text-white"
                            >
                            {{ isLoading ? 'Loading...' : 'Kirim' }}
                        </button>
                    </div>
                </div>
                <div class="border border-gray-400 p-2 rounded-xl">
                    <div class="mb-3">
                        History Chat
                    </div>
                    <div class="grid grid-cols-1 mb-4 gap-3">
                        <template v-for="room in rooms" > 
                            <div class="" v-if="room.openai_id == dataForm.id">
                                <a :href="`/chat/${room.id}`" class="flex bg-gray-300 border border-gray-400 px-2 py-0.5 rounded-xl">
                                    {{ room.title }}
                                </a>
                            </div>
                            <div v-else>
                                <a :href="`/chat/${room.id}`" class="flex bg-gray-50 border border-gray-400 px-2 py-0.5 rounded-xl">
                                    {{ room.title }}
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>