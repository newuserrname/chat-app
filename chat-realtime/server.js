const app = require('express')();
const http = require('http').createServer(app);
const io = require('socket.io')(http, {
    cors: {origin: "*"}
});
var Redis = require('ioredis');
var redis = new Redis();
const users = [];

var port = 2022;

http.listen(port, function () {
    console.log('Listening to port: '+port);
});

redis.subscribe('private-channel', function () {
    console.log("subscribed to private channel done");
});

redis.on('message', function (channel, message) {
    message = JSON.parse(message);
    //console.log(message);
    if (channel == 'private-channel') {
        let data = message.data.data;
        let receiver_id = data.receiver_id;
        let event = message.event;

        io.to(`${users[receiver_id]}`).emit(channel + ':' + message.event, data);
    }
});

io.on('connection', (socket) => {
    socket.on("user_connected", function (user_id) {
        users[user_id] = socket.id;
        io.emit('updateUserStatus', users);
        console.log("user connected "+ user_id);
    });
    socket.on('Disconnect', function () {
        const i =  users.indexOf(socket.id);
        users.splice(i, 1, 0);
        io.emit('updateUserStatus', users);
        console.log(users);
    });
});
