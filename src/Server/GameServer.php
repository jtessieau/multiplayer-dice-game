<?php

namespace App\Server;

use App\Model\GameModel;
use App\Model\PlayerModel;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class GameServer implements MessageComponentInterface
{
    protected array $games;
    protected array $players;

    public function __construct()
    {
        $this->games = [];
        $this->players = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {

        // When player connect to ws, generate an id and send it to him
        $player = new PlayerModel();
        $this->players[$player->guid] = [
            "conn" =>$conn,
            "player" => $player
            ];

        //
        $this->players[$player->guid]["conn"]->send(json_encode($this->players[$player->guid]));

        // Echo connexion id on server terminal
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        if (isset($data['method'])) {
            switch ($data['method']) {
                case 'player_connexion':
                    $return = [
                        "method" => "game_password",
                        "password" => $this->connexion($from, $data)
                    ];
                    $from->send(json_encode($return));
                    break;
                case 'find_game':
                    echo "looking for games ...";
                    $return = ["method" => "find_game", "message" => "Game not found"];

                    foreach ($this->games as $game) {
                        if ($game->password = $data['password']) {
                            $return = ["method" => "find_game", "message" => "Game found"];
                        }
                    }
                    $from->send(json_encode($return));

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


    public function connexion($from, $data)
    {
        $this->players[] = [
            "resourceId" => $from->resourceId,
            "guid" => $data['player_guid']
        ];

        if ($data['game'] === "New Game") {
            $game = new GameModel();
            $game->creatorId = $data['player_guid'];
            $game->player1 = $data['player_guid'];
            $game->password = $game->randomPass();
            $this->games[] = $game;

            return $game->password;
        }

        if ($data['game'] === "Join Game") {
//
        }
    }
}