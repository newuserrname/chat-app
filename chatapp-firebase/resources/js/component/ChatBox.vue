<template>
    <div class="chatbox">
        <div class="modal-dialog-scrollable">
            <div class="modal-content">
                <div class="msg-head">
                    <div class="row">
                        <div class="col-8">
                            <div class="d-flex align-items-center">
                                <span class="chat-icon"><img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/arroleftt.svg" alt="image title"></span>
                                <div class="flex-shrink-0">
                                    <img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/user.png" alt="user img">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3>{{showSelectedName ? selectedName: this.receiver_name}}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <ul class="moreoption">
                                <li class="navbar nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Action</a></li>
                                        <li><a class="dropdown-item" href="#">Another action</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="msg-body">
                        <ul>
                            <li
                                v-for="message in messages"
                                :class="{'sender': message.receiver_id == this.current_id, 'repaly': message.receiver_id != this.current_id}"
                                @mouseover="showActions(message)"
                                @mouseleave="hideActions(message)">
                                <p>{{ message.message }}</p>
                                <span class="time">{{ message.created_at }}</span>
                                <div class="actions" v-if="message.showActions">
                                    <button class="btn btn-sm"><i class="fa-solid fa-reply"></i></button>
                                    <button class="btn btn-sm"><i class="fa-solid fa-face-smile"></i></button>
                                    <button class="btn btn-sm"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </li>
                            <li v-if="messages.length === 0">
                                <div class="divider">
                                    <h6>{{ messages[0] }}</h6>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="send-box">
                    <form @submit.prevent>
                        <div class="btn btn-lg">
                            <label for="image-input">
                                <i class="fa-regular fa-image" title="Đính kèm file"></i>
                            </label>
                            <input id="image-input" type="file" style="display:none;">
                        </div>
                        <div class="btn btn-lg">
                            <label for="tag-input">
                                <i class="fa-solid fa-note-sticky" title="Chọn nhãn dán"></i>
                            </label>
                            <input id="tag-input" type="file" style="display:none;">
                        </div>
                        <div class="btn btn-lg">
                            <label for="gif-input">
                                <i class="fa-solid fa-file-import" title="Chọn file gif"></i>
                            </label>
                            <input id="gif-input" type="file" style="display:none;">
                        </div>
                        <input type="text" class="form-control" aria-label="message…" placeholder="Write message…"
                        v-model="message" @keyup.enter="sendMessage">
                        <div class="btn btn-lg">
                            <i class="fa-solid fa-face-smile" title="Chọn biểu tượng cảm xúc"></i>
                        </div>
                        <button type="button" @click="sendMessage">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "ChatBox",
    props: {
        current_id: String,
        receiver_name: String,
        receiver_id: String,
        selectedName: String,
        selectedId: String,
        showSelectedName: true,
        messages: Array
    },
    data() {
        console.log(this.messages)
      return {
          message: this.message
      }
    },
    methods: {
        showActions(message) {
            message.showActions = true;
        },
        hideActions(message) {
            message.showActions = false;
        },
        sendMessage() {
              axios.post("/api/send-message", {
                  currentId: this.current_id,
                  receiverId: this.selectedId ? this.selectedId : this.receiver_name,
                  message: this.message
              }).then((response) => {
                  console.log(response.data);
                  const newMessage = {
                      type: response.data.type,
                      message: response.data.message,
                      created_at: response.data.created_at
                  };
                  this.$emit("new-message", newMessage);
                  this.message = "";
              }).catch(error => {
                  console.log(error);
              })
        }
    },
    watch: {
        /*selectedUser(newValue, oldValue) {
            /!*console.log('Selected user:' + newValue + ":" +oldValue)*!/
        }*/
    },
}
</script>
<style scoped>

</style>
