//только взаимодействие клиента с сервером
function show_board(board, inf) {
    let html_board = "<table border='1'>";
    let color_board_square;
    for (let i = 0; i < 8; i++) {
        html_board += "<tr>";
        for (let j = 0; j < 8; j++) {
            if (i % 2 == j % 2 || i % 2 == j % 2)
                color_board_square = 'chess__square_black'
            else
                color_board_square = 'chess__square_white';

            if (inf[i][j] == 'prev_from')
                color_board_square = 'chess__square_prev_from'
            else if (inf[i][j] == 'prev_to')
                color_board_square = 'chess__square_prev_to'
            else if (inf[i][j] == 'is_click')
                color_board_square = 'chess__square_is_click';
            else if (inf[i][j] == 'is_move')
                color_board_square = 'chess__square_is_move';
            html_board += `<td class = 'chess__square ${color_board_square}'  onclick = 'click_box(${i}, ${j})'>`;
            html_board += board[i][j];
            html_board += "</td>";
        }
        html_board += "</tr>";
    }
    html_board += "</table>";
    document.getElementById("info").innerHTML = "Ход - " + move_color;
    document.getElementById("games").innerHTML = html_board;

    if (board[0].indexOf("&#9823") != -1){
        select_figure(0, board[0].indexOf("&#9823"));
    }

    if (board[7].indexOf("&#9817") != -1){
        select_figure(7, board[7].indexOf("&#9817"));
    }
}

function exhangePHP(data) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText != '') {
                var data = JSON.parse(this.responseText);
                console.log(data);
                board = JSON.parse(this.responseText).board;
                inf = JSON.parse(this.responseText).inf;
                move_color = JSON.parse(this.responseText).color;
                show_board(board, inf);
            }
        }
    }
    xmlhttp.open("POST", "server/chess.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(data);
}

function newChess() {
    exhangePHP("init");
}

function randomTurn() {
    exhangePHP("randomTurn");
}


function click_box(x, y) {
    data = 'clickBox&x=' + x + '&y=' + y;
    exhangePHP(data);
}

exhangePHP("init");

//пешка меняет статус
var modal = document.getElementById("my_modal");
var figureQ = document.getElementById("Q");
var figureR = document.getElementById("R");
var figureB = document.getElementById("B");
var figureN = document.getElementById("N");
var select_x_pawn_figure;
var select_y_pawn_figure;

function transformFigure(figure){
    modal.style.display = "none";
    if (move_color != "up")
        figure = figure.toUpperCase();
    else
        figure = figure.toLowerCase();
    data = 'pawnBox&x=' + select_x_pawn_figure + '&y=' + select_y_pawn_figure +'&figure=' + figure;
    exhangePHP(data);
}

function select_figure(to_x, to_y){
    modal.style.display = "block";
    select_x_pawn_figure = to_x;
    select_y_pawn_figure = to_y;
}

figureQ.onclick = function () {    
    figure = "Q";
    transformFigure(figure);
}

figureR.onclick = function () {
    figure = "R";
    transformFigure(figure);
}

figureB.onclick = function () {
    figure = "B";
    transformFigure(figure);
}

figureN.onclick = function () {
    figure = "N";
    transformFigure(figure);
}
