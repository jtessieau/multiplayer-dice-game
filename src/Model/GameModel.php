<?php


namespace App\Model;

class GameModel extends AbstractModel
{
    public int $id;
    public string $password;
    public bool $isFull;
    public string $player1;
    public string $player2;
    public string $currentPlayer;
    public string $creatorId;

    public function __construct()
    {
        $this->isFull = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): GameModel
    {
        $this->id = $id;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): GameModel
    {
        $this->password = $password;
        return $this;
    }

    public function isFull(): bool
    {
        return $this->isFull;
    }


    public function setIsFull(bool $isFull): GameModel
    {
        $this->isFull = $isFull;
        return $this;
    }

    public function getPlayer1(): PlayerModel
    {
        return $this->player1;
    }

    public function setPlayer1(PlayerModel $player1): GameModel
    {
        $this->player1 = $player1;
        return $this;
    }

    public function getPlayer2(): PlayerModel
    {
        return $this->player2;
    }

    public function setPlayer2(PlayerModel $player2): GameModel
    {
        $this->player2 = $player2;
        return $this;
    }

    public function getCurrentPlayer(): PlayerModel
    {
        return $this->currentPlayer;
    }

    public function setCurrentPlayer(PlayerModel $currentPlayer): GameModel
    {
        $this->currentPlayer = $currentPlayer;
        return $this;
    }

    public function persist()
    {
        $db = $this->getPDO();
        $sql = "INSERT INTO game () VALUES ()";
        $stmt = $db->prepare($sql)
            ->execute();
    }

    public function findGameById(int $id): self
    {
        $sql = "SELECT * FROM game WHERE id=?";
        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        return $this;
    }
}