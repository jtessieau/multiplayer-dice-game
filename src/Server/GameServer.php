<?php

namespace App\Server;

use App\Model\GameModel;
use App\Model\PlayerModel;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class GameServer implements MessageComponentInterface
{
    protected array $games;
    protected array $clients;

    public function __construct()
    {
        $this->games = [];
        $this->clients = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store clients
        $this->clients[$conn->resourceId] = $conn;

        // Echo connexion id on server terminal
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Decode the data from clients, must contain an action field.
        $data = json_decode($msg, true);
        $payLoad = array();

        if ($data['action'] === 'connexion') {
            // return the resourceId to the client
            $payLoad = [
                "action" => "playerId",
                "playerId" => $from->resourceId
            ];
        }

        if ($data['action'] === 'createGame') {
            // Todo: create a new game
            $game = [
                "gameId" => $this->getGUID(),
                "players" => [
                    $from->resourceId
                ],
                "diceScore" => 0,
                "winner" => "none"
            ];

            $this->games[$game['gameId']] = $game;

            // Generate payLoad
            $payLoad = [
                "action" => "createGame",
                "game" => $this->games[$game['gameId']]
            ];
        }

        if ($data['action'] === 'joinGame') {
            // Todo: join a game
            if (array_key_exists($data['gameId'], $this->games)) {
                $payLoad = [
                    'action' => 'gameFound'
                ];
            } else {
                $payLoad = [
                    'action' => 'not found'
                ];

            }
        }

        if ($data['action'] === 'rollDice') {
            // Todo: player roll the dice
            // return payLoad
            // if dice score = 1 -> reset score from player
            // and change active player
        }
        if ($data['action'] === 'HoldScore') {
            // Todo: player hold current score
            // return payLoad
            // if total score > 100 :
            // end the game
            // else :
            // reset current score of current player
            // change active player
        }

        // Send the payload to client if not empty
        if (!empty($payLoad)) {
            $from->send(json_encode($payLoad));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        unset($this->clients[$conn->resourceId]);

        // Echo connexion resource of disconnected player
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();

        // Echo connexion resource of disconnected player
        echo "An error has occurred: {$e->getMessage()}\n";
    }

    public function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12)
                . chr(125);// "}"
            return $uuid;
        }
    }
}