<template>
    <div class="chatbox">
        <div v-if="selectedConversation" class="modal-dialog-scrollable">
            <!--            <div v-if="selectedConversation" class="modal-dialog-scrollable">-->
            <div class="modal-content">
                <div class="msg-head">
                    <div class="row">
                        <div class="col-8">
                            <div class="d-flex align-items-center">
                                <span class="chat-icon"><img class="rounded-circle  img-fluid"
                                                             v-bind:src="selectedConversation.avatar" alt="user img"
                                                             height="50" width="50"></span>
                                <div class="flex-shrink-0">
                                    <img class="img-fluid" v-bind:src="selectedConversation.avatar" height="50"
                                         width="50" alt="user img">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3>{{ selectedConversation.name }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <ul class="moreoption">
                                <li class="navbar nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Infomation</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#">Report</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-body" id="currentChat">
                    <div class="msg-body">
                        <ul>
                            <li v-if="dataConversation.length === 0">
                                <div class="divider">
                                    <h6>No message</h6>
                                </div>
                            </li>
                            <template v-for="message in dataConversation" :key="message.dataConversation">
                                <li
                                    :class="{'sender': message.dataMessage.type === 0, 'reply': message.dataMessage.type === 1}">
                                    <template v-if="message.dataMessage.is_deleted === false">
                                        <div>
                                            <template v-if="message.dataMessage.stamp !== null">
                                                <img class="btn btn-sm img-thumbnail img-fluid stamp-size"
                                                     v-on:mouseover="showDeleteButton(message.dataMessage.stamp)"
                                                     v-on:mouseleave="hideDeleteButton()"
                                                     :src="message.dataMessage.stamp"
                                                     alt="Stampv2">
                                            </template>
                                            <template v-if="message.dataMessage.file_attach !== null">
                                                <a v-bind:href="message.dataMessage.file_attach" target="_blank">
                                                    <img class="btn btn-sm img-thumbnail img-fluid  img-size"
                                                         :src="message.dataMessage.file_attach"
                                                         alt="Image"/>
                                                </a>
                                            </template>
                                            <p v-if="message.dataMessage.message">
                                                {{ message.dataMessage.message }}</p>
                                        </div>
                                        <div class="row">
                                            <template v-if="message.dataMessage.type === 1">
                                                <div class="col-8 text-left flex-row">
                                                    <span class="text-left time">{{ message.created_at }}</span>
                                                </div>
                                                <div class="col-4 flex-row-reverse actions">
                                                    <i v-if="message.dataMessage.is_provider_seen === true"
                                                       title="Store read" class="fa fa-check" aria-hidden="true"></i>

                                                    <button title="Delete this message" class="btn btn-sm mr-10"
                                                            @click="deleteValue(message)">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </template>
                                            <template v-if="message.dataMessage.type === 0">
                                                <div class="col-4 flex-row-reverse actions">
                                                    <button title="Make unseen" class="btn btn-sm mr-10"
                                                            @click="makeUnseenValue(message)">
                                                        <span v-if="message.dataMessage.is_seeker_seen === true">Make unseen</span>
                                                    </button>

                                                    <i v-if="message.dataMessage.is_seeker_seen === true"
                                                       title="Store read" class="fa fa-check" aria-hidden="true"></i>
                                                </div>
                                                <div class="col-8 text-right flex-row">
                                                    <span class="text-right time">{{ message.created_at }}</span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
                <div class="send-box">
                    <form @submit.prevent>
                        <div class="btn btn-lg">
                            <label for="image-input">
                                <i class="fa-regular fa-image" title="Attach file"></i>
                            </label>
                            <input
                                id="image-input" type="file" style="display:none;"
                                @change="selectFile">
                        </div>
                        <div @click="clickStamp" class="btn btn-lg">
                            <i class="fa-solid fa-note-sticky" title="Choose Stamp"></i>
                        </div>
                        <div v-show="showStamp" class="stampv2 stampv2_visible">
                            <div class="stampv2_container">
                                <div class="category">
                                    <img
                                        v-for="item in itemStamps"
                                        :key="item.id"
                                        :title="item.stamp_name"
                                        class="btn btn-sm img-thumbnail img-fluid"
                                        :src="item.stamp_link"
                                        @click="sendStampv2(item.stamp_link)">
                                </div>
                            </div>
                        </div>
                        <input
                            type="text" class="form-control" aria-label="message…" placeholder="Write message…"
                            @click="dataConversation && makeSeen(dataConversation)"
                            v-model="message" @keyup.enter="sendMessage"/>
                        <button type="button" @click="sendMessage">Send</button>
                    </form>
                </div>
            </div>
        </div>
        <!--        <div v-else class="alert alert-primary d-flex align-items-center" role="alert">
                    No Conversaion
                </div>-->
    </div>
</template>

<script>
export default {
    name: "SeekerChatBox",
    props: [
        'current_id',
        'selectedConversation',
        'dataConversation',
    ],
    data() {
        return {
            message: this.message,
            currentId: this.current_id,
            showStamp: false,
            itemStamps: null,
        }
    },
    updated() {
        this.$nextTick(() => this.scrollToEnd());
    },
    methods: {
        clickStamp() {
            this.showStamp = !this.showStamp
        },
        scrollToEnd: function () {
            document.getElementById("currentChat").scrollTop = document.getElementById("currentChat").scrollHeight;
        },
        sendFile() {
            const formData = new FormData();
            formData.append('currentId', this.current_id);
            formData.append('receiverId', this.selectedConversation.receiver_id);
            if (this.selectedFile) {
                formData.append('attachment', this.selectedFile);
            }
            this.$emit('new-message');

            axios.post('/seeker-conversation/send-file-attach', formData)
                .then((response) => {

                })
                .catch((error) => {
                    console.log(error);
                });
        },
        sendMessage() {
            const formData = new FormData();
            formData.append('currentId', this.current_id);
            formData.append('receiverId', this.selectedConversation.receiver_id);
            formData.append('message', this.message);
            if (this.selectedFile) {
                formData.append('attachment', this.selectedFile);
            }
            this.$emit('new-message');

            axios.post('/seeker-conversation/send-message', formData)
                .then((response) => {
                    const newMessage = {
                        type: response.data.type,
                        message: response.data.message,
                        created_at: response.data.created_at
                    };
                    this.message = '';
                    this.selectedFile = null;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        deleteValue(value) {
            const conversationMessageRefDocumentId = db.collection('conversation_message').doc(value.dataMessage.id);

            this.$emit('new-message');
            conversationMessageRefDocumentId.update({
                is_deleted: true,
            }).catch((error) => {
                console.log("Error delete: ", error);
            })
        },
        makeUnseenValue(value) {
            const conversationMessageRefDocumentId = db.collection('conversation_message').doc(value.dataMessage.id);

            conversationMessageRefDocumentId.update({
                is_seeker_seen: false,
            }).catch((error) => {
            })
        },
        makeSeen(value) {
            db.collection("conversation_message")
                .where('conversation_id', '==', value[0].dataMessage.conversation_id)
                .where('type', '==', 0)
                .get().then(function(querySnapshot) {
                querySnapshot.forEach(function(doc) {
                    doc.ref.update({
                        is_seeker_seen: true
                    });
                });
            });
        },
        sendStampv2(link) {
            const formData = new FormData();
            formData.append('currentId', this.current_id);
            formData.append('receiverId', this.selectedConversation.receiver_id);
            formData.append('stampV2', link);

            this.$emit('new-message');

            axios.post('/seeker-conversation/send-stampv2', formData)
                .then((response) => {

                })
                .catch((error) => {
                    console.log(error)
                })
        },
        selectFile(event) {
            this.selectedFile = event.target.files[0];
            this.sendFile();
        },
        getStampv2() {
            axios.get('/seeker-conversation/get-stampv2')
                .then((response) => {
                    this.itemStamps = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.getStampv2();
    },
}
</script>
<style scoped>
.img-size {
    max-width: 40%;
}

.stamp-size {
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
    width: 15rem;
    height: 15rem;
    max-width: 100%;
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

.mr-10 {
    margin-right: 5px;
    padding-top: 0px;
    padding-bottom: 0px;
}

/*
ul li.reply p {
    width: 100%
}
 */
li.reply p {
    /*border-top-left-radius: 0px !important;*/
    border-bottom-right-radius: 0px !important;
}

li.sender p {
    border-top-left-radius: 0px !important;
    /*border-bottom-right-radius: 0px !important;*/
}

.text-left {
    text-align: left;
    padding-left: 10px
}
.text-right {
    text-align: right;
    padding-right: 10px
}
</style>
