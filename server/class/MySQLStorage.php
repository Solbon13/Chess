<?php
    class MysqlStorage {
        var $pdo;
    
        function __construct($dns, $user, $pass){
            $this->pdo = new PDO($dns, $user, $pass);
        }
    
        function save($board, $inf, $color){
            $this->pdo
                ->prepare('UPDATE chess SET board = ?, inf = ?, color = ?')
                ->execute(array($board, $inf, $color));
            return $this->load();
        }
    
        function load(){
            $result = $this->pdo->prepare('SELECT * FROM chess WHERE id = ?');
            $result ->execute(array(0));
            $str = $result->fetch();
            return $str;
        }
    }    