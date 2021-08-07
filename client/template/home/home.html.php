<div class="content">
    <div class="landing">

        <div id="title">
            <h1>Dice Game</h1>
            <p class="subh1">Multiplayer</p>
        </div>

        <!-- Edit Player Name -->
        <div id="formPlayerName">
            <div class="displayPlayerName" id="displayPlayerName">Julien</div>
            <input
                    type="text"
                    class="d-none"
                    id="inputEditPlayer"
                    autocomplete="off"
            />
            <button id="btnEditPlayerName"><i class="far fa-edit"></i></button>
        </div>

        <!-- Launcher buttons -->
        <div id ="btnLauncher">
            <button class="btn" id="btnCreateGame">Create Game</button>
            <button class="btn" id="btnJoinGame">Join Game</button>
        </div>

        <!-- Join Game -->
        <div id="formJoinGame" class="d-none">
            <input type="text" id="inputGameCode" placeholder="Enter game code ...">
            <button class="btn btn-warning" id="btnCancel">Cancel</button>
            <button class="btn btn-valid" id="btnJoin">Join</button>
        </div>
    </div>

</div>


<script src="js/app.js"></script>
