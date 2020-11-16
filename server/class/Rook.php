<?php

class Rook extends Figure {

    public $force = 50;
    
    public function __construct($color) {
        parent::__construct($color);
    }

    function isMove($xFrom, $yFrom, $xTo, $yTo, $prevY) {
        if ($xFrom == $xTo || $yFrom == $yTo)
            return true;
        return false;
    }

    function isMoveAttack($obj, $x, $y){
        for ($x1 = $x + 1; $x1 < 8; $x1++){
            $obj = parent::isAttackBox($obj, $x, $y, $x1, $y);
            $obj = parent::isTurnBox($obj, $x, $y, $x1, $y);
            if ($obj -> board[$x1][$y] != " "){
                break;
            }
        }
        for ($x1 = $x - 1; $x1 > -1; $x1--){
            $obj = parent::isAttackBox($obj, $x, $y, $x1, $y);
            $obj = parent::isTurnBox($obj, $x, $y, $x1, $y);
            if ($obj -> board[$x1][$y] != " "){
                break;
            }
        }
        for ($y1 = $y + 1; $y1 < 8; $y1++){
            $obj = parent::isAttackBox($obj, $x, $y, $x, $y1);
            $obj = parent::isTurnBox($obj, $x, $y, $x, $y1);
            if ($obj -> board[$x][$y1] != " "){
                break;
            }
        }
        for ($y1 = $y - 1; $y1 > -1; $y1--){
            $obj = parent::isAttackBox($obj, $x, $y, $x, $y1);
            $obj = parent::isTurnBox($obj, $x, $y, $x, $y1);
            if ($obj -> board[$x][$y1] != " "){
                break;
            }
        }
        return $obj;
    }
}