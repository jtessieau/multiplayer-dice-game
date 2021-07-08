<?php

namespace App\Controller;

class Home extends App
{
    public function index()
    {
        $this->render('Homepage', 'home/home.html.php');
    }
}
