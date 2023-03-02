<template>
    <!-- char-area -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="chat-area">
                    <!-- chatlist -->
                    <ChatList
                        :current_id="this.current_id"
                        @selectUser="handleSelectedUser">
                    </ChatList>
                    <!-- chatlist -->
                    <!-- chatbox -->
                    <ChatBox
                        :profile_id="this.provider_id"
                        :provider_name="this.provider_name"
                        :selectedUser="selectedUser"
                        :showSelectedUser="showSelectedUser"
                    ></ChatBox>
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

export default {
    name: "AppChatComponent",
    components: {
        ChatList,
        ChatBox
    },
    props: ['current_id', 'provider_id', 'provider_name'],
    data() {
      return {
          selectedUser: null,
          showSelectedUser: false,
      }
    },
    methods: {
        handleSelectedUser(name) {
            this.selectedUser = name;
            this.showSelectedUser = true;
        }
    }
}
</script>
