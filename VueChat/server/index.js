import { createServer } from "http";
const httpServer = createServer();
import Server from 'socket.io';
const io = new Server(httpServer, {})

//join vao room <2 client. Neu room do >2 client thi se tu tao 1 room moi va join vao 
const getClientRoom = () => {
    let index = 0;
    while (true) {
        if(!io.sockets.adapter.rooms[index] || io.sockets.adapter.rooms[index].length < 2) {
            return index;[]
        }
        index ++;
    }
}

io.on('connect', (socket)=>{
    console.log("có người vừa kết nối!");
    const clientRoom = getClientRoom(); // lay client room thoa man dk
    socket.join(clientRoom); // goi den clientRoom
    if(io.sockets.adapter.rooms[clientRoom].length < 2) { // kiem tra xem room nao <2 client
        io.in(clientRoom).emit('statusRoom', 'Đang chờ người lạ vào...'); // gui sk cho tat ca client trong room
    } else {
        io.in(clientRoom).emit('statusRoom', 'Có người lạ vừa vào!');
    }
    socket.on('disconnect', (reason) => {
        socket.to(clientRoom).emit('statusRoom', 'Người lạ đã thoát phòng. Chờ người khác...');
    });
    socket.on('sendMessage', function (message) { // nhan message tu client
        socket.to(clientRoom).emit('receiveMessage', message); // emit message ay cho nguoi trong room ngoai tru nguoi gui
    })
});
httpServer.listen(3000);