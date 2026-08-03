<?php
require __DIR__ . "/../Back-End/session_auth.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Chat - minifacebook</title>
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css?v=<?php echo filemtime(__DIR__ . '/ui.css'); ?>">
</head>

<body>
    <main class="page-shell">
        <section class="content-panel">
            <header class="content-header">
                <div>
                    <p class="project-kicker">miniFacebook</p>
                    <h1>Public Chat</h1>
                </div>
            </header>
            <label for="title">Title</label>
            <input id="chat-message" type="text" autocomplete="off" placeholder="Type a Message" style="width:450px;" size="30"
                onkeypress="var user = document.getElementById('username').value.trim(); myWebSocket.emit('typing', user); EnterToSend(event)">                
            <button id="send" class="secondary-button">Send</button>
            <div id="typing"></div>
            <label for="content">Chat Box</label>
            <div class= id="responses"></div>
            </form>
        </section>
    </main>
    <input type="hidden" id="username" value="<?php echo htmlspecialchars($_SESSION['username']) ?>">
    <script src="/socket.io/socket.io.js"></script>
    <script>
        // Connect to the backend server
        const myWebSocket = io();

        // Log when the connection is successful
        document.getElementById("send").addEventListener('click', SendMsg)

        function SendMsg() {
            var msg = document.getElementById("chat-message").value.trim();
            var user = document.getElementById("username").value.trim();
            // var user = "corey";
            if (!msg) return;
            console.log(`Dubug> Message Being Sent to Server: ${msg} from ${user}`);
            myWebSocket.emit("message", msg, user);
        }

        myWebSocket.on("display-msg", (data) => {
            console.log(data);
            var div = document.createElement('div');
            var timeStamp = new Date().toLocaleTimeString();
            div.innerHTML = '<span style="color: #2431e5">[' + timeStamp + ']</span> ' + data;
            document.getElementById('responses').appendChild(div);
        });

        function EnterToSend(event) {
            if (event.key === 'Enter') {
                SendMsg();
            }
            return;
        }

        myWebSocket.on("typing", (user) => {
            document.getElementById('typing').innerHTML = `${user} is typing ... <br>`;
            setTimeout(function () { document.getElementById("typing").innerHTML = ""; }, 500);
        });



    </script>
</body>

</html>