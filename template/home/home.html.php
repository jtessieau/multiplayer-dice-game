<pre><?php var_dump($_SESSION) ?></pre>

<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col text-center">
            <h2>Welcome</h2>
            <div class="inputPlayerName">
                <span><?= $player->getName(); ?></span>
                <input type="hidden" name="playerName" placeholder="<?= $player->getName() ?>">
                <button class="btn btn-primary" id="playerSubmit">Edit</i></button>
            </div>
        </div>
    </div>


    <div class="row justify-content-center" id="gameSelector">
        <form action="/game" method="post" class="d-grid">
            <input type="submit" class="btn btn-primary mb-3" id="newGameButton" name="game" value="New Game">
        </form>

        <button class="btn btn-light" id="joinButton">Join a game</button>
    </div>

    <div class="row" id="joinForm">
        <form action="/game" method="post">
            <input type="text" name="gameCode" id="inputGameId" placeholder="Enter game code">
            <div class="form-text"></div>
            <input type="submit" class="btn btn-primary" name="game" value="Join Game" id="btnJoin">
        </form>

    </div>

</div>

<script>
    // Player Id
    let playerId = ''

    // Websocket
    const ws = new WebSocket('ws://localhost:8080')
    ws.onopen = () => {
        console.log('Connexion established')
        let request = {
            "action":"connexion"
        }
        ws.send(JSON.stringify(request));
    };

    ws.onmessage = (e) => {
        console.log('New message: ')
        let response = JSON.parse(e.data)
        console.log(response)

        if (response.action === "playerId"){
            playerId = response.playerId
            let el = document.createElement('div')
            el.innerText = playerId
            document.querySelector('body').appendChild(el)
        }

        if (response.action === "createGame") {
            let el = document.createElement('div')
            el.innerText = response.game.gameId
            document.querySelector('body').appendChild(el)
        }
    };

    // Dom elements
    const btnCreate = document.querySelector('#newGameButton')
    btnCreate.addEventListener('click', (e) => {
        e.preventDefault()
        let request = {
            "action":"createGame"
        }
        ws.send(JSON.stringify(request))
    })

    const btnJoin = document.querySelector('#btnJoin')
    const inputGameId = document.querySelector('#inputGameId')

    btnJoin.addEventListener('click', (e) => {
        e.preventDefault()
        let request = {
            "action": "joinGame",
            "gameId": inputGameId.value
        }

        ws.send(JSON.stringify(request))
    })
</script>
