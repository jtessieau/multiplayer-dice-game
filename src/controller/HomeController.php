<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    public function index()
    {

        $this->render('Home','home/home.html.php');
    }

    public function gameboard()
    {
        require_once '../template/gameboard/gameboard.html.php';
    }
}
