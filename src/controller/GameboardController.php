<?php


namespace App\Controller;

use App\Model\GameModel;

class GameboardController extends AbstractController
{
    private GameModel $game;

    public function index(): void
    {
        $this->render('GameServer Board', 'gameboard/gameboard.html.php');
    }

    public function game()
    {
        if(!empty($_POST)) {
            if ($_POST['game'] === 'New Game') {
                // todo
            }

            if ($_POST['game'] === 'Join Game') {
                // todo
            }
        }
        else {
            header('Location: /');
        }


        $this->render('Multiplayer Dice Game', 'gameboard/game.html.php');
    }

}