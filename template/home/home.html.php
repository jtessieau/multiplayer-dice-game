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
    const inputPlayerName = document.querySelector('.inputPlayerName input');
    const spanPlayerName = document.querySelector('.inputPlayerName span');
    const btnEdit = document.querySelector('.inputPlayerName button');

    btnEdit.addEventListener('click', () => {
            if (btnEdit.innerHTML === 'Edit') {
                btnEdit.innerHTML = 'Ok';
                spanPlayerName.style.display = "none";
                inputPlayerName.type = 'text';
                inputPlayerName.focus();

                inputPlayerName.addEventListener('keyup', (e)=>{
                    if (e.code === "Enter" && document.activeElement === inputPlayerName) {
                        updatePlayerName();
                    }
                })
            } else {
                updatePlayerName()
            }
        });

    function updatePlayerName() {
        const xhr = new XMLHttpRequest();

        if (inputPlayerName.value !== '' && inputPlayerName.placeholder !== inputPlayerName.value) {


            let param = "username=" + inputPlayerName.value;

            xhr.open('POST', '/player/updateUsername', true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    spanPlayerName.innerHTML = inputPlayerName.value;
                    inputPlayerName.placeholder = inputPlayerName.value;
                    inputPlayerName.value = '';
                }
            }

            xhr.send(param);
        }

        btnEdit.innerHTML = 'Edit';
        inputPlayerName.type = 'hidden';
        spanPlayerName.style.display = 'inline-block';
    }

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


    // Refresh $_SESSION

    let session = document.querySelector('pre')
    setInterval(() => {
        fetch('/debug/session')
            .then(async (response) => {
                if (response.ok) {
                    session.innerHTML = await response.text();
                }
            })
    }, 1000);

</script>
