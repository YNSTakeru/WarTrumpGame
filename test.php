<?php

require_once('gameManager.php');
require_once('trump.php');
require_once('player.php');

use WarTrumpGame\GameManager;
use WarTrumpGame\Trump;
use WarTrumpGame\Player;

function testPlayerCount($playerCount=2, $expectedPlayerCount=2,$input=false) {

    $gameManager = new GameManager(playerCount:$playerCount,input:$input);
    $actualPlayerCount = $gameManager->getPlayerCount();

    assert($actualPlayerCount === $expectedPlayerCount, 'プレイヤーの人数が正しく設定されていません');
    echo 'プレイヤーの人数:'.$playerCount.'が正しく設定されています' . PHP_EOL;

    unset($gameManager);
}

testPlayerCount(playerCount:2,expectedPlayerCount:2);


function testLoopPlayerCount($minPlayerCount=2, $maxPlayerCount=5) {
    for($i = $minPlayerCount; $i <= $maxPlayerCount; $i++) {
        testPlayerCount(playerCount:$i, expectedPlayerCount:$i);
    }
}

testLoopPlayerCount(minPlayerCount:2, maxPlayerCount:5);


function testPlayerName($playerCount=2,$input=false) {
    if ($input)
    return;

    $gameManager = new GameManager(playerCount:$playerCount,input:$input);
    $players = $gameManager->getPlayers();

    for($i = 0; $i < $playerCount; $i++) {
        $actualPlayerName = $players[$i]->getName();
        assert($actualPlayerName === 'プレイヤー' . ($i + 1), 'プレイヤーの名前が正しく設定されていません');
    }
    echo 'プレイヤーの名前が正しく設定されています' . PHP_EOL;

    unset($gameManager);
}

testPlayerName(playerCount:2,input:false);

function testTrumpCount($playerCount=2,$input=false) {
    $gameManager = new GameManager(playerCount:$playerCount,input:$input);
    $trumps = $gameManager->getTrumps();

    $actualTrumpsCount = count($trumps);
    $expectedTrumpsCount = 53;

    assert($actualTrumpsCount === $expectedTrumpsCount, 'トランプの枚数が正しくありません');
    echo 'トランプの枚数が正しく設定されています' . PHP_EOL;

    unset($gameManager);
}

testTrumpCount(playerCount:2,input:false);

function testTrumpMark($playerCount=2,$input=false) {
    $gameManager = new GameManager(playerCount:$playerCount,input:$input);
    $trumps = $gameManager->getTrumps();

    foreach($trumps as $trump) {
        $actualMark = $trump->getMark();
        assert(in_array($actualMark, Trump::MARKS), 'トランプのマークが正しく設定されていません');
    }
    echo 'トランプのマークが正しく設定されています' . PHP_EOL;

    unset($gameManager);
}

testTrumpMark(playerCount:2,input:false);

function testShuffle($playerCount=2,$input=false) {
    $gameManager = new GameManager(playerCount:$playerCount,input:$input);
    $trumps = $gameManager->getTrumps();

    $trumpCount = [];
    foreach($trumps as $trump) {
        $mark = $trump->getMark();
        $number = $trump->getNumber();
        $trumpCount[$mark][$number] = 0;
    }

    foreach($trumps as $trump) {
        $mark = $trump->getMark();
        if($mark === "joker"){
            $mark = "joker";
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

testShuffle(playerCount:2,input:false);

function testDeal($playerCount=2,$input=false) {
    $gameManager = new GameManager(playerCount:$playerCount,input:$input);
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

testDeal();


function testLoopDeal(){
    for($i = 2; $i <= 5; $i++) {
        testDeal(playerCount:$i);
    }
}

testLoopDeal();

function testDealOverlap($playerCount=2,$input=false) {
    $gameManager = new GameManager(playerCount:$playerCount,input:$input);
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

testDealOverlap();


function testLoopDealOverlap(){
    for($i = 2; $i <= 5; $i++) {
        testDealOverlap(playerCount:$i);
    }
}

testLoopDealOverlap();
