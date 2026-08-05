var http = require('http'), fs = require('fs');

var httpServer = http.createServer(httphandler);
var socketio = require('socket.io')(httpServer);
var port = 8080;
httpServer.listen(port);
console.log("HTTPS server   is listening on port: " + port);

function httphandler(request, response){
    response.writeHead(200);
    var clientUI_stream = fs.createReadStream("../Front-End/publicchat.php")  
    clientUI_stream.pipe(response);
}

socketio.on('connection', function(socketclient){
    console.log("A new socket.IO client is connected: "
        + socketclient.client.conn.remoteAddress + ": " + socketclient.id
    );
    socketclient.on("message", function(data, user){
        console.log(`Debug> received a chat message: ${data}`);
        if(!data || data.trim() === '') return;
        if(!user || user.trim() === ''){
            console.log("Debug> Unauthorized user sent a message");
            return;
        }
        var message = `${user} says: ${data}`;
        socketio.emit('display-msg',message);
    });
    socketclient.on("typing", (user) =>{
        if(!user || user.trim() === ''){
            console.log("Debug> Unauthorized user sent a message");
            return;
        }
        console.log(`${user} is typing...`);
        socketclient.broadcast.emit("typing", user);
    })

});

