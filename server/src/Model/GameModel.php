<?php


class GameModel
{
    private int $id;
    private array $players;
    private int $currentPlayer;
    private int $winnerId;
    private int $diceScore;

    public function __construct()
    {
        $this->winnerId = 0;
        $this->diceScore = 1;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}