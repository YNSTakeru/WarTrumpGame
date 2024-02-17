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
}

testPlayerName(playerCount:2,input:false);

function testTrumpCount($playerCount=2,$input=false) {
    $gameManager = new GameManager(playerCount:$playerCount,input:$input);
    $trumps = $gameManager->getTrumps();

    $actualTrumpsCount = count($trumps);
    $expectedTrumpsCount = 53;

    assert($actualTrumpsCount === $expectedTrumpsCount, 'トランプの枚数が正しくありません');
    echo 'トランプの枚数が正しく設定されています' . PHP_EOL;
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
}

testTrumpMark(playerCount:2,input:false);

function testTrumpNumber($playerCount=2,$input=false) {
    $gameManager = new GameManager(playerCount:$playerCount,input:$input);
    $trumps = $gameManager->getTrumps();

    $expectedNumberCount = 13;

    $expectedCardNumber = 1;
    $EXPECTED_MARKS = ['spade', 'heart', 'diamond', 'club',"joker"];
    $expectedMarkIndex = 0;


    foreach($trumps as $trump) {
        $actualMark = $trump->getMark();
        if($actualMark === "joker"){
            assert($trump->getMark() === $EXPECTED_MARKS[$expectedMarkIndex], 'ジョーカーが正しく設定されていません');
            continue;
        }
        $actualNumber = $trump->getNumber();
        assert($actualNumber === $expectedCardNumber, 'トランプの数字が正しく設定されていません');
        $expectedCardNumber = $expectedCardNumber % $expectedNumberCount + 1;

        assert($actualMark === $EXPECTED_MARKS[$expectedMarkIndex], 'トランプのマークが正しく設定されていません');
        if($expectedCardNumber === 1) $expectedMarkIndex++;
    }

    echo 'トランプが正しく設定されています' . PHP_EOL;
}

testTrumpNumber(playerCount:2,input:false);
