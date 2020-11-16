<?php
    include 'class/Board.php';

    
class AIChess {
    
    function __construct(){
        $this->chess = new Chess;
    }
    
    function getInf($obj){
        $obj -> infUpAttack = array(
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array())
        );
        $obj -> infDownAttack = array(
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array())
        );
        $obj -> infUpTurn = array(
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array())
        );
        $obj -> infDownTurn = array(
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array()),
            array(array(), array(), array(), array(), array(), array(), array(), array())
        );
        //перебор доски. проверить клетка не пуста. и куда сможет сходить с данной клетки фигура
        for ($x = 0; $x < 8; $x++){
            for ($y = 0; $y < 8; $y++){
                if ($obj -> board[$x][$y] != " "){
                    $figure = $this->chess->getFigure($obj -> board[$x][$y], $capture);
                    $obj = $figure->isMoveAttack($obj, $x, $y);
                }                   
            }
        }
        return $obj;
    }

    function attackBox(){

    }

    function ratingPosition($obj){
        $rateBoardUp = 0;
        $rateBoardDown = 0;
        $ratePosition = new Board;
        $maxRateMasDown = array();
        $maxRateDown = -900;
        $maxRateMasUp = array();
        $maxRateUp = -900;
                
        for ($x = 0; $x < 8; $x++){
            for ($y = 0; $y < 8; $y++){
                if ($this->chess->getColorFigureBox($obj -> board[$x][$y]) == "up"){
                    $figure = $this->chess->getFigure($obj -> board[$x][$y], $capture);
                    if ($figure instanceof Bishop)
                        $rateBoardUp = $rateBoardUp + $ratePosition->bishopEvalDown[$x][$y];
                    if ($figure instanceof King)
                        $rateBoardUp = $rateBoardUp + $ratePosition->rookEvalDown[$x][$y];
                    if ($figure instanceof Knight)
                        $rateBoardUp = $rateBoardUp + $ratePosition->knightEval[$x][$y];
                    if ($figure instanceof Pawn)
                        $rateBoardUp = $rateBoardUp + $ratePosition->pawnEvalDown[$x][$y];
                    if ($figure instanceof Queent)
                        $rateBoardUp = $rateBoardUp + $ratePosition->queenEval[$x][$y];
                    if ($figure instanceof Rook)
                        $rateBoardUp = $rateBoardUp + $ratePosition->rookEvalDown[$x][$y];
                    $rateBoardUp = $rateBoardUp + $figure->force;
                }
                if ($this->chess->getColorFigureBox($obj -> board[$x][$y]) == "down"){
                    $figure = $this->chess->getFigure($obj -> board[$x][$y], $capture);
                    if ($figure instanceof Bishop){
                        $bishopEval = array_reverse($ratePosition->bishopEvalDown);
                        $rateBoardDown = $rateBoardDown + $bishopEval[$x][$y];
                    }
                    if ($figure instanceof King){
                        $kingEval = array_reverse($ratePosition->rookEvalDown);
                        $rateBoardDown = $rateBoardDown + $kingEval[$x][$y];
                    }
                    if ($figure instanceof Knight){
                        $knightEval = array_reverse($ratePosition->knightEval);
                        $rateBoardDown = $rateBoardDown + $knightEval[$x][$y];
                    }
                    if ($figure instanceof Pawn){
                        $pawnEval = array_reverse($ratePosition->pawnEvalDown);
                        $rateBoardDown = $rateBoardDown + $pawnEval[$x][$y];
                    }
                    if ($figure instanceof Queent){
                        $queenEval = array_reverse($ratePosition->queenEval);
                        $rateBoardDown = $rateBoardDown + $queenEval[$x][$y];
                    }
                    if ($figure instanceof Rook){
                        $rookEval = array_reverse($ratePosition->rookEvalDown);
                        $rateBoardDown = $rateBoardDown + $rookEval[$x][$y];
                    }
                    $rateBoardDown = $rateBoardDown + $figure->force;
                }

                if (count($obj -> infDownTurn[$x][$y]) > 0){
                    foreach($obj -> infDownTurn[$x][$y] as $key => $value) { // $key - индекс элемента массива, $value - значение элемента массива
                        if ($this->chess->getColorFigureBox($obj -> board[$value->xTo][$value->yTo]) == "down"){
                            $figure = $this->chess->getFigure($obj -> board[$value->xTo][$value->yTo], $capture);
                            if ($figure instanceof Bishop){
                                $bishopEval = array_reverse($ratePosition->bishopEvalDown);
                                $value->rate = 0 - $bishopEval[$value->xTo][$value->yTo] + $bishopEval[$x][$y];
                            }
                            if ($figure instanceof King){
                                $kingEval = array_reverse($ratePosition->rookEvalDown);
                                $value->rate  = 0 - $kingEval[$value->xTo][$value->yTo] + $kingEval[$x][$y];
                            }
                            if ($figure instanceof Knight){
                                $knightEval = array_reverse($ratePosition->knightEval);
                                $value->rate  = 0 - $knightEval[$value->xTo][$value->yTo] + $knightEval[$x][$y];
                            }
                            if ($figure instanceof Pawn){
                                $pawnEval = array_reverse($ratePosition->pawnEvalDown);
                                $value->rate = 0 - $pawnEval[$value->xTo][$value->yTo] + $pawnEval[$x][$y];
                            }
                            if ($figure instanceof Queent){
                                $queenEval = array_reverse($ratePosition->queenEval);
                                $value->rate  = 0 - $queenEval[$value->xTo][$value->yTo] + $queenEval[$x][$y];
                            }
                            if ($figure instanceof Rook){
                                $rookEval = array_reverse($ratePosition->rookEvalDown);
                                $value->rate  = 0 - $rookEval[$value->xTo][$value->yTo] + $rookEval[$x][$y];
                            }
                            //вывести отдельную функцию. передаем(массив атак, координаты)
                            //проверить не бьются ли фигуры. если да то уменьшить стоимость клетки на стоимость фигуры
                            if (count($obj -> infUpAttack[$x][$y]) > 0){
                                //на шахматной доске какая моя фигура ходит сюда. т.е. хожу на битую клетку
                                $figureYour = $this->chess->getFigure($obj -> board[$value->xTo][$value->yTo], $capture);
                                $value->rate = $value->rate - $figureYour->force;
                                $value->figureYour = $figureYour->force;
                            }

                            //проверить кол-во и силу обмена


                            //если съест то прибавить силу фигуру противника
                            if ($this->chess->getColorFigureBox($obj -> board[$x][$y]) == "up"){
                                $figureEnemy = $this->chess->getFigure($obj -> board[$x][$y], $capture);
                                $value->rate = $value->rate + $figureEnemy->force;
                                $value->figureEnemy = $figureEnemy->force;
                            }

                            $value -> xFrom = $x;
                            $value -> yFrom = $y;
                            if($maxRateDown < $value->rate){
                                $maxRateDown = $value->rate;
                                $maxRateMasDown = array();
                                $maxRateMasDown[] = $value;
                            }else
                            if ($maxRateDown == $value->rate){
                                $maxRateDown = $value->rate;
                                $maxRateMasDown[] = $value;
                            }
                            $obj -> infDownTurn[$x][$y][$key] = $value;
                        }
                    }
                }
                if (count($obj -> infUpTurn[$x][$y]) > 0){
                    foreach($obj -> infUpTurn[$x][$y] as $key => $value) { // $key - индекс элемента массива, $value - значение элемента массива
                        if ($this->chess->getColorFigureBox($obj -> board[$value->xTo][$value->yTo]) == "up"){
                            $figure = $this->chess->getFigure($obj -> board[$value->xTo][$value->yTo], $capture);
                            if ($figure instanceof Bishop)
                                $value->rate = 0 - $ratePosition->bishopEvalDown[$value->xTo][$value->yTo] + $ratePosition->bishopEvalDown[$x][$y];
                            if ($figure instanceof King)
                                $value->rate  = 0 - $ratePosition->rookEvalDown[$value->xTo][$value->yTo] + $ratePosition->rookEvalDown[$x][$y];
                            if ($figure instanceof Knight)
                                $value->rate  = 0 - $ratePosition->knightEval[$value->xTo][$value->yTo] + $ratePosition->knightEval[$x][$y];
                            if ($figure instanceof Pawn)
                                $value->rate = 0 - $ratePosition->pawnEvalDown[$value->xTo][$value->yTo] + $ratePosition->pawnEvalDown[$x][$y];
                            if ($figure instanceof Queent)
                                $value->rate  = 0 - $ratePosition->queenEval[$value->xTo][$value->yTo] + $ratePosition->queenEval[$x][$y];
                            if ($figure instanceof Rook)
                                $value->rate  = 0 - $ratePosition->rookEvalDown[$value->xTo][$value->yTo] + $ratePosition->rookEvalDown[$x][$y];

                            if (count($obj -> infDownAttack[$x][$y]) > 0){
                                $figureYour = $this->chess->getFigure($obj -> infDownAttack[$x][$y][0], $capture);
                                $value->rate = $value->rate - $figureYour->force;
                            }
                            if ($this->chess->getColorFigureBox($obj -> board[$x][$y]) == "down"){
                                $figureEnemy = $this->chess->getFigure($obj -> board[$x][$y], $capture);
                                $value->rate = $value->rate + $figureEnemy->force;
                            }
                            
                            $value -> xFrom = $x;
                            $value -> yFrom = $y;
                            if($maxRateUp < $value->rate){
                                $maxRateUp = $value->rate;
                                $maxRateMasUp = array();
                                $maxRateMasUp[] = $value;
                            }else
                            if ($maxRateUp == $value->rate){
                                $maxRateUp = $value->rate;
                                $maxRateMasUp[] = $value;
                            }
                            $obj -> infUpTurn[$x][$y][$key] = $value;
                        }
                        
                    }
                }

            }
        }
        $obj -> rateUp = $rateBoardUp;
        $obj -> maxRateMasDown = $maxRateMasDown;
        $obj -> maxRateMasUp = $maxRateMasUp;
        $obj -> rateDown = $rateBoardDown;
        return $obj;
    }

    function analysisBoard($obj){
        $obj = $this->getInf($obj);
        $obj = $this->ratingPosition($obj);
        return $obj;
    }

    function initInf(){
        return array(
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " ")
        );
    }

    function move($obj, $x, $y, $keyX, $keyY){
        $obj -> inf = $this->initInf();
        $obj -> inf[$x][$y]= "prev_to";
        $obj -> inf[$keyX][$keyY]= "prev_from";
        $obj -> board[$x][$y]= $obj -> board[$keyX][$keyY];
        $obj -> board[$keyX][$keyY]= " ";
        $obj -> color = $obj -> color == "down" ? "up" : "down";
        return $obj;
    }

    function moveAI($obj){
        //ломать рокировку только в экстренных случаях
        //проверка на повтор ходов чтоб не было бесмысленности
        //проверка не бьет ли фигуру и сам бит тогда обмен) меньше фигур меньше нагрузка
        //если убрать фигуру не будет ли больше потеря (тут сделать ход и проверить? если больше то вернуть ход)
        $obj = $this->analysisBoard($obj);
        if ($obj -> color == "down"){
            $random = count($obj -> maxRateMasDown) - 1;
            $randomTurn = $obj -> maxRateMasDown[rand(0, $random)];
        }else{
            $random = count($obj -> maxRateMasUp) - 1;
            $randomTurn = $obj -> maxRateMasUp[rand(0, $random)];
        }
        $obj -> random = $random;
        return $this->move($obj, $randomTurn->xFrom, $randomTurn->yFrom, $randomTurn->xTo, $randomTurn->yTo);
    }
}