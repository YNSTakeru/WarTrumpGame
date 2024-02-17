<?php


namespace WarTrumpGame;
class Trump
{
    const MARKS = ['spade', 'heart', 'diamond', 'club',"joker"];
    const NUMBERS = [1,2,3,4,5,6,7,8,9,10,11,12,13];
    private $mark;
    private $number;
    public function __construct($mark=0, $number=0)
    {
        $this->mark = $mark;
        $this->number = $number;
    }

    public function getMark()
    {
        return $this->mark;
    }
    public function getNumber()
    {
        return $this->number;
    }
    public function getCardInfo()
    {
        $mark = $this->mark;
        if($mark !== "joker")
            return $mark . 'の' . $this->number;

        return 'ジョーカー';
    }
}
