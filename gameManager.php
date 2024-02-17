<?php
namespace WarTrumpGame;

class GameManager {
    private $playerCount;
    private $players = [];
    private $trumps = [];
    private $field = [];
    private $winner;
    private $isEnd = false;
    private $result = [];
    private $resultArray = [];
    private $resultKey = 0;
    private $resultValue = 0;
    private $input = false;

    public function __construct($playerCount=2,$input=true)
    {
        $this->prepareBeforeStart($playerCount,$input);
        $this->createTrump();
        $this->shuffleTrump();
        $this->dealTrump();
    }



    private function setPlayerCount($playerCount=2){

        if($this->input){
            echo 'ゲームを開始します' . PHP_EOL;
            echo "プレイヤーの人数を入力してください(2〜5): ";
            $this->playerCount = (int)trim(fgets(STDIN));
        }else{
            $this->playerCount = $playerCount;
        }
    }

    private function setPlayer(){
        $playerCount = $this->playerCount;
        if ($this->input) {
            for($i = 0; $i < $playerCount; $i++) {
                echo "プレイヤー" . ($i + 1) . "の名前を入力してください: ";
                $this->players[] = new Player(trim(fgets(STDIN)));
            }
        }else{
            for ($i = 0; $i < $playerCount; $i++) {
                $this->players[] = new Player('プレイヤー' . ($i + 1));
            }
        }
    }

     private function prepareBeforeStart($playerCount=2,$input=false){
        $this->input = $input;
        $this->setPlayerCount($playerCount);
        $this->setPlayer();
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function getTrumps()
    {
        return $this->trumps;
    }

    public function getPlayerCount()
    {
        return $this->playerCount;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getWinner()
    {
        return $this->winner;
    }

    public function getIsEnd()
    {
        return $this->isEnd;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getResultArray()
    {
        return $this->resultArray;
    }

    public function getResultKey()
    {
        return $this->resultKey;
    }

    public function getResultValue()
    {
        return $this->resultValue;
    }

    public function createTrump()
    {
        foreach (Trump::MARKS as $mark) {
            if($mark === "joker"){
                $this->trumps[] = new Trump(mark:$mark);
                continue;
            }
            foreach (Trump::NUMBERS as $number) {
                $this->trumps[] = new Trump(mark:$mark, number:$number);
            }
        }
    }

    public function shuffleTrump()
    {
        shuffle($this->trumps);
    }

    public function dealTrump()
    {
        $trumps = $this->trumps;
        $playerCount = count($this->players);
        $trumpCount = count($trumps);
        for ($i = 0; $i < $trumpCount; $i++) {
            $this->players[$i % $playerCount]->setHand($trumps[$i]);
        }
    }

    public function play()
    {
        $playerCount = count($this->players);
        $fieldCount = 0;
        for ($i = 0; $i < $playerCount; $i++) {
            $fieldCount++;
            $this->field[] = $this->players[$i]->getHand()[$fieldCount - 1];
        }
        $this->judge();
    }

    public function judge()
    {
        $playerCount = count($this->players);
        $fieldCount = count($this->field);
        $maxNumber = 0;
        $maxPlayer = 0;
        for ($i = 0; $i < $fieldCount; $i++) {
            if ($this->field[$i]->getNumber() > $maxNumber) {
                $maxNumber = $this->field[$i]->getNumber();
                $maxPlayer = $i;
            }
        }
        $this->winner = $this->players[$maxPlayer];
        $this->winner->setScore($this->winner->getScore() + 1);
        $this->winner->setHand($this->field);
        $this->field = [];
        $this->resultArray[$this->resultKey] = $this->resultValue;
        $this->resultKey++;
        $this->resultValue++;
        $this->result = $this->resultArray;
        $this->resultArray = [];
        $this->resultKey = 0;
        $this->resultValue = 0;
        $this->isEnd = false;
        for ($i = 0; $i < $playerCount; $i++) {
            if (count($this->players[$i]->getHand()) === 0) {
                $this->isEnd = true;
            }
        }

        if ($this->isEnd) {
            $this->result = [];
            $this->resultArray = [];
            $this->resultKey = 0;
            $this->resultValue = 0;
            for ($i = 0; $i < $playerCount; $i++) {
                $this->resultArray[$this->resultKey] = count($this->players[$i]->getHand());
                $this->resultKey++;
            }
            arsort($this->resultArray);
            $this->result = $this->resultArray;
        }
    }
}

