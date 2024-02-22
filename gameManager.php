<?php
namespace WarTrumpGame;

require_once('fieldCard.php');

class GameManager {

    private $playerCount;
    private $players = [];
    private $trumps = [];
    private $field = [];

    private $poolField = [];

    private $winner;
    private $isEnd = false;
    private $result = [];
    private $resultArray = [];
    private $resultKey = 0;
    private $resultValue = 0;
    private $isInput = false;
    private $candidateWinnerPlayers;
    private $canSubmitPlayers;

    public function __construct($playerCount=2,$isInput=true,$trumps=null)
    {
        $this->prepareBeforeStart($playerCount,$isInput);
        if(!$trumps){
        $this->createTrump();
        $this->shuffleTrump();
    }
    if($trumps){
        $this->trumps = $trumps;
    }
    $this->dealTrump();
    }


    private function setPlayerCount($playerCount=2){

        if($this->isInput){
            echo 'ゲームを開始します' . PHP_EOL;
            echo "プレイヤーの人数を入力してください(2〜5): ";
            $this->playerCount = (int)trim(fgets(STDIN));
        }else{
            $this->playerCount = $playerCount;
        }
    }

    private function setPlayer(){
        $playerCount = $this->playerCount;
        if ($this->isInput) {
            for($i = 0; $i < $playerCount; $i++) {
                $id = $i + 1;
                echo "プレイヤー" . ($i + 1) . "の名前を入力してください: ";
                $this->players[] = new Player(name:trim(fgets(STDIN)),id:$i);
            }
        }else{
            for ($i = 0; $i < $playerCount; $i++) {
                $this->players[] = new Player(name:'プレイヤー' . ($i + 1),id:$i+1);
            }
        }
    }

     private function prepareBeforeStart($playerCount=2,$isInput=false){
        $this->isInput = $isInput;
        echo "戦争を開始します。". PHP_EOL;
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
            if($mark === "ジョーカー"){
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
        $playerCount = $this->playerCount;
        $trumpCount = count($trumps);

        for ($i = 0; $i < $trumpCount; $i++) {
            $this->players[$i % $playerCount]->setHand($trumps[$i]);
        }
        echo "カードが配られました。".PHP_EOL;
    }



    private function getChargedPlayersWhenHand0($needChargePlayers){
        $chargedPlayers = [];

        foreach($needChargePlayers as $needChargePlayer){
            if($needChargePlayer->isPlayerHandEmptyAndDeckNotEmpty()){
                $needChargePlayer->chargeDeckToHand();
                echo $needChargePlayer->getName()."の手札がなくなりました。手元から手札にカードを補充します。".PHP_EOL;
                $chargedPlayers[] = $needChargePlayer;
            }
        }


       return $chargedPlayers;
    }



    private function returnFieldCardToPlayers(){
        foreach($this->field as $fieldCard){
            $card = $fieldCard->getCard();
            $fieldPlayer = $fieldCard->getPlayer();
            $fieldPlayer->setHand($card);
        }
        $this->field = [];
    }

    private function updatePlayers($newPlayers){
        for($i = 0; $i < count($this->players); $i++){
            $player = $this->players[$i];

            foreach($newPlayers as $newPlayer){
                if($player->getId() === $newPlayer->getId())
                    $player = $newPlayer;
            }
            foreach($this->candidateWinnerPlayers as $candidateWinnerPlayer){
                if($player->getId() === $candidateWinnerPlayer->getId())
                    $candidateWinnerPlayer = $player;
            }
        }
    }


    private function canSubmitPlayersHandler($players){

        $canSubmitPlayers = [];
        $needChargeHandPlayers = [];

        foreach($players as $player){
            if(count($player->getHand()) > 0){
                $canSubmitPlayers[] = $player;
            }else{
                $needChargeHandPlayers[] = $player;
            }
        }

        if (count($canSubmitPlayers) < 2) {
            $chargedPlayers = $this->getChargedPlayersWhenHand0($needChargeHandPlayers);
            $canSubmitPlayers = array_merge($canSubmitPlayers, $chargedPlayers);
            $this->updatePlayers($canSubmitPlayers);
        }

        $this->canSubmitPlayers = $canSubmitPlayers;
    }

    private function submitCardToField(){
        $players = $this->players;
        foreach($players as $player){
            $this->field[] = new FieldCard($player);
        }
    }

    private function displayFieldCard(){
        foreach($this->field as $fieldCard){
            echo $fieldCard->getPlayer()->getName(). "のカードは". $fieldCard->getCard()->getCardInfo() ."です。".PHP_EOL;
        }
    }



    private function getIfAisMaxCardPlayers($candidateWinnerPlayers,$maxNumber){

        $candidateWinnerPlayersCount = count($candidateWinnerPlayers);

        if ($candidateWinnerPlayersCount < 2)
            return $candidateWinnerPlayers;

        foreach($candidateWinnerPlayers as $candidateWinnerPlayer){
            foreach($this->field as $filedCard){
                $name = $filedCard->getPlayer();
                if($candidateWinnerPlayer === $name){
                    $card = $filedCard->getCard();
                    if($card->getMark() === "スペード" && $card->getNumber() === TRUMP::SPADE_A_NUMBER){
                        $candidateWinnerPlayers = [];
                        $candidateWinnerPlayers[] = $candidateWinnerPlayer;
                    }
                }
            }
        }

        return $candidateWinnerPlayers;
    }


    public function getCandidateWinnerPlayers(){
        return $this->candidateWinnerPlayers;
    }

    public function getCandidateWinnerPlayersCount(){
        return count($this->candidateWinnerPlayers);
    }

    public function getMaxNumberOnField(){
        $fieldCount = count($this->field);
        $maxNumber = 0;

        for ($i = 0; $i < $fieldCount; $i++) {
            $card = $this->field[$i]->getCard();
            if ($card->getNumber() > $maxNumber) {
                $maxNumber = $card->getNumber();
            }
        }

        return $maxNumber;
    }

    private function setCandidateWinnerPlayers(){
        $maxNumber = $this->getMaxNumberOnField();

        $candidateWinnerPlayers = [];

        foreach($this->field as $filedCard){
            $card = $filedCard->getCard();
            $player = $filedCard->getPlayer();
            if($maxNumber === $card->getNumber()){
                $candidateWinnerPlayers[] = $player;
            }
        }

       $candidateWinnerPlayers = $this->getIfAisMaxCardPlayers(candidateWinnerPlayers:$candidateWinnerPlayers,maxNumber:$maxNumber);
       $this->candidateWinnerPlayers = $candidateWinnerPlayers;
    }

    private function gameEndHandler(){
        foreach($this->players as $player){
            if(count($player->getHand()) === 0){
                echo $player->getName()."の手札がなくなりました。".PHP_EOL;
                if(count($this->candidateWinnerPlayers) > 1)
                    $this->returnFieldCardToPlayers();

                $this->isEnd = true;
                break;
            }
        }
        if(!$this->isEnd){
            return;
        }
        $gameWinner = $this->players[0];
        $winnerScore = count($gameWinner->getDeck())+count($gameWinner->getHand());

        $array = [];
        foreach($this->players as $player){
            $playerScore = count($player->getDeck())+count($player->getHand());
            $value[$playerScore] = $player;
            $array[] = $value;
            echo $player->getName()."の手札の枚数は".$playerScore."枚です。";
            if($winnerScore < $playerScore){
                $gameWinner = $player;
                $winnerScore = count($gameWinner->getDeck())+count($gameWinner->getHand());
            }
        }

        echo PHP_EOL;

        krsort($array);
    }

    private function winnerHandler(){

        if(count($this->candidateWinnerPlayers) > 1)
            return;

        $winner = $this->candidateWinnerPlayers[0];

        $fieldCards = [];

        foreach($this->field as $fieldCard){
            $fieldCards[] = $fieldCard->getCard();
        }


        $poolFieldCards = [];
        foreach($this->poolField as $fieldCard){
            $poolFieldCards[] = $fieldCard->getCard();
        }

        $fieldCards = array_merge($fieldCards, $poolFieldCards);

        $winner->addDeck($fieldCards);

        $this->winner = $winner;


        echo $this->winner->getName() . "が勝ちました。".$this->winner->getName()."は".count($this->field)+count($this->poolField)."枚のカードをもらいました。".PHP_EOL;


        $this->field = [];
        $this->poolField = [];
    }

    private function isDraw(){
        if ($this->getCandidateWinnerPlayersCount() > 1)
            return true;
        else
            return false;
    }


    private function drawHandler(){
        $this->canSubmitPlayers = [];
        if ($this->isDraw())
        {
            echo "引き分けです。".PHP_EOL;
            $this->canSubmitPlayersHandler($this->candidateWinnerPlayers);
            $this->poolField = array_merge($this->poolField,$this->field);
        }
    }


    private function judge()
    {
        $this->setCandidateWinnerPlayers();
        $this->drawHandler();
        if(count($this->canSubmitPlayers) > 1){
            $this->field = [];
            return;
        }

        $this->winnerHandler();
        $this->gameEndHandler();
        $this->field = [];
    }


    private function sortPlayersByScore(){
        $players = $this->players;

        usort($players, function ($a, $b) {
            if(count($a->getDeck())+count($a->getHand()) === count($b->getDeck())+count($b->getHand())){
                return 0;
            }
            if(count($a->getDeck())+count($a->getHand()) > count($b->getDeck())+count($b->getHand())){
                return -1;
            }
            return 1;
        });

        $this->players = $players;

    }


    private function displayRanking(){
        foreach ($this->players as $key => $player) {
            $rank = $key + 1;
            echo $player->getName()."が".$rank."位";
            if($rank === count($this->players))
                echo "です。".PHP_EOL;
            else
                echo "、";
        }
    }

    public function play()
    {
        while(!$this->getIsEnd()){
            echo "戦争!".PHP_EOL;
            $this->submitCardToField();
            $this->displayFieldCard();
            $this->judge();
        }

        $this->sortPlayersByScore();
        $this->displayRanking();

        echo "戦争を終了します。".PHP_EOL;
    }
}

