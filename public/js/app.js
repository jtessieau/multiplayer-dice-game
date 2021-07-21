// Connect to websocket
const ws = new WebSocket('ws://acronyme.me:8080')

// Player Id
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

let player = {
    "id": '',
    "name": getCookie("playerName")
};
let game = {
    "id": ''
};

// Homepage elements
const content = document.querySelector('.content');

const displayPlayerName = document.querySelector(".displayPlayerName");
const inputEditPlayerName = document.querySelector(".inputEditPlayerName");
const btnEditPlayerName = document.querySelector(".btnEditPlayerName");

const btnLauncher = document.querySelector('.btnLauncher');
const btnCreate = document.querySelector('#newGameButton')
const btnJoin = document.querySelector('#btnJoin');
const inputGameId = document.querySelector('#inputGameId');

function escapeHtml(str)
{
    const map =
        {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
    return str.replace(/[&<>"']/g, function(m) {return map[m];});
}

// Event listener

// Modify player name
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
        player.name = escapeHtml(inputEditPlayerName.value);
        document.cookie = "playerName="+player.name;
        inputEditPlayerName.type = "hidden";
        displayPlayerName.style.display = "inline-block";
        displayPlayerName.innerText = player.name;
        btnEditPlayerName.style.display = "inline";
    }
})

// Websocket Send action

// Create a new game
btnCreate.addEventListener('click', (e) => {
    e.preventDefault()
    let request = {
        "action": "createGame",
        "playerName": player.name
    }
    ws.send(JSON.stringify(request))
})

// Join a game
btnJoin.addEventListener("click", (e) => {
    btnLauncher.style.display = "none";
    inputGameId.style.display = "block";
    inputGameId.focus();
})

inputGameId.addEventListener('keyup', (e) => {
    if (e.code === "Enter") {

        let request = {
            "action": "joinGame",
            "playerName": player.name,
            "gameId": escapeHtml(inputGameId.value)
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

// Charge game board
const gameboard = document.createElement('div')
fetch('/gameboard').then( async (res) => {
    gameboard.innerHTML = await res.text()
})

function loadGame() {
    content.innerHTML = '';
    content.appendChild(gameboard);
}

function updateGame(response) {
    document.querySelector('#dice').className = 'dice' + response.game.diceScore;

    document.querySelector('#player1').innerText = response.game.players[0].name;
    document.querySelector('#player1Current').innerText = response.game.players[0].currentScore;
    document.querySelector('#player1Total').innerText = response.game.players[0].totalScore;

    document.querySelector('#player2').innerText = response.game.players[1].name;
    document.querySelector('#player2Current').innerText = response.game.players[1].currentScore;
    document.querySelector('#player2Total').innerText = response.game.players[1].totalScore;

    if (response.game.currentPlayer === 0) {
        document.querySelector('#player1').classList.add('active-player');
        document.querySelector('#player2').classList.remove('active-player');
    } else {
        document.querySelector('#player1').classList.remove('active-player');
        document.querySelector('#player2').classList.add('active-player');
    }

}

// Websocket
ws.onerror = () => {
    if (ws.readyState === 3) {
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
        if(player.name === '') {
            player.name = "Player" + player.id;
            document.cookie = "playerName="+player.name;
        }
        displayPlayerName.innerHTML = player.name;
    }

    if (response.action === "createGame") {
        game.id = response.game.gameId
        content.innerHTML = '<div class="gameCode">Game code: ' + game.id + '</div>'
        content.innerHTML += '<div class="waiting">Waiting for the second player ...</div>'
    }

    if (response.action === "playerJoin") {
        loadGame();
        updateGame(response);

    }

    if (response.action === "joinGame") {
        if (response.message === "Game Found") {
            game.id = response.game.gameId

            loadGame();
            updateGame(response);

        } else {
            // Todo: Game not found
        }
    }

    if (response.action === "updateGameBoard") {
        updateGame(response);
        document.querySelector('#dice').classList.remove("animateDice")
        void document.querySelector('#dice').offsetWidth // To reset animation
        document.querySelector('#dice').classList.add("animateDice")

        if (response.game.winner !== null) {

            // todo: winner display
            alert('winner')
        }
    }

    if (response.action === "disconnectedPlayer") {
        alert(response.message);
        window.location.href = '/';
    }
};