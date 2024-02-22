<?php
namespace WarTrumpGame;



class Player
{
    private $name;
    private $hand = [];
    private $deck = [];

    private $id;


    public function __construct($name,$id)
    {
        $this->name = $name;
        $this->id = $id;
    }
    public function getHand()
    {
        return $this->hand;
    }
    public function setHand($card)
    {
        $this->hand[] = $card;
    }

    public function addHands($cards)
    {
        $this->hand = array_merge($this->hand, $cards);
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


    public function getId(){
        return $this->id;
    }


    public function getDeck()
    {
        return $this->deck;
    }

    public function shuffleDeck()
    {
        shuffle($this->deck);
    }

    public function addDeck($cards)
    {
        $this->deck = array_merge($this->deck, $cards);
    }

    public function popHand(){
        return array_shift($this->hand);
    }

    public function popDeck(){
        return array_shift($this->deck);
    }

    public function isPlayerHandEmptyAndDeckNotEmpty(){
        return count($this->hand) === 0 && count($this->deck) > 0;
    }

    public function chargeDeckToHand(){
        $this->shuffleDeck();
        $card = $this->popDeck();
        $this->setHand($card);
    }
}
