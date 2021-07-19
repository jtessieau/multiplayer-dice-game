// Player Id
let player = {
    "id":null,
    "name":''
};
let game = {
    "id":null
};

// Websocket
const ws = new WebSocket('ws://localhost:8080')
ws.onerror = () => {
    console.log(ws.readyState)
    if (ws.readyState === 3) {
        console.log("WS connect error");
        document.querySelector('.container').innerHTML = "Can't connect to the server, try again later...";
    }
}
ws.onopen = () => {
    let request = {
        "action": "connexion"
    }
    ws.send(JSON.stringify(request));
};

ws.onmessage = (e) => {
    let response = JSON.parse(e.data)

    if (response.action === "playerId") {
        player.id = response.playerId
    }

    if (response.action === "createGame") {
        game.id = response.game.gameId
        container.innerHTML = '<div class="gameCode">Game code: ' + game.id + '</div>'
        container.innerHTML += '<div class="waiting">Waiting for the second player ...</div>'
    }

    if (response.action === "playerJoin") {
        container.innerHTML = ''
        const game = document.createElement('div')
        fetch('/gameboard').then(async (response) => {
            game.innerHTML = await response.text()
        })
        container.appendChild(game)
    }

    if (response.action === "joinGame") {
        if (response.message === "Game Found") {
            game.id = response.game.gameId

            container.innerHTML = ''
            const game = document.createElement('div')
            fetch('/gameboard').then(async (res) => {
                game.innerHTML = await res.text()
            })
            container.appendChild(game)
        } else {
            // Todo
        }
    }

    if (response.action === "updateGameBoard") {

        let dice = document.querySelector('#dice')
        dice.className = 'dice' + response.game.diceScore
        let player1Current = document.querySelector('#player1Current')
        player1Current.innerText = response.game.players[0].currentScore
        let player1Total = document.querySelector('#player1Total')
        player1Total.innerHTML = response.game.players[0].totalScore
        let player2Current = document.querySelector('#player2Current')
        player2Current.innerHTML = response.game.players[1].currentScore
        let player2Total = document.querySelector('#player2Total')
        player2Total.innerHTML = response.game.players[1].totalScore

        if (response.game.winner !== null) {

            // todo: winner display
            alert('winner')
        }
    }
};

// Dom elements
const container = document.querySelector('.container')

// Create a new game
const btnCreate = document.querySelector('#newGameButton')
btnCreate.addEventListener('click', (e) => {
    e.preventDefault()
    let request = {
        "action": "createGame"
    }
    ws.send(JSON.stringify(request))
})

// Join a game
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

// Game Action
container.addEventListener('click', (e) => {
    e.preventDefault()

    // Roll Dice
    if (e.target.id === "btnRollDice") {
        let request = {
            "action": "rollDice",
            "playerId": player.id,
            "gameId": game.id
        }
        ws.send(JSON.stringify(request))
    }

    // Hold Score
    if (e.target.id === "btnHoldScore") {
        let request = {
            "action": "holdScore",
            "playerId": player.id,
            "gameId": game.id
        }
        ws.send(JSON.stringify(request))
    }

    // New game
    if (e.target.id === "btnNewGame") {
        let request = {
            "action": "newGame",
            "playerId": player.id,
            "gameId": game.id
        }
        ws.send(JSON.stringify(request))
    }
})