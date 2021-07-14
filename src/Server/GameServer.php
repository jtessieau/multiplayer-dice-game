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
                    [
                        "id" => $from->resourceId,
                        "currentScore" => 0,
                        "totalScore" => 0
                    ]
                ],
                "diceScore" => 0,
                "currentPlayer" => rand(0, 1),
                "winner" => null
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
                // Game found, add player to the game
                $game = &$this->games[$data['gameId']];
                $playerExist = false;
                foreach ($game['players'] as $player) {
                    if ($player['id'] === $from->resourceId) {
                        $playerExist = true;
                    }
                }
                if (!$playerExist) {
                    $game["players"][] = [
                        "id" => $from->resourceId,
                        "currentScore" => 0,
                        "totalScore" => 0
                    ];
                    $player1 = $this->games[$data['gameId']]["players"][0];

                    $payLoad = [
                        "action" => "playerJoin",
                        "message" => "Player joined the game",
                        "game" => $this->games[$data['gameId']]
                    ];

                    $this->clients[$player1['id']]->send(json_encode($payLoad));
                }

                $payLoad = [
                    "action" => "joinGame",
                    "message" => "Game Found",
                    "game" => $this->games[$data['gameId']]
                ];
            } else {
                $payLoad = [
                    'action' => 'joinGame',
                    'message' => 'Game not found'
                ];
            }
        }

        if ($data['action'] === 'rollDice') {
            // Todo: player roll the dice
            $game = &$this->games[$data['gameId']];

            if ($data['playerId'] === $game['players'][$game['currentPlayer']]['id']) {
                $game['diceScore'] = rand(1, 6);

                // If dice = 1 -> Zero current score + Change player
                if ($game['diceScore'] === 1) {
                    $game['players'][$game['currentPlayer']]['currentScore'] = 0;
                    if ($game['currentPlayer'] === 0) {
                        $game['currentPlayer'] = 1;
                    } else {
                        $game['currentPlayer'] = 0;
                    }
                } else {
                    $game['players'][$game['currentPlayer']]['currentScore'] += $game['diceScore'];
                }
                $payLoad = [
                    "action" => "updateGameBoard",
                    "game" => $this->games[$data['gameId']]
                ];

                foreach ($game['players'] as $client) {
                    $this->clients[$client['id']]->send(json_encode($payLoad));
                }
            }
        }

        if ($data['action'] === 'holdScore') {
            $game = &$this->games[$data['gameId']];
            if ($data['playerId'] === $game['players'][$game['currentPlayer']]['id']) {
                $game['players'][$game['currentPlayer']]['totalScore'] += $game['players'][$game['currentPlayer']]['currentScore'];
                $game['players'][$game['currentPlayer']]['currentScore'] = 0;
                if ($game['players'][$game['currentPlayer']]['totalScore'] >= 100) {
                    $game['winner'] = $game['currentPlayer'];
                } else {
                    if ($game['currentPlayer'] === 0) {
                        $game['currentPlayer'] = 1;
                    } else {
                        $game['currentPlayer'] = 0;
                    }
                }
                $payLoad = [
                    "action" => "updateGameBoard",
                    "game" => $this->games[$data['gameId']]
                ];

                foreach ($game['players'] as $client) {
                    $this->clients[$client['id']]->send(json_encode($payLoad));
                }
            }
        }

        if ($data['action'] === "newGame") {
            $game = &$this->games[$data['gameId']];
            if ($game['winner'] !== null) {
                $game['winner'] = null;

                foreach ($game['players'] as &$player) {
                    $player['currentScore'] = 0;
                    $player['totalScore'] = 0;
                }

                $payLoad = [
                    "action" => "updateGameBoard",
                    "game" => $this->games[$data['gameId']]
                ];

                foreach ($game['players'] as $client) {
                    $this->clients[$client['id']]->send(json_encode($payLoad));
                }
            }

        }

        // Send the payload to client if not empty
        if (!empty($payLoad && $payLoad['action'] !== "updateGameBoard")) {
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