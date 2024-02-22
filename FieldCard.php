<?php

namespace WarTrumpGame;

class FieldCard
{
    private $player;
    private $card;

    public function __construct($player) {
        $this->card = $player->popHand();
        $this->player = $player;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function getCard()
    {
        return $this->card;
    }
}
