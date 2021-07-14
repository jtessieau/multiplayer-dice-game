<pre><?php var_dump($_SESSION) ?></pre>
<pre><?php var_dump($_POST) ?></pre>

<div class="container">
</div>

<script>
    // Websocket function
    const conn = new WebSocket('ws://localhost:8080');
    conn.onopen = () => {
        console.log('Connection established');
        const data = {
            "method": "player_connexion",
            "player_guid": "<?= $_SESSION['player_guid']?>",
            "game": "<?= $_POST['game']?>"
        };
        conn.send(JSON.stringify(data));
    }
    conn.onclose = () => {
        console.log('Connection closed');
    }

    conn.onmessage = (e) => {
        let data = JSON.parse(e.data);
        console.log(data);

        if (data.method === 'game_password') {
            let el = document.createElement('div');
            el.classList.add("password");

            el.innerHTML = 'The password for this room is: <strong>' + data.password + '</strong>';
            container.appendChild(el);
        }
    }
    const container = document.querySelector('.container');
    const waitingMessage = document.createElement('div');
    waitingMessage.innerHTML = 'Waiting for player 2';
    waitingMessage.classList.add("waitingMessage");
    container.appendChild(waitingMessage);
</script>