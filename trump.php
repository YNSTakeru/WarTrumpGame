<?php


namespace WarTrumpGame;
class Trump
{
    const MARKS = ['スペード', 'ハート', 'ダイヤ', 'クラブ',"ジョーカー"];
    const NUMBERS = [1,2,3,4,5,6,7,8,9,10,11,12,13];

    const SPADE_A_NUMBER = 13;
    private $mark;
    private $number;
    private $displayCardNumbers = ["2","3","4","5","6","7","8","9","10","J","Q","K","A"];


    public function __construct($mark=0, $number=999999)
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
       ;
        if($mark !== "ジョーカー")
            return $mark . 'の' .  $this->displayCardNumbers[$this->number - 1];

        return 'ジョーカー';
    }
}
