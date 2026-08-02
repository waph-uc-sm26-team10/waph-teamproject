var http = require('http'), fs = require('fs');

var httpServer = http.createServer(httphandler);
var socketio = require('socket.io')(httpServer);
var port = 8080;
httpServer.listen(port);
console.log("HTTPS server   is listening on port: " + port);

function httphandler(request, response){
    response.writeHead(200);
    var clientUI_stream = fs.createReadStream("../Front-End/client.html")  
    clientUI_stream.pipe(response);
}

socketio.on('connection', function(socketclient){
    console.log("A new socket.IO client is connected: "
        + socketclient.client.conn.remoteAddress + ": " + socketclient.id
    );
});