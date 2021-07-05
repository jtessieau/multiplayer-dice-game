<?php

namespace App\Controller;

Class Home extends App
{
    public function index()
    {
        $this->render('Homepage', 'home/home.html.php');
    }
}
