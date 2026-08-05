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
                <div class="header-actions">
                    <a class="secondary-button" href="homepage.php">
                        <i class="fa-solid fa-house"></i>
                        Home
                    </a>
                    <a class="secondary-button" href="../Back-End/logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </a>
                </div>
            </header>
            <label for="chat-message">Message</label>
            <input class="message-input" id="chat-message" type="text" autocomplete="off" placeholder="Type a Message" style="width:450px;" size="30"
                onkeypress="var user = username_val; myWebSocket.emit('typing', user); EnterToSend(event)">                
            <button id="send" class="compact-button">Send</button>
            <div id="typing"></div>
            <label style="padding-top: 5px;" for="responses">Chat Box</label>
            <div class="chat-box" id="responses"></div>
        </section>
    </main>
    <script src="/socket.io/socket.io.js"></script>
    <script>
        var username_val = "<?php echo htmlspecialchars($_SESSION['username']) ?>"
    </script>
    <script>
        const myWebSocket = io();

        document.getElementById("send").addEventListener('click', SendMsg)

        function SendMsg() {
            var msg = document.getElementById("chat-message").value.trim();
            var user = username_val;
            // var user = "corey";
            if (!msg) return;
            console.log(`Dubug> Message Being Sent to Server: ${msg} from ${user}`);
            myWebSocket.emit("message", msg, user);
            var msg = document.getElementById("chat-message").value = "";

        }

        myWebSocket.on("display-msg", (data) => {
            var responses = document.getElementById('responses');
            var div = document.createElement('div');
            var timeStamp = document.createElement('span');
            var messageText = document.createElement('span');

            timeStamp.style.color = '#2431e5';
            timeStamp.textContent = '[' + new Date().toLocaleTimeString() + '] ';
            messageText.textContent = data;

            div.appendChild(timeStamp);
            div.appendChild(messageText);
            responses.appendChild(div);
            responses.scrollTop = responses.scrollHeight;
        });

        function EnterToSend(event) {
            if (event.key === 'Enter') {
                SendMsg();
            }
            return;
        }

        myWebSocket.on("typing", (user) => {
            var typing = document.getElementById('typing');
            typing.textContent = `${user} is typing ...`;
            setTimeout(function () { typing.textContent = ""; }, 500);
        });

    </script>
</body>

</html>