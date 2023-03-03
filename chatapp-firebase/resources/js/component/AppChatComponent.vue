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
                        :provider_name="provider_name"
                        :provider_id="provider_id"
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
    props: ['current_id', 'provider_id', 'provider_name'],
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
            axios.get("/api/messages/" + this.current_id + "/" +
                    (this.selectedId ? this.selectedId : this.provider_id))
                .then((response) => {
                    /*const sortMessages = response.data.sort((a, b)=> {
                        new Date(b.created_at) - new Date(a.created_at);
                    });*/
                    const options = { hour12: true, hour: "numeric", minute: "numeric", timeZone: "Asia/Ho_Chi_Minh" };
                    const dataMessage = response.data.map(msg => ({
                        type: msg.type,
                        message: msg.message,
                        created_at: new Date(msg.created_at).toLocaleTimeString("en-US", options)
                    }));
                    this.messages = dataMessage
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
