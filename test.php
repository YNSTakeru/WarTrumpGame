<?php

require_once('gameManager.php');
require_once('trump.php');
require_once('player.php');

use WarTrumpGame\GameManager;
use WarTrumpGame\Trump;


function testPlayerCount($playerCount=2, $expectedPlayerCount=2,$isInput=false) {

    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $actualPlayerCount = $gameManager->getPlayerCount();

    assert($actualPlayerCount === $expectedPlayerCount, 'プレイヤーの人数が正しく設定されていません');
    echo 'プレイヤーの人数:'.$playerCount.'が正しく設定されています' . PHP_EOL;

    unset($gameManager);
}

// testPlayerCount(playerCount:2,expectedPlayerCount:2);


function testLoopPlayerCount($minPlayerCount=2, $maxPlayerCount=5) {
    for($i = $minPlayerCount; $i <= $maxPlayerCount; $i++) {
        testPlayerCount(playerCount:$i, expectedPlayerCount:$i);
    }
}

// testLoopPlayerCount(minPlayerCount:2, maxPlayerCount:5);


function testPlayerName($playerCount=2,$isInput=false) {
    if ($isInput)
    return;

    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $players = $gameManager->getPlayers();

    for($i = 0; $i < $playerCount; $i++) {
        $actualPlayerName = $players[$i]->getName();
        assert($actualPlayerName === 'プレイヤー' . ($i + 1), 'プレイヤーの名前が正しく設定されていません');
    }
    echo 'プレイヤーの名前が正しく設定されています' . PHP_EOL;

    unset($gameManager);
}

// testPlayerName(playerCount:2,isInput:false);

function testPlayerId($playerCount=2,$isInput=false) {
    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $players = $gameManager->getPlayers();

    for($i = 0; $i < $playerCount; $i++) {
        $actualPlayerId = $players[$i]->getId();
        assert($actualPlayerId === $i + 1, 'プレイヤーのidが正しく設定されていません');
    }
    echo 'プレイヤーのidが正しく設定されています' . PHP_EOL;

    unset($gameManager);
}

// testPlayerId();

function testLoopPlayerId(){
    for($i = 2; $i <= 5; $i++) {
        testPlayerId(playerCount:$i);
    }
}

// testLoopPlayerId();

function testTrumpCount($playerCount=2,$isInput=false) {
    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $trumps = $gameManager->getTrumps();

    $actualTrumpsCount = count($trumps);
    $expectedTrumpsCount = 53;

    assert($actualTrumpsCount === $expectedTrumpsCount, 'トランプの枚数が正しくありません');
    echo 'トランプの枚数が正しく設定されています' . PHP_EOL;

    unset($gameManager);
}

// testTrumpCount(playerCount:2,isInput:false);

function testTrumpMark($playerCount=2,$isInput=false) {
    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $trumps = $gameManager->getTrumps();

    foreach($trumps as $trump) {
        $actualMark = $trump->getMark();
        assert(in_array($actualMark, Trump::MARKS), 'トランプのマークが正しく設定されていません');
    }
    echo 'トランプのマークが正しく設定されています' . PHP_EOL;

    unset($gameManager);
}

// testTrumpMark(playerCount:2,isInput:false);

function testShuffle($playerCount=2,$isInput=false) {
    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $trumps = $gameManager->getTrumps();

    $trumpCount = [];
    foreach($trumps as $trump) {
        $mark = $trump->getMark();
        $number = $trump->getNumber();
        $trumpCount[$mark][$number] = 0;
    }

    foreach($trumps as $trump) {
        $mark = $trump->getMark();
        if($mark === "ジョーカー"){
            $mark = "ジョーカー";
        }
        $number = $trump->getNumber();
        $trumpCount[$mark][$number]++;
    }

    foreach($trumpCount as $mark => $numbers) {
        foreach($numbers as $number => $count) {
            assert($count === 1, 'トランプが正しくシャッフルされていません');
        }
    }

    echo 'トランプが正しくシャッフルされています' . PHP_EOL;

    unset($gameManager);
}

// testShuffle(playerCount:2,isInput:false);

function testDeal($playerCount=2,$isInput=false) {
    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $players = $gameManager->getPlayers();
    $trumps = $gameManager->getTrumps();
    $trumpCount = count($trumps);
    $expectedHandCount = (int)($trumpCount / $playerCount);
    $shouldAddCardPlayerIndex = $trumpCount % $playerCount -1;

    for($i = 0; $i < $playerCount; $i++) {
        $player = $players[$i];
        $actualHand = $player->getHand();
        $actualHandCount = count($actualHand);
        if($i <= $shouldAddCardPlayerIndex) {
            assert($actualHandCount === $expectedHandCount + 1, 'プレイヤーにトランプに配られたトランプの枚数が正しくないです');
        } else {
            assert($actualHandCount === $expectedHandCount, 'プレイヤーにトランプに配られたトランプの枚数が正しくないです');
        }
    }

    echo 'プレイヤーに配れたトランプの枚数は正しいです' . PHP_EOL;

    unset($gameManager);
}

// testDeal();


function testLoopDeal(){
    for($i = 2; $i <= 5; $i++) {
        testDeal(playerCount:$i);
    }
}

// testLoopDeal();

function testDealOverlap($playerCount=2,$isInput=false) {
    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $players = $gameManager->getPlayers();

    $allHand = [];
    for($i = 0; $i < $playerCount; $i++) {
        $player = $players[$i];
        $actualHand = $player->getHand();
        $allHand = array_merge($allHand, $actualHand);
    }


    $mark = [];
    foreach($allHand as $trump){
        $mark[$trump->getMark()][] = $trump->getNumber();
    }

    foreach($mark as $value){

        $uniqueHandCount = count(array_unique($value));
        $actualHandCount = count($value);

        assert($actualHandCount === $uniqueHandCount, '他のプレイヤーに配られたトランプと重複しています');
    }

    echo '他のプレイヤーに配られたトランプと重複していません' . PHP_EOL;

    unset($gameManager);
}

// testDealOverlap();


function testLoopDealOverlap(){
    for($i = 2; $i <= 5; $i++) {
        testDealOverlap(playerCount:$i);
    }
}

// testLoopDealOverlap();


function testSubmitCardNumOnField($playerCount=2,$isInput=false){
    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $gameManager->play();
    $fieldCount = count($gameManager->getField());

    assert($fieldCount === $gameManager->getPlayerCount(), "場に正しいカードの枚数が出されていません。");

    echo "場に正しいカードの枚数が出されています。" . PHP_EOL;

    unset($gameManager);
}

// testSubmitCardNumOnField();


function testLoopSubmitCardNumOnField(){
    for($i = 2; $i <= 5; $i++) {
        testSubmitCardNumOnField(playerCount:$i);
    }
}

// testLoopSubmitCardNumOnField();


function testSubmitCardBeforeAfterHand()
{
    $gameManager = new GameManager(playerCount: 2, isInput: false);
    $players = $gameManager->getPlayers();
    $player = $players[0];
    $beforeHand = $player->getHand();
    $gameManager->play();
    $afterHand = $player->getHand();
    $beforeHandCount = count($beforeHand);
    $afterHandCount = count($afterHand);
    $field = $gameManager->getField();
    $submitCard = $field[0]->getCard();
    $isFlag = false;


    foreach ($beforeHand as $card) {
        if ($card->getMark() === $submitCard->getMark() && $card->getNumber() === $submitCard->getNumber()) {
            $isFlag = true;
            break;
        }
    }


    assert($beforeHandCount === $afterHandCount + 1, "カードが正しく出されていません。");
    assert($isFlag, "出したカードが手札から消えていません。");

    echo "カードが正しく出されています。" . PHP_EOL;
    echo "出したカードが手札から消えています。" . PHP_EOL;

    unset($gameManager);
}

// testSubmitCardBeforeAfterHand();

function testMaxCardNum($playerCount=2,$isInput=false){
    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $gameManager->play();
    $field = $gameManager->getField();

    $actuallyMaxNumber = $gameManager->getMaxNumberOnField();
    $expectedMaxNumber = 0;

    foreach($field as $fieldCard){
        $card = $fieldCard->getCard();
        if($expectedMaxNumber < $card->getNumber() ){
            $expectedMaxNumber = $card->getNumber();
        }
    }

    assert($actuallyMaxNumber === $expectedMaxNumber, "一番大きい値が正しく取得できていません。");

    echo "一番大きい値が正しく取得できています。" . PHP_EOL;

    unset($gameManager);
}

// testMaxCardNum();

function testLoopMaxCardNum(){
    for($i = 2; $i <= 5; $i++) {
        testMaxCardNum(playerCount:$i);
    }
}

// testLoopMaxCardNum();

function testCandidateWinnerPlayersCountWhenMaxNumberIsOneOnField($playerCount=2,$isInput=false){

    $isMaxNumberOneOnField = false;

    while(!$isMaxNumberOneOnField){
        $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
        $gameManager->play();
        $field = $gameManager->getField();
        $maxNumber = $gameManager->getMaxNumberOnField();
        $actualCandidateWinnerPlayersCount = $gameManager->getCandidateWinnerPlayersCount();
        $expectedCandidateWinnerPlayersCount = 0;
        $maxNumberCount = 0;

        foreach($field as $fieldCard){
            $card = $fieldCard->getCard();
            if($maxNumber === $card->getNumber()){
                $expectedCandidateWinnerPlayersCount++;
                $maxNumberCount++;
            }
        }

        if ($maxNumberCount > 1)
            continue;

        $isMaxNumberOneOnField = true;

        assert($actualCandidateWinnerPlayersCount === $expectedCandidateWinnerPlayersCount, "一番大きい値が一枚の場合、勝利候補者の数が正しくありません。");

        echo "一番大きい値が一枚の場合、勝利候補者の数が正しいです。" . PHP_EOL;

        unset($gameManager);
    }
}


// testCandidateWinnerPlayersCountWhenMaxNumberIsOneOnField();

function testLoopCandidateWinnerPlayersCountWhenMaxNumberIsOneOnField(){
    for($i = 2; $i <= 5; $i++) {
        testCandidateWinnerPlayersCountWhenMaxNumberIsOneOnField(playerCount:$i);
    }
}

// testLoopCandidateWinnerPlayersCountWhenMaxNumberIsOneOnField();

function testPlayerIsCandidateWinnerPlayers($playerCount=2,$isInput=false){

    $isOneMaxNumberOnField = false;
    while(!$isOneMaxNumberOnField){
        $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
        $gameManager->play();
        $field = $gameManager->getField();
        $candidateWinnerPlayers = $gameManager->getCandidateWinnerPlayers();
        $isFlag = false;

        foreach($field as $fieldCard){
            $player = $fieldCard->getPlayer();
            if(in_array($player, $candidateWinnerPlayers)){
                $isFlag = true;
                break;
            }
        }

        if (count($candidateWinnerPlayers) > 1)
            continue;

        $isOneMaxNumberOnField = true;

        assert($isFlag, "fieldに出たplayerが、candidateWinnerPlayersに含まれていません。");

        echo "一番大きい数字が一枚の時、fieldに出たplayerが、candidateWinnerPlayersに含まれています。" . PHP_EOL;

        unset($gameManager);
    }
}

// testPlayerIsCandidateWinnerPlayers();


function testLoopPlayerIsCandidateWinnerPlayers(){
    for($i = 2; $i <= 5; $i++) {
        testPlayerIsCandidateWinnerPlayers(playerCount:$i);
    }
}

// testPlayerIsCandidateWinnerPlayers();

function testCandidateWinnerPlayersCountWhenMaxNumberIsTwoOnField($playerCount=2,$isInput=false){

    $maxFieldCardCount = $playerCount > 4 ? 4 : $playerCount;

    for($i = 2; $i <= $maxFieldCardCount; $i++){
        $isMaxNumberTwoOnField = false;

        while(!$isMaxNumberTwoOnField){
            $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
            $gameManager->play();
            $field = $gameManager->getField();
            $maxNumber = $gameManager->getMaxNumberOnField();
            $actualCandidateWinnerPlayersCount = $gameManager->getCandidateWinnerPlayersCount();
            $expectedCandidateWinnerPlayersCount = 0;
            $maxNumberCount = 0;
            $isSpadeAFlag = false;

            foreach($field as $fieldCard){
                $card = $fieldCard->getCard();
                if($maxNumber === $card->getNumber()){
                    $expectedCandidateWinnerPlayersCount++;
                    $maxNumberCount++;
                    if($card->getMark() === "スペード" && $card->getNumber() === TRUMP::SPADE_A_NUMBER)
                       $isSpadeAFlag = true;
                }
            }

            if ($maxNumberCount !== $maxFieldCardCount || $isSpadeAFlag)
                continue;

            $isMaxNumberTwoOnField = true;

            assert($actualCandidateWinnerPlayersCount === $expectedCandidateWinnerPlayersCount, "スペードのAを含まず、一番大きい値が".$maxFieldCardCount."枚の場合、勝利候補者の数が正しくありません。");

            echo "スペードのAを含まず、一番大きい値が".$maxFieldCardCount."枚の場合、勝利候補者の数が正しいです。" . PHP_EOL;

            unset($gameManager);
        }
    }

}

// testCandidateWinnerPlayersCountWhenMaxNumberIsTwoOnField();

function testLoopCandidateWinnerPlayersCountWhenMaxNumberIsMoreThanTwoOnField(){
    for($i = 2; $i <= 5; $i++) {
        testCandidateWinnerPlayersCountWhenMaxNumberIsTwoOnField(playerCount:$i);
    }
}

// testLoopCandidateWinnerPlayersCountWhenMaxNumberIsMoreThanTwoOnField();


function testCandidateWinnerPlayersCountWhenMaxNumberIsTwoAndSpadeAOnField($playerCount=2,$isInput=false){

    $maxFieldCardCount = $playerCount > 4 ? 4 : $playerCount;

    for($i = 2; $i <= $maxFieldCardCount; $i++){
        $isMaxNumberTwoOnField = false;

        while(!$isMaxNumberTwoOnField){
            $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
            $gameManager->play();
            $field = $gameManager->getField();
            $maxNumber = $gameManager->getMaxNumberOnField();
            $actualCandidateWinnerPlayersCount = $gameManager->getCandidateWinnerPlayersCount();
            $expectedCandidateWinnerPlayersCount = 0;
            $maxNumberCount = 0;
            $isSpadeAFlag = false;

            foreach($field as $fieldCard){
                $card = $fieldCard->getCard();
                if($maxNumber === $card->getNumber()){
                    $maxNumberCount++;
                    if($card->getMark() === "スペード" && $card->getNumber() === TRUMP::SPADE_A_NUMBER){
                        $isSpadeAFlag = true;
                        $expectedCandidateWinnerPlayersCount = 1;
                        break;
                    }
                }
            }

            if ($maxNumberCount !== $maxFieldCardCount || !$isSpadeAFlag)
                continue;

            $isMaxNumberTwoOnField = true;

            assert($actualCandidateWinnerPlayersCount === $expectedCandidateWinnerPlayersCount, "スペードのAを含み、一番大きい値が".$maxFieldCardCount."枚の場合、勝利候補者の数".$actualCandidateWinnerPlayersCount."が正しくありません。");

            echo "スペードのAを含み、一番大きい値が".$maxFieldCardCount."枚の場合、勝利候補者の数は".$actualCandidateWinnerPlayersCount."が正しいです。" . PHP_EOL;

            unset($gameManager);
        }
    }

}

// testCandidateWinnerPlayersCountWhenMaxNumberIsTwoAndSpadeAOnField();

function testLoopCandidateWinnerPlayersCountWhenMaxNumberIsTwoAndSpadeAOnField(){
    for($i = 2; $i <= 5; $i++) {
        testCandidateWinnerPlayersCountWhenMaxNumberIsTwoAndSpadeAOnField(playerCount:$i);
    }
}

// 実行に数分かかる (1/13)^4 を引かなければならないため
// testLoopCandidateWinnerPlayersCountWhenMaxNumberIsTwoAndSpadeAOnField();


function testCandidateWinnerPlayersCountWhenJokerOnField($playerCount=2,$isInput=false){

    $maxFieldCardCount = $playerCount > 4 ? 4 : $playerCount;

    for($i = 2; $i <= $maxFieldCardCount; $i++){
        $isJokerOnField = false;

        while(!$isJokerOnField){
            $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
            $gameManager->play();
            $field = $gameManager->getField();
            $actualCandidateWinnerPlayersCount = $gameManager->getCandidateWinnerPlayersCount();
            $expectedCandidateWinnerPlayersCount = 0;
            $isJoker = false;

            foreach($field as $fieldCard){
                $card = $fieldCard->getCard();
                if($card->getMark() === "ジョーカー"){
                    $isJoker = true;
                    $expectedCandidateWinnerPlayersCount = 1;
                    break;
                }
            }

            if (!$isJoker)
                continue;

            $isJokerOnField = true;

            assert($actualCandidateWinnerPlayersCount === $expectedCandidateWinnerPlayersCount, "ジョーカーが場に出た場合、勝利候補者の数が正しくありません。");

            echo "ジョーカーが場に出た場合、勝利候補者の数が正しいです。" . PHP_EOL;

            unset($gameManager);
        }
    }

}

// testCandidateWinnerPlayersCountWhenJokerOnField();

function testLoopCandidateWinnerPlayersCountWhenJokerOnField(){
    for($i = 2; $i <= 5; $i++) {
        testCandidateWinnerPlayersCountWhenJokerOnField(playerCount:$i);
    }
}

// testLoopCandidateWinnerPlayersCountWhenJokerOnField();


function testFinalCandidateWinnerPlayersCount($playerCount=2,$isInput=false){

    $gameManager = new GameManager(playerCount:$playerCount,isInput:$isInput);
    $gameManager->play();

    $field = $gameManager->getField();
    $actualCandidateWinnerPlayersCount = $gameManager->getCandidateWinnerPlayersCount();

    unset($gameManager);

    assert($actualCandidateWinnerPlayersCount === 1, "実際の勝利候補者は最終的に1人になりません。");

    echo "必ず実際の勝利候補者は最終的に".$actualCandidateWinnerPlayersCount."になります。" . PHP_EOL;
}

// testFinalCandidateWinnerPlayersCount();

function getTrumpsDraw(){
    $trumps = [];
    for($i = 1; $i <= 13; $i++){
        $trumps[] = new Trump(mark:"ハート",number:$i);
        $trumps[] = new Trump(mark:"ダイヤ",number:$i);
        $trumps[] = new Trump(mark:"クラブ",number:$i);
        $trumps[] = new Trump(mark:"スペード",number:$i);
    }

    $trumps[] = new Trump(mark:"ジョーカー");

    return $trumps;
}

function testAllTrumpPattern($playerCount=2){
    $trumps = getTrumpsDraw();

    $gameManager = new GameManager(playerCount:$playerCount,isInput:false,trumps:$trumps);
    $gameManager->play();
}

testAllTrumpPattern(playerCount:5);
