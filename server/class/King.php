<?php

class King extends Figure{

    public $force = 900;
    
    public function __construct($color) {
        parent::__construct($color);
    }

    function isMove($xFrom, $yFrom, $xTo, $yTo, $prevY) {
        if (abs($xFrom - $xTo) < 2 && abs($yFrom - $yTo) < 2)
            return true;
        if ($xFrom == 0 && $yTo == 4 && $yFrom == 6)
            return true;
        if ($xFrom == 0 && $yTo == 4 && $yFrom == 2)
            return true;
        if ($xFrom == 7 && $yTo == 4 && $yFrom == 6)
            return true;
        if ($xFrom == 7 && $yTo == 4 && $yFrom == 2)
            return true;
        return false;
    }

    function isMoveAttack($obj, $x, $y){
        $obj = parent::isAttackBox($obj, $x, $y, $x + 1, $y + 1);
        $obj = parent::isAttackBox($obj, $x, $y, $x + 1, $y);
        $obj = parent::isAttackBox($obj, $x, $y, $x + 1, $y - 1);
        $obj = parent::isAttackBox($obj, $x, $y, $x - 1, $y + 1);
        $obj = parent::isAttackBox($obj, $x, $y, $x - 1, $y);
        $obj = parent::isAttackBox($obj, $x, $y, $x - 1, $y - 1);
        $obj = parent::isAttackBox($obj, $x, $y, $x, $y - 1);
        $obj = parent::isAttackBox($obj, $x, $y, $x, $y + 1);

        $obj = parent::isTurnBox($obj, $x, $y, $x + 1, $y + 1);
        $obj = parent::isTurnBox($obj, $x, $y, $x + 1, $y);
        $obj = parent::isTurnBox($obj, $x, $y, $x + 1, $y - 1);
        $obj = parent::isTurnBox($obj, $x, $y, $x - 1, $y + 1);
        $obj = parent::isTurnBox($obj, $x, $y, $x - 1, $y);
        $obj = parent::isTurnBox($obj, $x, $y, $x - 1, $y - 1);
        $obj = parent::isTurnBox($obj, $x, $y, $x, $y - 1);
        $obj = parent::isTurnBox($obj, $x, $y, $x, $y + 1);
        return $obj;
    }
}