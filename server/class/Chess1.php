<?php
//прошу указать в какие подсистемы и с какими полномочиями подключать пользователей к ЭБ для учета операций со средствами, поступающими во временное распоряжение получателя бюджетных средств 
include 'class/Board.php';
include 'class/Figure.php';
include 'class/Rook.php';
include 'class/Bishop.php';
include 'class/Knight.php';
include 'class/Queen.php';
include 'class/Pawn.php';
include 'class/King.php';
class Chess {
    var $storage;
	
	function __construct(MysqlStorage $storage){
		$this->storage = $storage;
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

    function getColorFigureBox($figureBox){
        $whiteArray = array("&#9814", "&#9816", "&#9815", "&#9813", "&#9812", "&#9815", "&#9816", "&#9814","&#9817");
        if (in_array($figureBox, $whiteArray))
            return "white";
        $blackArray = array("&#9823","&#9820", "&#9822", "&#9821", "&#9819", "&#9818", "&#9821", "&#9822", "&#9820");
        if (in_array($figureBox, $blackArray))
            return "black";
        return "empty";
    }

    function getFigure($figureBox, $capture){
        switch ($figureBox) {
            case "&#9814":
                return new Rook("white");
            case '&#9820':
                return new Rook("black");
            case "&#9816":
                return new Knight("white");
            case '&#9822':
                return new Knight("black");
            case "&#9815":
                return new Bishop("white");
            case '&#9821':
                return new Bishop("black");
            case "&#9813":
                return new Queen("white");
            case '&#9819':
                return new Queen("black");
            case '&#9817':
                return new Pawn("white", $capture);
            case '&#9823':
                return new Pawn("black", $capture);
            case '&#9812':
                return new King("white");
            case '&#9818':
                return new King("black");
        } 
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

    function isAddAttak(){

    }

    function getInf($obj){
        $obj -> infWhiteAttack = array(
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0)
        );
        $obj -> infBlackAttack = array(
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0)
        );
        //перебор доски. проверить можно сходить на клетку. если клетка занята то проверить смогут сходить фигуры противника
        for ($x = 0; $x < 8; $x++){
            for ($y = 0; $y < 8; $y++){
                if ($this->getColorFigureBox($obj -> board[$x][$y]) != "black"){
                    //проверка бьет черная ладья или ферзь вертикаль х горизонталь у
                    for ($x1 = $x + 1; $x1 < 8; $x1++){
                        if ($obj -> board[$x1][$y] != " "){
                            if ($obj -> board[$x1][$y] == "&#9820" || $obj -> board[$x1][$y] == "&#9819"){
                                $obj -> infBlackAttack[$x][$y] ++;
                            }
                        break;
                        }
                    }
                    for ($x1 = $x - 1; $x1 > -1; $x1--){
                        if ($obj -> board[$x1][$y] != " "){
                            if ($obj -> board[$x1][$y] == "&#9820" || $obj -> board[$x1][$y] == "&#9819"){
                                $obj -> infBlackAttack[$x][$y] ++;
                            }
                        break;
                        }
                    }
                    for ($y1 = $y + 1; $y1 < 8; $y1++){
                        if ($obj -> board[$x][$y1] != " "){
                            if ($obj -> board[$x][$y1] == "&#9820" || $obj -> board[$x][$y1] == "&#9819"){
                                $obj -> infBlackAttack[$x][$y] ++;
                            }
                        break;
                        }
                    }
                    for ($y1 = $y - 1; $y1 > -1; $y1--){
                        if ($obj -> board[$x][$y1] != " "){
                            if ($obj -> board[$x][$y1] == "&#9820" || $obj -> board[$x][$y1] == "&#9819"){
                                $obj -> infBlackAttack[$x][$y] ++;
                            }
                        break;
                        }
                    }
                    //проверка бьет черная слон или ферзь
                    $y1 = $y;
                    for ($x1 = $x + 1; $x1 < 8; $x1++){  
                        $y1++;
                        if ($y1 == 8) break;
                        if ($obj -> board[$x1][$y1] != " "){
                            if ($obj -> board[$x1][$y1] == "&#9821" || $obj -> board[$x1][$y1] == "&#9819"){
                                $obj -> infBlackAttack[$x][$y] ++;
                            }
                        break;
                        }
                    }
                    $y1 = $y;
                    for ($x1 = $x - 1; $x1 > -1; $x1--){
                        $y1++;
                        if ($y1 == 8) break;
                        if ($obj -> board[$x1][$y1] != " "){
                            if ($obj -> board[$x1][$y1] == "&#9821" || $obj -> board[$x1][$y1] == "&#9819"){
                                $obj -> infBlackAttack[$x][$y] ++;
                            }
                        break;
                        }
                    }
                    $x1 = $x;
                    for ($y1 = $y - 1; $y1 > -1; $y1--){
                        $x1++;
                        if ($x1 == 8) break;
                        if ($obj -> board[$x1][$y1] != " "){
                            if ($obj -> board[$x1][$y1] == "&#9821" || $obj -> board[$x1][$y1] == "&#9819"){
                                $obj -> infBlackAttack[$x][$y] ++;
                            }
                        break;
                        }
                    }
                    $x1 = $x;
                    for ($y1 = $y - 1; $y1 > -1; $y1--){
                        $x1--;
                        if ($x1 == -1) break;
                        if ($obj -> board[$x1][$y1] != " "){
                            if ($obj -> board[$x1][$y1] == "&#9821" || $obj -> board[$x1][$y1] == "&#9819"){
                                $obj -> infBlackAttack[$x][$y] ++;
                            }
                        break;
                        }
                    }
                    //конь
                    if ($obj -> board[$x + 1][$y + 2] == "&#9822" 
                    || $obj -> board[$x + 1][$y - 2] == "&#9822"
                    || $obj -> board[$x + 2][$y + 1] == "&#9822"
                    || $obj -> board[$x + 2][$y - 1] == "&#9822"
                    || $obj -> board[$x - 1][$y + 2] == "&#9822" 
                    || $obj -> board[$x - 1][$y - 2] == "&#9822"
                    || $obj -> board[$x - 2][$y + 1] == "&#9822"
                    || $obj -> board[$x - 2][$y - 1] == "&#9822"
                    ){
                        $obj -> infBlackAttack[$x][$y] ++;
                    }
                    //пешка
                    if ($obj -> board[$x + 1][$y + 1] == "&#9823"
                    || $obj -> board[$x + 1][$y - 1] == "&#9823"
                    ){
                        $obj -> infBlackAttack[$x][$y] ++;
                    }
                    //король
                    if ($obj -> board[$x + 1][$y + 1] == "&#9818" 
                    || $obj -> board[$x + 1][$y] == "&#9818"
                    || $obj -> board[$x + 1][$y-1] == "&#9818"
                    || $obj -> board[$x - 1][$y + 1] == "&#9818" 
                    || $obj -> board[$x - 1][$y] == "&#9818"
                    || $obj -> board[$x - 1][$y - 1] == "&#9818"
                    || $obj -> board[$x][$y + 1] == "&#9818"
                    || $obj -> board[$x][$y - 1] == "&#9818"
                    ){
                        $obj -> infBlackAttack[$x][$y] ++;
                    }
                }

                if ($this->getColorFigureBox($obj -> board[$x][$y]) == "black"){
                    switch ($obj -> board[$x][$y]) {
                        case "&#9814":
                            $obj -> infWhiteAttack[$x][$y] ++;
                            break;
                        case '&#9820':
                            for ($x1 = $x + 1; $x1 < 8; $x1++){
                                if ($obj -> board[$x1][$y] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y] ++;
                                    }
                                    break;
                                }
                                $obj -> infWhiteAttack[$x1][$y] ++;
                            }
                            for ($x1 = $x - 1; $x1 > -1; $x1--){
                                if ($obj -> board[$x1][$y] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y] ++;
                                    }
                                    break;
                                }
                                $obj -> infWhiteAttack[$x1][$y] ++;
                            }
                            for ($y1 = $y + 1; $y1 < 8; $y1++){
                                if ($obj -> board[$x][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x][$y1] ++;
                                    }
                                    break;
                                }
                                $obj -> infWhiteAttack[$x][$y1] ++;
                            }
                            for ($y1 = $y - 1; $y1 > -1; $y1--){
                                if ($obj -> board[$x][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x][$y1] ++;
                                    }
                                    break;
                                }
                                $obj -> infWhiteAttack[$x][$y1] ++;
                            }
                            break;
                        case "&#9816":
                            $obj -> infWhiteAttack[$x][$y] ++; 
                            break;
                        case '&#9822':
                            if ($this->getColorFigureBox($obj -> board[$x + 1][$y + 2]) != "black")
                                $obj -> infWhiteAttack[$x + 1][$y + 2] ++; 
                            if ($this->getColorFigureBox($obj -> board[$x + 1][$y -2]) != "black")
                                $obj -> infWhiteAttack[$x + 1][$y - 2] ++;
                            if ($this->getColorFigureBox($obj -> board[$x + 2][$y + 1]) != "black")
                                $obj -> infWhiteAttack[$x + 2][$y + 1] ++;
                            if ($this->getColorFigureBox($obj -> board[$x + 2][$y - 1]) != "black")
                                $obj -> infWhiteAttack[$x + 2][$y - 1] ++;
                            if ($this->getColorFigureBox($obj -> board[$x - 1][$y + 2]) != "black")
                                $obj -> infWhiteAttack[$x - 1][$y + 2] ++;
                            if ($this->getColorFigureBox($obj -> board[$x - 1][$y - 2]) != "black")
                                $obj -> infWhiteAttack[$x - 1][$y - 2] ++; 
                            if ($this->getColorFigureBox($obj -> board[$x - 2][$y + 1]) != "black")
                                $obj -> infWhiteAttack[$x - 2][$y + 1] ++;
                            if ($this->getColorFigureBox($obj -> board[$x - 2][$y - 1]) != "black")
                                $obj -> infWhiteAttack[$x - 2][$y - 1] ++; 
                            break;
                        case "&#9815":
                            $obj -> infWhiteAttack[$x][$y] ++;
                            break;
                        case '&#9821':
                            $y1 = $y;
                            for ($x1 = $x + 1; $x1 < 8; $x1++){  
                                $y1++;
                                if ($y1 == 8) break;
                                if ($obj -> board[$x1][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y1] ++;
                                    }
                                break;
                                }
                                $obj -> infWhiteAttack[$x1][$y1] ++;
                            }
                            $y1 = $y;
                            for ($x1 = $x - 1; $x1 > -1; $x1--){
                                $y1++;
                                if ($y1 == 8) break;
                                if ($obj -> board[$x1][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y1] ++;
                                    }
                                break;
                                }
                                $obj -> infWhiteAttack[$x1][$y1] ++;
                            }
                            $x1 = $x;
                            for ($y1 = $y - 1; $y1 > -1; $y1--){
                                $x1++;
                                if ($x1 == 8) break;
                                if ($obj -> board[$x1][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y1] ++;
                                    }
                                break;
                                }
                                $obj -> infWhiteAttack[$x1][$y1] ++;
                            }
                            $x1 = $x;
                            for ($y1 = $y - 1; $y1 > -1; $y1--){
                                $x1--;
                                if ($x1 == -1) break;
                                if ($obj -> board[$x1][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y1] ++;
                                    }
                                break;
                                }
                                $obj -> infWhiteAttack[$x1][$y1] ++;
                            }
                            break;
                        case "&#9813":
                            $obj -> infWhiteAttack[$x][$y] ++;
                            break;
                         case '&#9819':
                            for ($x1 = $x + 1; $x1 < 8; $x1++){
                                if ($obj -> board[$x1][$y] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y] ++;
                                    }
                                    break;
                                }
                                $obj -> infWhiteAttack[$x1][$y] ++;
                            }
                            for ($x1 = $x - 1; $x1 > -1; $x1--){
                                if ($obj -> board[$x1][$y] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y] ++;
                                    }
                                    break;
                                }
                                $obj -> infWhiteAttack[$x1][$y] ++;
                            }
                            for ($y1 = $y + 1; $y1 < 8; $y1++){
                                if ($obj -> board[$x][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x][$y1] ++;
                                    }
                                    break;
                                }
                                $obj -> infWhiteAttack[$x][$y1] ++;
                            }
                            for ($y1 = $y - 1; $y1 > -1; $y1--){
                                if ($obj -> board[$x][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x][$y1] ++;
                                    }
                                    break;
                                }
                                $obj -> infWhiteAttack[$x][$y1] ++;
                            }
                            $y1 = $y;
                            for ($x1 = $x + 1; $x1 < 8; $x1++){  
                                $y1++;
                                if ($y1 == 8) break;
                                if ($obj -> board[$x1][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y1] ++;
                                    }
                                break;
                                }
                                $obj -> infWhiteAttack[$x1][$y1] ++;
                            }
                            $y1 = $y;
                            for ($x1 = $x - 1; $x1 > -1; $x1--){
                                $y1++;
                                if ($y1 == 8) break;
                                if ($obj -> board[$x1][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y1] ++;
                                    }
                                break;
                                }
                                $obj -> infWhiteAttack[$x1][$y1] ++;
                            }
                            $x1 = $x;
                            for ($y1 = $y - 1; $y1 > -1; $y1--){
                                $x1++;
                                if ($x1 == 8) break;
                                if ($obj -> board[$x1][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y1] ++;
                                    }
                                break;
                                }
                                $obj -> infWhiteAttack[$x1][$y1] ++;
                            }
                            $x1 = $x;
                            for ($y1 = $y - 1; $y1 > -1; $y1--){
                                $x1--;
                                if ($x1 == -1) break;
                                if ($obj -> board[$x1][$y1] != " "){
                                    if ($this->getColorFigureBox($obj -> board[$x1][$y1]) == "white"){
                                        $obj -> infWhiteAttack[$x1][$y1] ++;
                                    }
                                break;
                                }
                                $obj -> infWhiteAttack[$x1][$y1] ++;
                            }
                            break;
                        case '&#9817':
                            $obj -> infWhiteAttack[$x][$y] ++;
                            break;
                        case '&#9823':
                            if ($this->getColorFigureBox($obj -> board[$x - 1][$y + 1]) != "black")
                                $obj -> infWhiteAttack[$x - 1][$y + 1] ++;
                            if ($this->getColorFigureBox($obj -> board[$x - 1][$y - 1]) != "black")
                                $obj -> infWhiteAttack[$x - 1][$y - 1] ++;
                            break;
                        case '&#9812':
                            $obj -> infWhiteAttack[$x][$y] ++;
                            break;
                        case '&#9818':
                            if ($this->getColorFigureBox($obj -> board[$x + 1][$y + 1]) != "black")
                                $obj -> infWhiteAttack[$x + 1][$y + 1] ++; 
                            if ($this->getColorFigureBox($obj -> board[$x + 1][$y]) != "black")
                                $obj -> infWhiteAttack[$x + 1][$y] ++;
                            if ($this->getColorFigureBox($obj -> board[$x + 1][$y - 1]) != "black")
                                $obj -> infWhiteAttack[$x + 1][$y-1] ++;
                            if ($this->getColorFigureBox($obj -> board[$x - 1][$y + 1]) != "black")
                                $obj -> infWhiteAttack[$x - 1][$y + 1] ++; 
                            if ($this->getColorFigureBox($obj -> board[$x - 1][$y]) != "black")
                                $obj -> infWhiteAttack[$x - 1][$y] ++;
                            if ($this->getColorFigureBox($obj -> board[$x - 1][$y - 1]) != "black")
                                $obj -> infWhiteAttack[$x - 1][$y - 1] ++;
                            if ($this->getColorFigureBox($obj -> board[$x][$y + 1]) != "black")
                                $obj -> infWhiteAttack[$x][$y + 1] ++;
                            if ($this->getColorFigureBox($obj -> board[$x][$y - 1]) != "black")
                                $obj -> infWhiteAttack[$x][$y - 1] ++;
                            break;
                    }
                }                    
            }
        }
    }

    function isCheck($obj, $xFrom, $yFrom, $xTo, $yTo){
        //проверить свободен ли путь перебором клеток хода
        // определяем откуда куда проверять
        if ($xFrom > $xTo){
            $maxX = $xFrom;
            $minX = $xTo;
        }else{
            $maxX = $xTo;
            $minX = $xFrom;
        }
        if ($yFrom > $yTo){
            $maxY = $yFrom;
            $minY = $yTo;
        }else{
            $maxY = $yTo;
            $minY = $yFrom;
        }

        /* если Х не меняется проверить фором только У */
        if ($minX == $maxX){
            for ($y = $minY + 1; $y < $maxY; $y++) {
                if ($obj -> board[$minX][$y] != " "){
                    //$obj -> boardX= $obj -> board[$minX][$y];
                    return false;}
            }
        }else if ($minY == $maxY){
            for ($x = $minX + 1; $x < $maxX; $x++) {
                if ($obj -> board[$x][$minY] != " "){
                    //$obj -> boardY = $obj -> board[$x][$minY];
                    return false;
                }
            }
        }else{
            for ($x = $minX + 1; $x < $maxX; $x++) {
                $minY ++;
                if ($obj -> board[$x][$minY] != " "){
                    //$obj -> boardXY= $xFrom + ' ' + $yFrom;
                    return false;
                }
            }
        }
        return true;
    }

    function move($obj, $x, $y, $keyX, $keyY){
        $obj -> inf = $this->initInf();
        $obj -> inf[$x][$y]= "2";
        $obj -> inf[$keyX][$keyY]= "1";
        $obj -> board[$x][$y]= $obj -> board[$keyX][$keyY];
        $obj -> board[$keyX][$keyY]= " ";
        $color = $obj -> color == "white" ? "black" : "white";
        $this->storage -> save(json_encode($obj -> board), json_encode($obj -> inf), $color);
        return $obj;
    }

    function findFirst($obj){
        $keyX = -1;
        $keyY = -1;
        $firstClick -> keyX = $keyX;
        $firstClick -> keyY = $keyY;
        foreach ($obj -> inf as $item) {
            $keyY = array_search("3", $item);
            ++ $keyX;
            //проверить возможность хода ()
            if (!($keyY === false)){
                $firstClick -> keyX = $keyX;
                $firstClick -> keyY = $keyY;
                return $firstClick;
            }         
        }
        return $firstClick;
    }

    function isClickBox($obj, $x, $y){
        $this->getInf($obj);
        $capture = $this->getColorFigureBox($obj -> board[$x][$y]);
        if ($obj -> board[$x][$y] == " " || $capture != $obj -> color){
            //может ходит (проверить есть ли значение 3 в массиве inf)
            $firstClick = $this->findFirst($obj, $x, $y);
            if ($firstClick -> keyX != -1){
                
                $figure = $this->getFigure($obj -> board[$firstClick -> keyX][$firstClick -> keyY], $capture);
                
                if ($figure->isMove($x, $y, $firstClick -> keyX , $firstClick -> keyY))
                if ($figure instanceof Knight || 
                    $this->isCheck($obj, $x, $y, $firstClick -> keyX , $firstClick -> keyY))
                        return $this->move($obj, $x, $y, $firstClick -> keyX , $firstClick -> keyY);
            }
        }
        else if ($this->getColorFigureBox($obj -> board[$x][$y]) === $obj -> color)
        {
            $firstClick = $this->findFirst($obj, $x, $y);
            if ($firstClick -> keyX != -1)
                $obj -> inf[$firstClick -> keyX][$firstClick -> keyY]= " ";
            //$obj -> inf = $this->initInf();
            $obj -> inf[$x][$y]= "3";
            $this->storage -> save(json_encode($obj -> board), json_encode($obj -> inf), $obj -> color);
            return $obj;}
        return $obj;
    }

    function newChess(){
        $map = array(
            //array("&#9814", "&#9816", "&#9815", "&#9813", "&#9812", "&#9815", "&#9816", "&#9814"),
            //array("&#9817", "&#9817", "&#9817", "&#9817", "&#9817", "&#9817", "&#9817", "&#9817"),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", "&#9822", " ", "&#9819", " ", " "),
            array(" ", "&#9818", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", "&#9821", " ", " ", " "),
            array(" ", "&#9820", " ", " ", " ", " ", " ", " "),
            array(" ", " ", " ", " ", " ", " ", "&#9823", " "),
            array(" ", " ", " ", " ", " ", " ", " ", " "),
            //array("&#9823", "&#9823", "&#9823", "&#9823", "&#9823", "&#9823", "&#9823", "&#9823"),
            //array("&#9820", "&#9822", "&#9821", "&#9819", "&#9818", "&#9821", "&#9822", "&#9820")
        );
        $inf = $this->initInf();
        $color = "white";

        $this->storage -> save(json_encode($map), json_encode($inf), $color);
        $sql = $this->storage -> load();
        
        $obj = $this->sqlDecodeObj($sql);
    
        return json_encode($obj);
    }

    function clickBox($x, $y){
        $sql = $this->storage -> load();
        
        $obj = $this->sqlDecodeObj($sql);

        $obj = $this->isClickBox($obj, $x, $y);
    
        return json_encode($obj);
    }

}



