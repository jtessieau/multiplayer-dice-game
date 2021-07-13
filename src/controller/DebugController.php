<?php


namespace App\Controller;


class DebugController
{
    public function getSession()
    {
        var_dump($_SESSION);
    }
}