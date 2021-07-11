<pre><?php var_dump($_SESSION) ?></pre>

<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col">
            <h2>Welcome</h2>
            <label for="playerName">Player Name</label>
            <input type="text" name="playerName" id="playerName"
                   value="<?php echo $player->getName(); ?>"
                   autocomplete="off" class="form-control mb-3">

            <button class="btn btn-primary" id="playerSubmit">edit</i></button>
        </div>
    </div>


    <div class="row justify-content-center" id="gameSelector">
        <button class="btn btn-light" id="joinButton">Join a game</button>
        <a class="btn btn-primary" id="createButton" href="/gameboard">New game</a>
    </div>

    <div class="row" id="joinForm">
        <form action="/gameboard" method="post">
            <input type="text" name="gameCode" id="gameCode" placeholder="Enter game code">
            <div class="form-text"></div>
            <button type="submit" class="btn btn-primary">Join</button>
        </form>

    </div>

</div>

<script>

    // Edit player name
    const usernameInput = document.querySelector('#playerName');
    const editButton = document.querySelector('#playerSubmit');

    editButton.addEventListener('click', () => {
            const xhr = new XMLHttpRequest();

            let param = "username=" + usernameInput.value;

            xhr.open('POST', '/player/updateUsername', true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    editButton.innerText = "Done"

                }
            }

            xhr.send(param);
        }
    );

    // Join a game
    const joinForm = document.querySelector('#joinForm');
    const joinButton = document.querySelector('#joinButton');
    const gameSelector = document.querySelector('#gameSelector')

    joinForm.style.display = "none";

    joinButton.addEventListener('click', (e) => {
        joinForm.style.display = "block";
        gameSelector.style.display = "none";
    });

    const submitJoin = document.querySelector('#joinForm button')

    submitJoin.addEventListener('click', (e) => {
        e.preventDefault();
        fetch('/game/findGame')
            .then(async (response) => {
                if (response.ok) {
                    const formText = document.querySelector('#joinForm .form-text');
                    formText.innerText = await response.text();
                }
            });
    });

</script>
