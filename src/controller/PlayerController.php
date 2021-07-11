<?php


namespace App\Controller;

class PlayerController extends AbstractController
{
    public function updateUsername()
    {
        $_SESSION['player_name'] = $_POST['username'];
        echo $_POST['username'];
    }
}