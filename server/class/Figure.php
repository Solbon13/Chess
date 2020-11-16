<?php

abstract class Figure {
    var $color;
	
	function __construct($color){
        $this->color = $color;
        $this->chess = new Chess;
    }
    
    abstract function isMove($xFrom, $yFrom, $xTo, $yTo, $prevY);

    //может возвращать тру или фалш, а запись производить в инф атак откуда вызвали
    //чтоб организовать ходы
    public function isAttackBox($obj, $xFrom, $yFrom, $xTo, $yTo){
        //важны ли количество атак без уточнения фигур?
        if ($xTo > -1 && $yTo > -1 && $xTo < 8 && $yTo < 8)
        //для защиты своих фигур необходима проверка
        //if ($this->chess->getColorFigureBox($obj -> board[$xTo][$yTo]) != $this->chess->getColorFigureBox($obj -> board[$xFrom][$yFrom]))
        {
            if ($this->chess->getColorFigureBox($obj -> board[$xFrom][$yFrom]) == "down"){
                $obj -> infDownAttack[$xTo][$yTo][] = $obj -> board[$xFrom][$yFrom];
                //if ($obj -> inf[$xFrom][$yFrom] == " ")
                  //  $obj -> inf[$xFrom][$yFrom] = "is_move";
            }
            else{
                $obj -> infUpAttack[$xTo][$yTo][] = $obj -> board[$xFrom][$yFrom];
                //if ($obj -> inf[$xFrom][$yFrom] == " ")
                  //  $obj -> inf[$xFrom][$yFrom] = "is_move";
            }
        }
        return $obj;
    }

    public function isTurnBox($obj, $xFrom, $yFrom, $xTo, $yTo){
        //важны ли количество атак без уточнения фигур?
        if ($xTo > -1 && $yTo > -1 && $xTo < 8 && $yTo < 8)
        if ($this->chess->getColorFigureBox($obj -> board[$xTo][$yTo]) != $this->chess->getColorFigureBox($obj -> board[$xFrom][$yFrom])){
            if ($this->chess->getColorFigureBox($obj -> board[$xFrom][$yFrom]) == "down"){
                $moveFigure -> xTo = $xFrom;
                $moveFigure -> yTo = $yFrom;
                $obj -> infDownTurn[$xTo][$yTo][] = $moveFigure;
            }
            else{
                $moveFigure -> xTo = $xFrom;
                $moveFigure -> yTo = $yFrom;
                $obj -> infUpTurn[$xTo][$yTo][] = $moveFigure;
            }
        }
        return $obj;
    }

}