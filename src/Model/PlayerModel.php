<?php


namespace App\Model;


class PlayerModel extends AbstractModel
{
    public int $id;
    public string $guid;
    public string $name = "";
    public int $scoreTotal = 0;
    public int $scoreCurrent = 0;

    public function __construct()
    {
        if (isset ($_SESSION['player_guid'])) {
            $this->guid = $_SESSION['player_guid'];
            $this->name = $_SESSION['player_name'];
        } else {
            $this->guid = $this->getGUID();
            $_SESSION['player_guid'] = $this->guid;
            $_SESSION['player_name'] = "Player " . substr($this->guid,1,5);
        }

        if (isset($_SESSION['player_name'])) {
            $this->name = $_SESSION['player_name'];
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): PlayerModel
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PlayerModel
    {
        $this->name = ucfirst($name);
        return $this;
    }

    public function getScoreTotal(): int
    {
        return $this->scoreTotal;
    }

    public function setScoreTotal(int $scoreTotal): PlayerModel
    {
        $this->scoreTotal = $scoreTotal;
        return $this;
    }

    public function getScoreCurrent(): int
    {
        return $this->scoreCurrent;
    }

    public function setScoreCurrent(int $scoreCurrent): PlayerModel
    {
        $this->scoreCurrent = $scoreCurrent;
        return $this;
    }

    public function persist()
    {
        $db = $this->getPDO();
        $sql = "INSERT INTO player (name ,scoreCurrent, scoreTotal) VALUE (?,?,?)";
        $db->prepare($sql);
        $db->execute($this->getName(), $this->getScoreCurrent(), $this->getScoreTotal());
    }

}