<?php

class Knight extends Figure {

    public $force = 30;
    
    public function __construct($color) {
        parent::__construct($color);
    }

    function isMove($xFrom, $yFrom, $xTo, $yTo, $prevY) {
        if ((abs($xFrom - $xTo) == 1 && abs($yFrom - $yTo) == 2)
            ||(abs($xFrom - $xTo) == 2 && abs($yFrom - $yTo) == 1))
            return true;
        return false;
    }
    
    function isMoveAttack($obj, $x, $y){
        $obj = parent::isAttackBox($obj, $x, $y, $x + 1, $y + 2);
        $obj = parent::isAttackBox($obj, $x, $y, $x + 1, $y - 2);
        $obj = parent::isAttackBox($obj, $x, $y, $x + 2, $y + 1);
        $obj = parent::isAttackBox($obj, $x, $y, $x + 2, $y - 1);
        $obj = parent::isAttackBox($obj, $x, $y, $x - 1, $y + 2);
        $obj = parent::isAttackBox($obj, $x, $y, $x - 1, $y - 2);
        $obj = parent::isAttackBox($obj, $x, $y, $x - 2, $y + 1);
        $obj = parent::isAttackBox($obj, $x, $y, $x - 2, $y - 1);

        $obj = parent::isTurnBox($obj, $x, $y, $x + 1, $y + 2);
        $obj = parent::isTurnBox($obj, $x, $y, $x + 1, $y - 2);
        $obj = parent::isTurnBox($obj, $x, $y, $x + 2, $y + 1);
        $obj = parent::isTurnBox($obj, $x, $y, $x + 2, $y - 1);
        $obj = parent::isTurnBox($obj, $x, $y, $x - 1, $y + 2);
        $obj = parent::isTurnBox($obj, $x, $y, $x - 1, $y - 2);
        $obj = parent::isTurnBox($obj, $x, $y, $x - 2, $y + 1);
        $obj = parent::isTurnBox($obj, $x, $y, $x - 2, $y - 1);

        return $obj;
    }
}