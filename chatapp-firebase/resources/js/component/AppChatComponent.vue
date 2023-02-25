<template>
    <div class="container">
        <h3 class=" text-center">Messaging</h3>
        <div class="messaging">
            <div class="inbox_msg">
                <div class="inbox_people">
                    <div class="headind_srch">
                        <div class="recent_heading">
                            <h4>Recent</h4>
                        </div>
                        <div class="srch_bar">
                            <div class="stylish-input-group">
                                <input type="text" class="search-bar"  placeholder="Search" >
                                <span class="input-group-addon">
                                    <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                               </span>
                            </div>
                        </div>
                    </div>
                    <div class="inbox_chat">
                        <div class="chat_list active_chat">
                            <div class="chat_people">
                                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                                <div class="chat_ib">
                                    <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                                    <p>Test, which is a new approach to have all solutions
                                        astrology under one roof.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="chat_list">
                            <div class="chat_people">
                                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                                <div class="chat_ib">
                                    <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                                    <p>Test, which is a new approach to have all solutions
                                        astrology under one roof.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="chat_list">
                            <div class="chat_people">
                                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                                <div class="chat_ib">
                                    <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                                    <p>Test, which is a new approach to have all solutions
                                        astrology under one roof.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="chat_list">
                            <div class="chat_people">
                                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                                <div class="chat_ib">
                                    <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                                    <p>Test, which is a new approach to have all solutions
                                        astrology under one roof.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="chat_list">
                            <div class="chat_people">
                                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                                <div class="chat_ib">
                                    <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                                    <p>Test, which is a new approach to have all solutions
                                        astrology under one roof.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="chat_list">
                            <div class="chat_people">
                                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                                <div class="chat_ib">
                                    <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                                    <p>Test, which is a new approach to have all solutions
                                        astrology under one roof.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="chat_list">
                            <div class="chat_people">
                                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                                <div class="chat_ib">
                                    <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                                    <p>Test, which is a new approach to have all solutions
                                        astrology under one roof.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mesgs">
                    <div class="msg_history">
                        <div v-for="message in listMessages" :key="message.id">
                            <div class="incoming_msg">
                            <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                            <div class="received_msg">
                                <div class="received_withd_msg">
                                    <p>Test which is a new approach to have all
                                        solutions
                                    </p>
                                    <span class="time_date"> 11:01 AM    |    June 9</span>
                                </div>
                            </div>
                        </div>
                            <div class="outgoing_msg">
                                <div class="sent_msg">
                                    <p>{{message}}</p>
                                    <span class="time_date"> {{created_at.toLocaleTimeString()}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="type_msg">
                        <div class="input_msg_write">
                            <input type="text" class="write_msg" placeholder="Type a message"
                            v-model="message" @keyup.enter="sendMessage"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AppChatComponent",
    props: {
        current_id: {
            type: String,
            required: true,
        },
        provider_id: {
            type: String,
            required: true,
        }
    },
    mounted() {
        const conversationRef = db.collection("conversation")
        const query = conversationRef.where('provider_id', '==', this.provider_id)
            .where('seeker_id', '==', this.current_id)
        query.onSnapshot((res)=> {
            res.forEach(doc=>{
                const conversationData = doc.data();
                this.conversation_id = conversationData.id
            })
            this.getMessages()
        })
    },
    data() {
        const now = new Date()
        const utcOffset = 7 * 60 // Số phút tương ứng với 7 giờ
        const vietnamTime = new Date(now.getTime() + utcOffset) // Cộng thêm số phút tương ứng vào thời gian hiện tại
        return {
            message: null,
            conversation_id: null,
            attachment: null,
            provider_seen: 0,
            seeker_seen: 0,
            type: 1,
            deleted_at: 0,
            created_at: vietnamTime,
            listMessages: [],
        }
    },
    created() {
        this.getMessages()
    },
    methods: {
        sendMessage() {
            db.collection("conversation_message").add({
                message: this.message,
                conversation_id: this.conversation_id,
                attachment: this.attachment,
                provider_seen: this.provider_seen,
                seeker_seen: this.seeker_seen,
                type: this.type,
                deleted_at: this.deleted_at,
                created_at: this.created_at
            }).then((docRef)=>{
                this.message = null
                this.conversation_id = this.conversation_id
                this.attachment = null
                this.provider_seen = 0
                this.seeker_seen = 0
                this.getMessages()
            }).catch((error)=>{
                console.log("Send message failed: ", error)
            });
        },
        getMessages() {
            const conversationRef = db.collection("conversation_message")
            const query = conversationRef.where('conversation_id', '==', this.conversation_id)
            query.onSnapshot((snapshot)=>{
                const messages = [];
                snapshot.forEach((doc) => {
                    const data = doc.data()
                    messages.push(data.message);
                });
                this.listMessages = messages
            })
        },
    }
}
</script>
