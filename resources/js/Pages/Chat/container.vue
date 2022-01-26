<template>
    <app-layout title="Container">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <chat-room-selection
                    v-if="currentRoom.id"
                    :rooms="chatRooms"
                    :currentRoom="currentRoom"
                    @roomChanged="setRoom( $event )"/>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <message-container :messages="messages"/>
                    <input-message :room="currentRoom"
                                   @messageSent="getMessages"/>
                </div>
                <!-- <modal :show='showingModal'>
                    <template #title>
                        Добавился новый пользователь c ником {{ newUser.name }}!
                    </template>

                    <template #content>
                        Хотите отправить ему привет?
                    </template>
                    <template #footer>
                        <button @click="showingModal=false">
                            Не отправлять
                        </button>
                        <danger-button class="ml-3" @click="sendHelloNewUser">
                            Отправить
                        </danger-button>

                        <danger-button class="ml-3" :class="{ 'opacity-25': deleteApiTokenForm.processing }" :disabled="deleteApiTokenForm.processing">
                            Не отправлять
                        </danger-button>
                    </template>
                </modal> -->
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import InputMessage from './inputMessage.vue'
    import MessageContainer from './messageContainer.vue'
    import ChatRoomSelection from './chatRoomSelection.vue'
    import Modal from '../../Jetstream/ConfirmationModal.vue';
    import Button from '../../Jetstream/SecondaryButton.vue';
    import DangerButton from '../../Jetstream/DangerButton.vue';


    export default defineComponent({
        components: {
            AppLayout,
            MessageContainer,
            InputMessage,
            ChatRoomSelection,
            Modal,
            Button,
            DangerButton,
        },
        data(){
            return {
                chatRooms: [],
                currentRoom: [],
                messages: [],
                showingModal: false,
                showingModal: true,
                newUser: {
                    name: 'ivan',
                    id: 14,
                },
            }
        },
        watch:{
            currentRoom(val, oldVal){
                if(oldVal.id){
                    this.disconnect( oldVal );
                }
                this.connect();
            }
        },
        methods:{
            connect(){
                if(this.currentRoom.id){
                    let vm = this;
                    this.getMessages();
                    window.Echo.private('chat.' + this.currentRoom.id)
                    .listen('.message.new', (e) => {
                        vm.getMessages();
                    });
                }
            },
            // sendHelloNewUser(){
            //     this.showingModal = false;
            //     axios.get('/user/notificate/' + this.newUser.id)
            //     .then( res => {
            //         console.log('Succcess');
            //     })
            //     .catch(error => {
            //         console.log(error);
            //     });
            // },
            // watchingCreateUser(){
            //     window.Echo.private('user')
            //         .listen('.user.new', (e) => {
            //             this.newUser = e;
            //             this.showingModal = true;
            //         })
            // },
            disconnect(room){
                window.Echo.leave("chat." + room.id);
            },
            getRooms(){
                axios.get('/chat/rooms')
                .then(response => {
                    this.chatRooms = response.data;
                    this.setRoom(response.data[0]);
                })
                .catch(error => {
                    console.log(error);
                });
            },
            getMessages(){
                axios.get('/chat/room/' + this.currentRoom.id + '/messages')
                .then(response => {
                    this.messages = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
            },
            setRoom(room){
                this.currentRoom = room;
                this.getMessages();
            }
        },
        created(){
            this.getRooms();
            // this.watchingCreateUser();
        }
    })
</script>
