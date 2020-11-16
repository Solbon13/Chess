<?php

include 'class/MySQLStorage.php';
include 'class/GameChess.php';
include 'config/db.php';


$chess = new GameChess($storage);
if (isset($_POST['init']))
{    
    $obj = $chess -> newChess();  
    echo ($obj);
}
elseif (isset($_POST['clickBox'])) 
{
    $obj = ($chess->clickBox($_POST['x'], $_POST['y']));
    echo ($obj);
}
elseif (isset($_POST['pawnBox'])) 
{
    $obj = ($chess->pawnBox($_POST['x'], $_POST['y'], $_POST['figure']));
    echo ($obj);
}
elseif (isset($_POST['randomTurn'])) 
{
    $obj = ($chess->randomTurn());
    echo ($obj);
}