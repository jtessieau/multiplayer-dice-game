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

    public function newGame()
    {
        $this->render('GameServer Board', 'gameboard/gameboard.html.php');
    }

    public function joinGame()
    {
        //todo: join game method.
    }

    public function findGame()
    {

    }
}