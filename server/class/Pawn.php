<?php

class Pawn extends Figure {

    public $force = 10;

    public function __construct($color, $attack) {
        parent::__construct($color);
        $this->attack = $attack;
        $this->chess = new Chess;
    }

    function isMove($xTo, $yTo, $xFrom, $yFrom, $prevY) {
        if ($this->color == "down"){
            if ((($xTo - $xFrom) == 2 && $xFrom == 1 && $yTo == $yFrom)
            ||(($xTo - $xFrom) == 1 && $yTo == $yFrom)
            ||(($xTo - $xFrom) == 1 && abs($yTo - $yFrom) == 1 && $this->attack == "up")
            //взятие на проходе.
            ||($xFrom == 4 && $yTo == $prevY)
            )
                return true;
        }
        
        if ($this->color == "up")
        if ((($xTo - $xFrom) == -2 && $xFrom == 6 && $yTo == $yFrom)
            ||(($xTo - $xFrom) == -1 && $yTo == $yFrom)
            ||(($xTo - $xFrom) == -1 && abs($yTo - $yFrom) == 1 && $this->attack == "down")
            ||($xFrom == 3 && $yTo == $prevY)
            )
                return true;
        return false;
    }

    function isMoveAttack($obj, $x, $y){
        if ($this->chess->getColorFigureBox($obj -> board[$x][$y]) == "down"){
            $obj = parent::isAttackBox($obj, $x, $y, $x + 1, $y + 1);
            $obj = parent::isAttackBox($obj, $x, $y, $x + 1, $y - 1);

            if ($this->chess->getColorFigureBox($obj -> board[$x + 1][$y - 1]) == "up")
                $obj = parent::isTurnBox($obj, $x, $y, $x + 1, $y - 1);
            if ($this->chess->getColorFigureBox($obj -> board[$x + 1][$y + 1]) == "up")
                $obj = parent::isTurnBox($obj, $x, $y, $x + 1, $y + 1);
            if ($obj -> board[$x + 1][$y] == " ")
                $obj = parent::isTurnBox($obj, $x, $y, $x + 1, $y);
            if ($x == 1){
                if ($obj -> board[$x + 2][$y] == " ")
                    $obj = parent::isTurnBox($obj, $x, $y, $x + 2, $y);
            }
        }else{
            $obj = parent::isAttackBox($obj, $x, $y, $x - 1, $y + 1);
            $obj = parent::isAttackBox($obj, $x, $y, $x - 1, $y - 1);

            if ($this->chess->getColorFigureBox($obj -> board[$x - 1][$y + 1]) == "down")
                $obj = parent::isTurnBox($obj, $x, $y, $x - 1, $y + 1);
            if ($this->chess->getColorFigureBox($obj -> board[$x - 1][$y - 1]) == "down")
                $obj = parent::isTurnBox($obj, $x, $y, $x - 1, $y - 1);
            if ($obj -> board[$x - 1][$y] == " ")
                $obj = parent::isTurnBox($obj, $x, $y, $x - 1, $y);
            if ($x == 6){
                if ($obj -> board[$x - 2][$y] == " ")
                    $obj = parent::isTurnBox($obj, $x, $y, $x - 2, $y);
            }
        }
        return $obj;
    }
}