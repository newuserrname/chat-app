<template>
    <div class="chatlist">
        <div class="modal-dialog-scrollable">
            <div class="modal-content">
                <div class="chat-header">
                    <div class="msg-search">
                        <input
                            type="text" class="form-control"
                            id="inlineFormInputGroup" placeholder="Search" aria-label="search"
                            v-model="searchUser">
                        <!--                        <a class="add" href="#"><img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/add.svg" alt="add"></a>-->
                    </div>
                </div>
                <div class="modal-body">
                    <div class="chat-lists">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active">
                                <div v-for="(chat, index) in filteredChatLists" :key="index"
                                     @click="selectedConversation(chat)"
                                     class="chat-list">
                                    <a href="#" class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img class="rounded-circle img-fluid" v-bind:src="chat.avatar"
                                                 alt="user img" height="32" width="32">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h3>{{ chat.name }}</h3>
                                            <p>{{ chat.last_message }}</p>
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

export default {
    name: "ChatList",
    props: ['current_id', 'chat_list', 'dataConversation'],
    data() {
        return {
            currentId: this.current_id,
            searchUser: "",
        }
    },
    computed: {
        filteredChatLists() {
            if (this.searchUser.trim() === "") {
                return this.chat_list;
            } else {
                return this.chat_list.filter((chat) => {
                    return chat.name.toLowerCase().includes(this.searchUser.toLowerCase())
                });
            }
        }
    },
    methods: {
        selectedConversation(chat) {
            if (this.isMobile()) {
                window.location.href = '/seeker-conversation/chat-mobile/' + chat.receiver_id;
            } else {
                this.$emit("conversationSelected", chat);
            }
        },
        isMobile() {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                return true
            } else {
                return false
            }
        },
    }
}
</script>

<style>
.modal-body {
    padding-left: 10px;
}

.item-active {
    background: #cccccc42;
    padding-left: 5px
}
</style>
