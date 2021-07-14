<?php

namespace App\Controller;

use App\Model\PlayerModel;

class HomeController extends AbstractController
{
    public function index()
    {

        $this->render('Home','home/home.html.php');
    }
}
