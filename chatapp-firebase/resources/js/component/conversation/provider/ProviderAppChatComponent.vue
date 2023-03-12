<template>
    <!-- char-area -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="chat-area">
                    <!-- chatlist -->
                    <ChatList
                        :current_id="current_id"
                        :chat_list="chatList"
                        @conversationSelected="loadSelectedConversation"
                        :dataConversation="dataConversation">
                    </ChatList>
                    <!-- chatlist -->
                    <!-- chatbox -->
                    <ChatBox
                        :current_id="current_id"
                        :selectedConversation="selectedConversation"
                        @new-message="triggerNewMessage"
                        :dataConversation="dataConversation">
                    </ChatBox>
                    <!-- chatbox -->
                </div>
            </div>
        </div>
    </div>
    <!-- char-area -->
</template>

<script>
import ChatList from "./ProviderChatList.vue";
import ChatBox from "./ProviderChatBox.vue";
import axios from "axios";

export default {
    name: "AppChatComponent",
    components: {
        ChatList,
        ChatBox,
    },
    props: {
        current_id: String,
    },
    data() {
        return {
            firstCallListChatFirebase: true,
            firstCallMessageFirebase: true,
            currentId: this.current_id,
            lastMessage: '',
            chatList: [],
            receiverId: [],
            receiverName: [],
            selectedConversation: null,
            dataConversation: [],
        }
    },
    methods: {
        triggerNewMessage() {
            this.firstCallListChatFirebase = true
            this.firstCallMessageFirebase = true
        },
        getChatList() {
            axios.get("/provider-conversation/chatlist/")
                .then(response => {
                    this.chatList = response.data.conversation;
                    this.receiverId = response.data.conversation.map(conversation => conversation.id);
                    this.receiverName = response.data.conversation.map(conversation => conversation.name);
                    if (this.chatList.length > 0) {
                        this.selectedConversation = this.chatList[0]
                        this.lastMessage = response.data.last_message
                        this.getMessages()
                    }
                }).catch(error => {
                console.log(error)
            });
        },
        loadSelectedConversation(chat) {
            this.selectedConversation = chat
            this.getMessages();
        },
        getMessages() {
            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
                timeZoneName: 'short'
            };


            if (this.selectedConversation != null && this.selectedConversation.seeker_id != null) {

                axios.get("/provider-conversation/messages/" + this.selectedConversation.seeker_id)
                    .then((response) => {
                        if (response.data[0] != null) {
                            this.dataConversation = response.data[0]
                                .map((result) => {
                                    return {
                                        dataMessage: result,
                                        created_at: new Date(result.created_at).toLocaleTimeString('en-US', options),
                                        currentId: response.data[1],
                                        receiverId: response.data[2],
                                    };
                                });
                            this.triggerNewMessage()
                        } else {
                            this.dataConversation = []
                        }
                    })
                    .catch((error) => {
                        console.log(error)
                    })


            }
        },
    },
    created() {
        db.collection('conversation').where('provider_id', '==', this.currentId)
            .onSnapshot(querySnapshot => {
                if (!this.firstCallListChatFirebase) return;

                querySnapshot.docChanges().forEach(change => {
                    if (change.type === 'added') {
                        this.getChatList()
                    }
                })

                this.firstCallListChatFirebase = false
            });

        db.collection('conversation_message')
            .onSnapshot({includeMetadataChanges: true}, querySnapshot => {
                querySnapshot.docChanges().forEach(change => {

                    if (!this.firstCallMessageFirebase) return;
                    if (change.type === "added" || change.type === "modified") {
                        this.getChatList()
                    }
                    this.firstCallMessageFirebase = false
                })
            })
    },
}
</script>
