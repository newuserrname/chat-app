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
                                :class="{'sender': message.receiver_id == this.current_id, 'repaly': message.receiver_id != this.current_id}">
                                <div
                                    @mouseover="showActions(message)"
                                    @mouseleave="hideActions(message)">
                                    <img
                                        class="btn btn-sm img-thumbnail img-fluid"
                                        v-if="message.message == null"
                                        :src="message.file_attach" alt="Responsive image">
                                    <p v-else>{{ message.message }}</p>
                                </div>
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
                            <input
                                id="image-input" type="file" style="display:none;"
                                @change="selectFile">
                        </div>
<!--                        <div class="btn btn-lg">
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
                        </div>-->
                        <div class="btn btn-lg">
                            <i class="fa-solid fa-face-smile" title="Chọn biểu tượng cảm xúc"></i>
                        </div>
                        <Input
                            type="text" class="form-control" aria-label="message…" placeholder="Write message…"
                            v-model="message" @keyup.enter="sendMessage"/>
                        <button type="button" @click="sendMessage">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import emojis from "./EmojiPicker/EmojiPicker.vue"
export default {
    name: "ChatBox",
    components: {
      emojis
    },
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
      return {
          message: this.message,
          selectedFile: null,
      }
    },
    methods: {
        showActions(message) {
            message.showActions = true;
        },
        hideActions(message) {
            message.showActions = false;
        },
        selectFile(event) {
            this.selectedFile = event.target.files[0];
        },
        sendMessage() {
            const formData = new FormData();
            formData.append('currentId', this.current_id);
            formData.append('receiverId', this.selectedId ? this.selectedId : this.receiver_name);
            formData.append('message', this.message);
            if (this.selectedFile) {
                formData.append('attachment', this.selectedFile);
            }
            axios.post('/api/send-message', formData)
                .then((response) => {
                    const newMessage = {
                        type: response.data.type,
                        message: response.data.message,
                        created_at: response.data.created_at
                    };
                    this.$emit('new-message', newMessage);
                    this.message = '';
                    this.selectedFile = null; // Reset selected file
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
}
</script>
<style scoped>
.img-thumbnail{
    max-width: 50%;
}
</style>
