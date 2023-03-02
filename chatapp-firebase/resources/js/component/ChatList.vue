<template>
    <div class="chatlist">
        <div class="modal-dialog-scrollable">
            <div class="modal-content">
                <div class="chat-header">
                    <div class="msg-search">
                        <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Search" aria-label="search"
                        v-model="searchUser">
<!--                        <a class="add" href="#"><img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/add.svg" alt="add"></a>-->
                    </div>
                </div>
                <div class="modal-body">
                    <!-- chat-list -->
                    <div class="chat-lists">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="Open" role="tabpanel" aria-labelledby="Open-tab">
                                <!-- chat-list -->
                                <div class="chat-list"
                                     v-for="[id, name] in filteredChatList"
                                     v-if="isLoaded"
                                     >
                                    <a @click="selectedUser(name, id)" href="#" class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/user.png" alt="user img">
<!--                                            <span class="active"></span>-->
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h3>{{name}}</h3>
                                            <p>!!...</p>
                                        </div>
                                    </a>
                                </div>
                                <!-- chat-list -->
                            </div>
                        </div>
                    </div>
                    <!-- chat-list -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import chatBox from "@/component/ChatBox.vue";

export default {
    name: "ChatList",
    props: {
        current_id: {
            type: String,
            required: true
        },
    },
    data() {
        return {
            chatlist: [],
            searchUser: "",
            isLoaded: false,
            receiverId: null,
        }
    },
    computed: {
        filteredChatList() {
            const searchUser = this.searchUser.trim().toLowerCase();
            if (Array.isArray(this.chatlist)) {
                return this.chatlist.filter(([key, value]) => {
                    if (typeof key === 'string' && typeof value === 'string') {
                        return value.toLowerCase().includes(searchUser.toLowerCase())
                    }
                    return false
                });
            } else {
                console.log('error search User');
            }
        }

    },
    methods: {
        selectedUser(name, id) {
            this.$emit("selectUser", name)
            this.receiverId = id
            console.log(this.receiverId = id)
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
            axios.get("/api/messages/" + this.current_id + "/" + this.receiverId)
                .then(response => {
                    console.log(response.data)
                })
        }
    },
    mounted() {
        this.listchat();
    },
}
</script>
