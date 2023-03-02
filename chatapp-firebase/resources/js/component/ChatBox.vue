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
                                    <h3>{{showSelectedUser ? selectedUser : this.provider_name}}</h3>
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
                            <li class="sender">
                                <p> Hey, Are you there? </p>
                                <span class="time">10:06 am</span>
                            </li>
                            <li class="sender">
                                <p> Hey, Are you there? </p>
                                <span class="time">10:16 am</span>
                            </li>
                            <li class="repaly">
                                <p>yes!</p>
                                <span class="time">10:20 am</span>
                            </li>
                            <li class="sender">
                                <p> Hey, Are you there? </p>
                                <span class="time">10:26 am</span>
                            </li>
                            <li class="sender">
                                <p> Hey, Are you there? </p>
                                <span class="time">10:32 am</span>
                            </li>
                            <li class="repaly">
                                <p>How are you?</p>
                                <span class="time">10:35 am</span>
                            </li>
                            <li>
                                <div class="divider">
                                    <h6>Today</h6>
                                </div>
                            </li>
                            <li class="repaly">
                                <p> yes, tell me</p>
                                <span class="time">10:36 am</span>
                            </li>
                            <li class="repaly">
                                <p>yes... on it</p>
                                <span class="time">junt now</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="send-box">
                    <form action="">
                        <input type="text" class="form-control" aria-label="message…" placeholder="Write message…">
                        <button type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send</button>
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
        provider_name: {
            type: String,
            required: true
        },
        selectedUser: String,
        showSelectedUser: true,
    },
    watch: {
        /*selectedUser(newValue, oldValue) {
            /!*console.log('Selected user:' + newValue + ":" +oldValue)*!/
        }*/
    },
}
</script>
