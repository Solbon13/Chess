<?php
//прошу указать в какие подсистемы и с какими полномочиями подключать пользователей к ЭБ для учета операций со средствами, поступающими во временное распоряжение получателя бюджетных средств 
include 'class/Chess.php';
include 'class/AIChess.php';
include 'class/Figure.php';
include 'class/Rook.php';
include 'class/Bishop.php';
include 'class/Knight.php';
include 'class/Queen.php';
include 'class/Pawn.php';
include 'class/King.php';
class GameChess {
    var $storage;
	
	function __construct(MysqlStorage $storage){
        $this->storage = $storage;
        $this->chess = new Chess;
    }
    
    function readDB(){
        $sql = $this->storage -> load();
        
        return $this->sqlDecodeObj($sql);
    }

    function sqlDecodeObj($sql){
        $board = json_decode($sql[board]);
        $inf = json_decode($sql[inf]);
        $color = json_decode($sql[color]);
        
        $obj -> board = $board;
        $obj -> inf = $inf;
        $obj -> color = $color;
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

    function isFreeTurn($obj, $xFrom, $yFrom, $xTo, $yTo){
        //проверить свободен ли путь перебором клеток хода
        // определяем откуда куда проверять
        if ($xFrom > $xTo){
            $deltaX = 1;
        }else{
            if ($xFrom == $xTo){
                $deltaX = 0;
            }else{
                $deltaX = -1;
            }
        }
        if ($yFrom > $yTo){
            $deltaY = 1;
        }else{
            if ($yFrom == $yTo){
                $deltaY = 0;
            }else{
                $deltaY = -1;
            }
        }
        while ($xFrom != $xTo) {
            $xTo += $deltaX;
            $yTo += $deltaY;
            if ($xFrom != $xTo)
            if ($obj -> board[$xTo][$yTo] != " "){
                return false;
            }
        }
        return true;
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

    function findBoard($obj, $findString){
        $keyX = -1;
        $keyY = -1;
        $findBoard -> keyX = $keyX;
        $findBoard -> keyY = $keyY;
        foreach ($obj -> inf as $item) {
            $keyY = array_search($findString, $item);
            ++ $keyX;
            //проверить возможность хода ()
            if (!($keyY === false)){
                $findBoard -> keyX = $keyX;
                $findBoard -> keyY = $keyY;
                return $findBoard;
            }         
        }
        return $findBoard;
    }

    function isClickBox($obj, $x, $y){
        $capture = $this->chess->getColorFigureBox($obj -> board[$x][$y]);
        if ($obj -> board[$x][$y] == " " || $capture != $obj -> color){
            //может ходит (проверить есть ли значение 3 в массиве inf)
            $firstClick = $this->findBoard($obj, "is_click");
            if ($firstClick -> keyX != -1){  
                $figure = $this->chess->getFigure($obj -> board[$firstClick -> keyX][$firstClick -> keyY], $capture);
                $prevY = -1;
                //до потому чтоб найти последний ход
                if ($figure instanceof Pawn)
                {
                    //проверка на взятие на проходе
                    $findBoard = $this->findBoard($obj, "prev_from");
                    if ($findBoard->keyX == 1 || $findBoard->keyX == 6){
                        $findBoard = $this->findBoard($obj, "prev_to");
                        if ($findBoard->keyX == 3 || $findBoard->keyX == 4){
                            $prevY = $findBoard->keyY;
                            if ($y == $obj -> keyY)
                                $obj -> board[$findBoard->keyX][$findBoard->keyY] = " ";
                        }
                    }
                }
                if ($figure->isMove($x, $y, $firstClick -> keyX , $firstClick -> keyY, $prevY)){
                    if ($figure instanceof Knight || 
                    $this->isFreeTurn($obj, $x, $y, $firstClick -> keyX , $firstClick -> keyY))
                        {
                            if ($figure instanceof King)
                            {
                                //Рокировка
                                if ($x == 0 && $y == 6){
                                    $obj -> board[$x][5] = $obj -> board[$x][7];
                                    $obj -> board[$x][7] = " ";
                                    }
                                if ($x == 0 && $y == 2){
                                    $obj -> board[$x][3] = $obj -> board[$x][0];
                                    $obj -> board[$x][0] = " ";
                                    }
                                if ($x == 7 && $y == 6){
                                    $obj -> board[$x][5] = $obj -> board[$x][7];
                                    $obj -> board[$x][7] = " ";
                                    }
                                if ($x == 7 && $y == 2){
                                    $obj -> board[$x][3] = $obj -> board[$x][0];
                                    $obj -> board[$x][0] = " ";
                                    }
                            }
                            return $this->move($obj, $x, $y, $firstClick -> keyX , $firstClick -> keyY);
                        }
                }
            }
        }
        else if ($capture === $obj -> color)
        {
            $firstClick = $this->findBoard($obj, "is_click");
            if ($firstClick -> keyX != -1)
                $obj -> inf[$firstClick -> keyX][$firstClick -> keyY]= " ";
            $obj -> inf[$x][$y]= "is_click";
            return $obj;}
        return $obj;
    }

    function newChess(){
        $map = array(
            array("&#9814", "&#9816", "&#9815", "&#9813", "&#9812", "&#9815", "&#9816", "&#9814"),
            array("&#9817", "&#9817", "&#9817", "&#9817", "&#9817", "&#9817", "&#9817", "&#9817"),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array("&#9823", "&#9823", "&#9823", "&#9823", "&#9823", "&#9823", "&#9823", "&#9823"),
            array("&#9820", "&#9822", "&#9821", "&#9819", "&#9818", "&#9821", "&#9822", "&#9820")
        );
        $inf = $this->initInf();
        $color = "down";

        $this->storage -> save(json_encode($map), json_encode($inf), $color);
        
        $obj = $this->readDB();
        
        return json_encode($obj);
    }

    function clickBox($x, $y){
        
        $obj = $this->readDB();

        $obj = $this->isClickBox($obj, $x, $y);
    
        $this->storage -> save(json_encode($obj -> board), json_encode($obj -> inf), $obj -> color);
        
        $obj = $this->readDB();
        $obj = $this->chess->toFEN($obj);
        $aiChess = new AIChess;
        $obj = $aiChess->analysisBoard($obj);
        return json_encode($obj);
    }

    function randomTurn(){
        
        $obj = $this->readDB();
        
        $aiChess = new AIChess;
        $obj = $aiChess->moveAI($obj);

        $this->storage -> save(json_encode($obj -> board), json_encode($obj -> inf), $obj -> color);
        
        $obj = $this->readDB();
        $obj = $aiChess->analysisBoard($obj);
        return json_encode($obj);
    }

    function pawnBox($x, $y, $figure){
        $obj = $this->readDB();

        if ($obj -> board[$x][$y] == "&#9823" || $obj -> board[$x][$y] == "&#9817")
        {
            switch ($figure) {
                case "r":
                    $figure = "&#9814";
                    break;
                case 'R':
                    $figure = "&#9820";
                    break;
                case "n":
                    $figure = "&#9816";
                    break;
                case 'N':
                    $figure = "&#9822";
                    break;
                case "b":
                    $figure = "&#9815";
                    break;
                case 'B':
                    $figure = "&#9821";
                    break;
                case "q":
                    $figure = "&#9813";
                    break;
                case "Q":
                    $figure = "&#9819";
                    break;
            }
            $obj -> board[$x][$y] = $figure;
            $this->storage -> save(json_encode($obj -> board), json_encode($obj -> inf), $obj -> color);
        
            $obj = $this->readDB();
            
            return json_encode($obj);
        }        
    }
}



