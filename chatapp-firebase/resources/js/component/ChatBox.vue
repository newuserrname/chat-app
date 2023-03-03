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
                                    <h3>{{showSelectedName ? selectedName: this.provider_name}}</h3>
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
                        <div  v-for="message in messages">
                            <ul>
<!--                                <li>
                                    <div class="divider">
                                        <h6>Today</h6>
                                    </div>
                                </li>-->
                                <li class="sender">
                                    <p> Hey, Are you there? </p>
                                    <span class="time">10:32 am</span>
                                </li>
                                <li class="repaly">
                                    <p>{{ message.message }}</p>
                                    <span class="time">{{ message.created_at }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="send-box">
                    <form @submit.prevent>
                        <input type="text" class="form-control" aria-label="message…" placeholder="Write message…"
                        v-model="message" @keyup.enter="sendMessage">
                        <button type="button"><i class="fa fa-paper-plane" aria-hidden="true"
                        @click="sendMessage"></i> Send</button>
                    </form>
                    <div class="send-btns">
                        <div class="attach">
                            <div class="button-wrapper">
                                <span class="label">
                                    <img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/upload.svg" alt="image title"> attached file
                                </span><input type="file" name="upload" id="upload" class="upload-box" placeholder="Upload File" aria-label="Upload File">
                            </div>
                        </div>
                    </div>
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
        provider_name: String,
        provider_id: String,
        selectedName: String,
        selectedId: String,
        showSelectedName: true,
        messages: Array
    },
    data() {
      return {
          message: this.message = " "
      }
    },
    methods: {
      sendMessage() {
          axios.post("/api/send-message", {
              currentId: this.current_id,
              receiverId: this.selectedId ? this.selectedId : this.provider_id,
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
