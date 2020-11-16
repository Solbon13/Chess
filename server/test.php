<?php
function initInf(){
    return array(
        array("3", " ", " ", " ", " ", " ", " ", " "),
        array(" ", " ", " ", " ", " ", " ", " ", " "),
        array(" ", " ", " ", " ", " ", " ", " ", " "),
        array(" ", " ", " ", " ", " ", " ", " ", " "),
        array(" ", " ", " ", " ", " ", " ", " ", " "),
        array(" ", " ", " ", " ", " ", " ", " ", " "),
        array(" ", " ", " ", " ", " ", " ", " ", " "),
        array(" ", " ", " ", " ", " ", " ", " ", " ")
    );
}

$inf = initInf();
$infR = array_reverse($inf);
print_r($infR);