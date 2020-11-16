<?php
  
  class Chess {

    function getColorFigureBox($figureBox){
        $downArray = array("&#9814", "&#9816", "&#9815", "&#9813", "&#9812", "&#9815", "&#9816", "&#9814","&#9817");
        if (in_array($figureBox, $downArray))
            return "down";
        $upArray = array("&#9823","&#9820", "&#9822", "&#9821", "&#9819", "&#9818", "&#9821", "&#9822", "&#9820");
        if (in_array($figureBox, $upArray))
            return "up";
        return "empty";
    }

    function getFigure($figureBox, $capture){
        switch ($figureBox) {
            case "&#9814":
                return new Rook("down");
            case '&#9820':
                return new Rook("up");
            case "&#9816":
                return new Knight("down");
            case '&#9822':
                return new Knight("up");
            case "&#9815":
                return new Bishop("down");
            case '&#9821':
                return new Bishop("up");
            case "&#9813":
                return new Queen("down");
            case '&#9819':
                return new Queen("up");
            case '&#9817':
                return new Pawn("down", $capture);
            case '&#9823':
                return new Pawn("up", $capture);
            case '&#9812':
                return new King("down");
            case '&#9818':
                return new King("up");
        } 
    }

    function toFEN($obj){
        $fen = '';
        $emptyBox = 0;
        for ($x = 0; $x < 8; $x++){
            if ($emptyBox != 0){
                $fen = $fen . $emptyBox;
                $emptyBox = 0;
            }
            for ($y = 0; $y < 8; $y++){
                if ($obj -> board[$x][$y] != " "){
                    if ($emptyBox != 0){
                        $fen = $fen . $emptyBox;
                        $emptyBox = 0;
                    }
                    switch ($obj -> board[$x][$y]) {
                        case "&#9814":
                            $figure = "r";
                            break;
                        case '&#9820':
                            $figure = "R";
                            break;
                        case "&#9816":
                            $figure = "n";
                            break;
                        case '&#9822':
                            $figure = "N";
                            break;
                        case "&#9815":
                            $figure = "b";
                            break;
                        case '&#9821':
                            $figure = "B";
                            break;
                        case "&#9813":
                            $figure = "q";
                            break;
                        case "&#9819":
                            $figure = "Q";
                            break;
                        case '&#9817':
                            $figure = "p";
                            break;
                        case '&#9823':
                            $figure = "P";
                            break;
                        case '&#9812':
                            $figure = "k";
                            break;
                        case '&#9818':
                            $figure = "K";
                            break;
                    }
                    $fen = $fen . $figure;
                }else{
                    $emptyBox++; 
                }      
            }
        }
        $color = $obj -> color == "down" ? "w" : "b";
        $fen = $fen . ' ' . $color;
        $obj -> fen = $fen;
        return $obj;
    }

  }