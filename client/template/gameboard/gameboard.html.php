<div class="container mx-auto my-auto">
    <div class="row text-center mb-4 justify-content-center">
        <div class="col-4">
            <h1>&#x1F437; Dice Game</h1>
            <button id="btnNewGame" class="btn btn-primary btn-lg">New Game</button>
        </div>
    </div>

    <div class="row text-center p-5">
        <div class="col player1Frame align-self-center">
            <h2 id="player1" class="playerName"></h2>
            <div id="player1Total" class="display-1 mb-5">

            </div>
            <div class="displayPlayerCurrent">
                <h3>CURRENT</h3>
                <div id="player1Current" class="display-6"></div>
            </div>
        </div>

        <div class="col align-self-center">
            <div id="dice" class="dice3">
                <div id="dot1" class="dot"></div>
                <div id="dot2" class="dot"></div>
                <div id="dot3" class="dot"></div>
                <div id="dot4" class="dot"></div>
                <div id="dot5" class="dot"></div>
                <div id="dot6" class="dot"></div>
            </div>
            <div class="mb-3 mt-4 d-grid gap-2">
                <button id="btnRollDice" class="btn btn-primary">Roll Dice</button>
                <button id="btnHoldScore" class="btn btn-primary">Hold</button>
            </div>
        </div>

        <div class="col player2Frame align-self-center">
            <h2 id="player2" class="playerName"></h2>
            <div id="player2Total" class="display-1 mb-5">

            </div>
            <div class="displayPlayerCurrent">
                <h3>CURRENT</h3>
                <div id="player2Current" class="display-6"></div>
            </div>
        </div>
    </div>
</div>