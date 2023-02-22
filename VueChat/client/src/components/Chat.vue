<script>
export default {
    name: 'ChatApp',
    data() {
        return {
            text: '',
            messages: [],
        }
    },
    methods: {
        sendMessage() {
            if(this.text !== '') {
                this.$socket.emit('sendMessage', this.text) // emit len server
                this.messages.push({
                    message: this.text,
                    type: 'Tôi'
                })
            }
            this.text = ''
        }
    },
    sockets: {
        statusRoom: function(message) {
            this.messages.push({
                message,
                type: 'status',
            })
        },
        // nhan message tu client trong room, push message vao array ban dau
        receiveMessage: function(message) {
            this.messages.push({
                message,
                type: 'Người lạ',
            })
        }
    },
}
</script>
<template>
    <div class="container">
    <div class="messaging">
      <div class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Chat</h4>
            </div>
            <div class="srch_bar">
              <div class="stylish-input-group">
                <input type="text" class="search-bar" placeholder="Tìm kiếm trên message" >
                <span class="input-group-addon">
                <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </span> </div>
            </div>
          </div>
          <div class="inbox_chat">
            <div class="chat_list active_chat">
              <div class="chat_people">
                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5>Client <span class="chat_date">Dec 25</span></h5>
                  <p>Hi there!.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="mesgs">
          <div class="msg_history">
            <div v-for="(message, index) in messages" :key="index">
                <!-- <div class="incoming_msg">
                    <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                    <div class="received_msg">
                        <div class="received_withd_msg">
                        <p>Test which is a new approach to have all
                            solutions</p>
                        <span class="time_date"> 11:01 AM    |    June 9</span></div>
                    </div>
                </div> -->
                <div class="outgoing_msg">
                <div class="sent_msg">
                    <p>{{ message.type }} : {{ message.message }}</p>
                    <span class="time_date"> 00:00 AM</span> </div>
                </div>
            </div>
          </div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input v-model="text" type="text" @keyup.enter="sendMessage" class="write_msg" placeholder="Aa" />
              <button class="msg_send_btn" @click="sendMessage"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
</template>