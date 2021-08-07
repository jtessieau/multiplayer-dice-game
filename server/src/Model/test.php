<?php


require_once 'GameModel.php';

$games = array();

$game = new GameModel();
$games[] = $game;

$game= new GameModel();
$games[] = $game;


$games[0]->setId(50);
$games[1]->setId(45);

$temp = $games[0];
$temp->setId(10);
var_dump($games);