// Player Id
let player = {
    "id": '',
    "name": ''
};
let game = {
    "id": ''
};

// Homepage dom manipulation

const displayPlayerName = document.querySelector(".displayPlayerName")
const inputEditPlayerName = document.querySelector(".inputEditPlayerName")
const btnEditPlayerName = document.querySelector(".btnEditPlayerName");

btnEditPlayerName.addEventListener('click', (e) => {
    if (inputEditPlayerName.type === "hidden") {
        btnEditPlayerName.style.display = "none";
        inputEditPlayerName.type = "text";
        inputEditPlayerName.value = player.name;
        inputEditPlayerName.focus();
        inputEditPlayerName.select();
        displayPlayerName.style.display = "none";
    }
})

inputEditPlayerName.addEventListener('keyup', (e) => {
    if (e.code === "Enter") {
        player.name = inputEditPlayerName.value;
        inputEditPlayerName.type = "hidden";
        displayPlayerName.style.display = "inline-block";
        displayPlayerName.innerText = player.name;
        btnEditPlayerName.style.display = "inline";
    }
})
const btnLauncher = document.querySelector('.btnLauncher');
const inputGameId = document.querySelector('#inputGameId')
const btnJoin = document.querySelector('#btnJoin');

btnJoin.addEventListener("click", (e) => {
    btnLauncher.style.display = "none";
    inputGameId.style.display = "block";
})

// Websocket
const ws = new WebSocket('ws://localhost:8080')
ws.onerror = () => {
    console.log(ws.readyState)
    if (ws.readyState === 3) {
        console.log("WS connect error");
        document.querySelector('.content').innerHTML = "Can't connect to the server, try again later...";
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
        player.id = response.playerId;
        player.name = "Player" + player.id;
        displayPlayerName.innerHTML = player.name;
    }

    if (response.action === "createGame") {
        game.id = response.game.gameId
        content.innerHTML = '<div class="gameCode">Game code: ' + game.id + '</div>'
        content.innerHTML += '<div class="waiting">Waiting for the second player ...</div>'
    }

    if (response.action === "playerJoin") {
        content.innerHTML = ''
        const gameboard = document.createElement('div')
        fetch('/gameboard').then(async (response) => {
            gameboard.innerHTML = await response.text()
        })
        content.appendChild(gameboard)
    }

    if (response.action === "joinGame") {
        console.log(game)
        if (response.message === "Game Found") {
            console.log(game)
            game.id = response.game.gameId

            content.innerHTML = ''
            const gameboard = document.createElement('div')
            fetch('/gameboard').then(async (res) => {
                gameboard.innerHTML = await res.text()
            })
            content.appendChild(gameboard)
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
const content = document.querySelector('.content');

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
inputGameId.addEventListener('keyup', (e) => {
    if (e.code === "Enter") {

        let request = {
            "action": "joinGame",
            "gameId": inputGameId.value
        }

        ws.send(JSON.stringify(request))
    }

})

// Game Action
content.addEventListener('click', (e) => {
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