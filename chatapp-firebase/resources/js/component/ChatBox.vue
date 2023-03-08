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
                                    <h5></h5>
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
                            <li v-if="messages.length === 0">
                                <div class="divider">
                                    <h6>No message</h6>
                                </div>
                            </li>
                            <li v-for="message in messages" :key="message.dataConversation.id"
                                :class="{'sender': message.receiver_id == this.current_id, 'repaly': message.receiver_id != this.current_id}">
                                <div>
                                    <img class="btn btn-sm img-thumbnail img-fluid stamp-size"
                                         v-if="message.dataConversation.stamp !== null"
                                         :src="message.dataConversation.stamp"
                                         alt="Stampv2">
                                    <img class="btn btn-sm img-thumbnail img-fluid  img-size"
                                         v-if="message.dataConversation.file_attach !== null"
                                         :src="message.dataConversation.file_attach"
                                         alt="Image">
                                    <p v-else>{{ message.dataConversation.message }}</p>
                                </div>
                                <span class="time">{{ message.created_at }}</span>
                                <div class="actions" v-if="message.showActions">
                                    <button class="btn btn-sm"><i class="fa-solid fa-reply"></i></button>
                                    <button class="btn btn-sm"><i class="fa-solid fa-face-smile"></i></button>
                                    <button class="btn btn-sm"><i class="fa-solid fa-trash"></i></button>
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
                        <div class="btn btn-lg">
                            <i class="fa-solid fa-note-sticky" id="show-stampv2" title="Chọn nhãn dán"></i>
                        </div>
                        <div class="stampv2 stampv2_visible" id="stampv2">
                            <div class="stampv2_container">
                                <div class="category">
                                    <img
                                        v-for="item in itemStamps"
                                        :key="item.id"
                                        class="btn btn-sm img-thumbnail img-fluid"
                                        :src="item.stamp_link"
                                        @click="sendStampv2(item.stamp_link)">
                                </div>
                            </div>
                        </div>
                        <div
                            class="btn btn-lg">
                            <i class="fa-solid fa-face-smile" title="Chọn biểu tượng cảm xúc"></i>
                        </div>
                        <input
                            type="text" class="form-control" aria-label="message…" placeholder="Write message…"
                            v-model="message" @keyup.enter="sendMessage"/>
                        <button type="button" @click="sendMessageAndFile()">Send</button>
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
        messages: Array,
    },
    data() {
      return {
          message: this.message,
          selectedFile: null,
          itemStamps: [],
      }
    },
    mounted() {
        this.getStampv2();
    },
    methods: {
        /*showActions(message) {
            message.showActions = true;
        },
        hideActions(message) {
            message.showActions = false;
        },*/
        sendStampv2(link) {
            const formData = new FormData();
            formData.append('currentId', this.current_id);
            formData.append('receiverId', this.selectedId ? this.selectedId : this.receiver_name);
            formData.append('stampV2', link);
            axios.post('/api/send-stampv2', formData)
                .catch((error) => {
                    console.log(error)
                })
        },
        selectFile(event) {
            this.selectedFile = event.target.files[0];
        },
        getStampv2() {
            axios.get('/api/get-stampv2')
                .then((response) => {
                    this.itemStamps = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        sendMessageAndFile() {
            if (this.selectedFile) {
                this.sendFile();
            } else {
                this.sendMessage();
            }
        },
        sendFile() {
            const formData = new FormData();
            formData.append('currentId', this.current_id);
            formData.append('receiverId', this.selectedId ? this.selectedId : this.receiver_name);
            if (this.selectedFile) {
                formData.append('attachment', this.selectedFile);
            }
            axios.post('/api/send-file-attach', formData)
                .then((response) => {
                    console.log(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
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
.img-size{
    max-width: 40%;
}
.stamp-size{
    max-width: 20%;
}
.stampv2 {
    background-color: white;
    border: 1px solid;
    border-radius: 5px;
    overflow: hidden;
    margin: 0;
    position: absolute;
    inset: auto auto 0px 0px;
    transform: translate(30px, -100px);
    width: 20rem;
    height: 20rem;
    max-width: 100%;
    display: none;
}

.stampv2_visible {
    animation-name: slideIn;
    animation-duration: 0.2s;
    animation-timing-function: ease-in-out;
    animation-fill-mode: forwards;
}

.stampv2,
.stampv2_container {
    position: absolute;
    padding: 1rem;
    overflow: auto;
    z-index: 1;
}

.category {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
    color: rgb(169, 169, 169);
}
</style>
