<template>
    <!-- char-area -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="chat-area">
                    <!-- chatlist -->
                    <ChatList
                        :current_id="current_id"
                        @selectName="handleSelectedName"
                        @selectId="handleSelectedId"
                        :chatlist="chatlist">
                    </ChatList>
                    <!-- chatlist -->
                    <!-- chatbox -->
                    <ChatBox
                        :current_id="current_id"
                        :receiver_name="receiver_name"
                        :receiver_id="receiver_id"
                        :selectedName="selectedName"
                        :selectedId="selectedId"
                        :showSelectedName="showSelectedName"
                        :messages="messages"
                        @new-message="handleNewMessage">
                    </ChatBox>
                    <!-- chatbox -->
                </div>
            </div>
        </div>
    </div>
    <!-- char-area -->
</template>

<script>
import ChatList from "./ChatList.vue";
import ChatBox from "./ChatBox.vue";
import axios from "axios";

export default {
    name: "AppChatComponent",
    components: {
        ChatList,
        ChatBox
    },
    props: ['current_id', 'receiver_id', 'receiver_name', 'conversation_id'],
    data() {
      return {
          selectedName: null,
          showSelectedName: false,
          chatlist: [],
          isLoaded: false,
          selectedId: null,
          messages: [],
      }
    },
    methods: {
        handleSelectedName(value) {
            this.selectedName = value;
            this.showSelectedName = true;
        },
        handleSelectedId(value) {
            this.selectedId = value;
        },
        handleNewMessage(newMessage) {
            this.messages.push(newMessage);
        },
        listchat() {
            axios.get("/api/chatlist/" + this.current_id)
                .then(response => {
                    this.chatlist = Object.entries(response.data.listchat);
                    this.isLoaded = true;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        getMessages() {
            axios.get("/api/messages/" + this.current_id + "/" + (this.selectedId ? this.selectedId : this.receiver_id))
                .then((response) => {
                    if (response.data && response.data.length >= 2) {
                        const messages = response.data[0];
                        const currentId = response.data[1];
                        const receiverId = response.data[2];

                        const dataMessage = messages.map((message) => {
                            return {
                                message: message.message !== "undefined" ? message.message : null,
                                file_attach: message.file_attach,
                                created_at: new Date(message.created_at)
                                    .toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true }),
                                current_id: currentId,
                                receiver_id: receiverId
                            };
                        });
                        this.messages = dataMessage;
                    } else {
                        this.messages = ["No Messages"];
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.listchat();
        this.getMessages();
    },
    watch: {
        selectedId: function() {
            this.getMessages()
        }
    }
}
</script>
