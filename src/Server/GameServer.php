<?php

namespace App\Server;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class GameServer implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    protected array $games;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->games = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        $data = [
            "event" => "connexion",
            "resourceId" => $conn->resourceId
        ];

        // Send connexion id to client.
        $conn->send(json_encode($data));

        // Echo connexion id on server terminal
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {

        $data = json_decode($msg, true);

        if (isset($data['event'])) {
            switch ($data['event']) {
                case 'newGame':
                    if (!empty($this->games)) {
                        foreach ($this->games as $game) {
                            if ($game['creator_resourceId'] = $data['player_resourceId']) {
                                $data = $game;
                                $data["event"] = "GameServer Found";
                            }
                        }
                    } else {
                        $gameData = [
                            "event" => "GameServer Created",
                            "creator_resourceId" => $data["player_resourceId"],
                            "game_password" => $this->randomPass(),
                            "player1" => [
                                "id" => $data["player_resourceId"],
                                "name" => "",
                                "currentScore" => 0,
                                "totalScore" => 0
                            ],
                            "player2" => [
                                "id" => null,
                                "name" => "",
                                "currentScore" => 0,
                                "totalScore" => 0
                            ],
                            "currentPlayer" => "player1"
                        ];

                        $this->games[] = $gameData;
                    }
                    $from->send(json_encode($data));
                    break;

                case 'joinGame':
                    foreach ($this->games as $game) {
                        if ($data['code'] === $game['game_password']){
                            $data = $game;
                            $found = true;
                        }
                    }
                    !$found ?
                    $data = [
                        "event" => "GameServer not found"
                    ] : $data['event'] = "GameServer found";
                    $from->send(json_encode($data));
                    break;
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}