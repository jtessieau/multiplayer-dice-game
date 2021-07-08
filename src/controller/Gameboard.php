<?php


namespace App\Controller;


class Gameboard extends App
{
    public function newGame(): void
    {
        $this->password = $this->randomPass();
        $this->render('Game Board','gameboard/gameboard.html.php');
    }

}