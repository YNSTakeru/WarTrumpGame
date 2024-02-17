<?php
namespace WarTrumpGame;



class Player
{
    private $name;
    private $hand = [];
    private $score = 0;

    private $deck = [];


    public function __construct($name)
    {
        $this->name = $name;
    }
    public function getHand()
    {
        return $this->hand;
    }
    public function setHand($card)
    {
        $this->hand[] = $card;
    }
    public function showHand()
    {
        $hand = '';
        foreach ($this->hand as $card) {
            $hand .= $card->getCardInfo() . ' ';
        }
        return $hand;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getScore()
    {
        return $this->score;
    }
    public function setScore($score)
    {
        $this->score = $score;
    }

    public function getDeck()
    {
        return $this->deck;
    }

    public function setDeck($card)
    {
        $this->deck[] = $card;
    }
}
