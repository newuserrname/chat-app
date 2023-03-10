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
import NoMessages from "./NoMessages.vue";

export default {
    name: "AppChatComponent",
    components: {
        ChatList,
        ChatBox,
        NoMessages
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
            const conversationMessageRef = db.collection('conversation_message').get();
            axios.get("/api/messages/" + this.current_id + "/" + (this.selectedId ? this.selectedId : this.receiver_id))
                .then((response) => {
                    if (response.data[0].length === 0) { // check if there are no messages
                        this.messages = null; // set messages to null
                    } else {
                        this.messages = response.data[0].map((message) => { // modify each message object
                            return {
                                dataConversation: message,
                                created_at: new Date(message.created_at)
                                    .toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true }), // convert to gio:phut am format
                                currentId: response.data[1],
                                receiverId: response.data[2],
                            };
                        });
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.listchat();
        db.collection('conversation_message')
            .onSnapshot(querySnapshot => {
                querySnapshot.docChanges().forEach(change => {
                    if (change.type === "added") {
                        // handle added document
                        this.getMessages();
                    }
                    if (change.type === "modified") {
                        // handle modified document
                        this.getMessages();
                    }
                    if (change.type === "removed") {
                        // handle removed document
                        this.getMessages();
                    }
                });
            })
    },
    watch: { // fix
        selectedId: function() {
            this.getMessages()
        }
    }
}
</script>
