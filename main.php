<?php
require_once('trump.php');
require_once('player.php');
require_once('fieldCard.php');
require_once('gameManager.php');

use WarTrumpGame\GameManager;

$gameManager = new GameManager();

$gameManager->play();
