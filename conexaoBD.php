<?php
    $hostBD = "localhost"; //Localização do servidor de BD
    $userBD = "root"; //Usuário do BD
    $senhaBD = "root"; //Senha do BD  -->  No ifpr é root em casa é vazio""
    $database = "sistemainf3"; //Nome do BD no qual se deseja efetuar a conexão

    //Função do PHP responsavel por estabelecer conexão com BD
    $conn = mysqli_connect($hostBD, $userBD, $senhaBD, $database);
    //Para testar  -->  http://localhost/sistemainf3/conexaoBD.php

    //Se mão conectar, exibe alerta de erro
    if(!$conn){
        echo"<p>Erro ao tentar conectar à Base de Dados <strong>$database</strong></p>";
    }
?>