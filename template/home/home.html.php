<div class="container">

    <div class="row justify-content-center mb-5">
        <div class="col">
            <img src="images/logo.png" alt="logo">
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h2>Welcome</h2>
            <div class="inputPlayerName">
                <span class="displayPlayerName"></span>
                <input type="hidden" name="playerName" class="inputEditPlayerName" placeholder="">
                <button class="btn btn-primary btnEditPlayerName">Edit</i></button>
            </div>
        </div>
    </div>


    <div class="row justify-content-center" id="gameSelector">
        <input type="submit" class="btn btn-primary mb-3" id="newGameButton" name="game" value="New Game">

        <button class="btn btn-light" id="joinButton">Join a game</button>
    </div>

    <div class="row" id="joinForm">
        <input type="text" name="gameCode" id="inputGameId" placeholder="Enter game code">
        <div class="form-text"></div>
        <input type="submit" class="btn btn-primary" name="game" value="Join Game" id="btnJoin">

    </div>
</div>

<script src="js/app.js"></script>
