<!-- Inclui o header -->
 <!-- SQL injoction = ruim, eles tentam roubar dados -->
 <?php include "header.php"?>

    <div class="container mt-3 mb-3">


    
        <?php

            //Verifica o método de requesição do servidor
            if($_SERVER["REQUEST_METHOD"] =="POST"){

                //Bloco para declaração das variavéis
                $fotoProduto = $nomeProduto = $descricaoProduto = $valorProduto = "";

                //Variável para controle de erros de preenchimento
                $erroPreenchimento = false;


                //Validação do campo nomeProduto
                //Utiliza a função empty() para vereficar se o campo está vazio
                if(empty($_POST["nomeProduto"])){
                    echo"
                        <div class='alert alert-warning text-center'>
                            O campo <strong>NOME DO PRODUTO</strong> é obrigatorio!
                        </div>
                    ";
                    $erroPreenchimento = true;
                }
                else{
                    $nomeProduto = filtrar_entrada($_POST["nomeProduto"]);
                }

                //Validação do campo descricaoProduto
                //Utiliza a função empty() para vereficar se o campo está vazio
                if(empty($_POST["descricaoProduto"])){
                    echo"
                        <div class='alert alert-warning text-center'>
                            O campo <strong>DISCRIÇÃO PRODUTO</strong> é obrigatorio!
                        </div>
                    ";
                    $erroPreenchimento = true;
                }
                else{
                    $descricaoProduto = filtrar_entrada($_POST["descricaoProduto"]);
                }

                //Validação do campo valorProduto
                //Utiliza a função empty() para vereficar se o campo está vazio
                if(empty($_POST["valorProduto"])){
                    echo"
                        <div class='alert alert-warning text-center'>
                            O campo <strong>VALOR DO PRODUTO</strong> é obrigatorio!
                        </div>
                    ";
                    $erroPreenchimento = true;
                }
                else{
                    $valorProduto = filtrar_entrada($_POST["valorProduto"]);
                }

            
                //Início da valídação da FOTO DO PRODUTO 
                $diretorio = "img/"; //Define para qual diretório do sistema as imagens serão movidas
                $fotoProduto = $diretorio . basename($_FILES['fotoProduto']['name']); //Img/Produto.png
                $erroUpload = false; //Varíavel para controle de erros de uploud da foto
                $tipoDaimagem = strtolower(pathinfo($fotoProduto, PATHINFO_EXTENSION)); //Pegar extensão do arquivo e converter para validação

                //Verifica se o tamanho do arquivo é maior do que ZERO
                if($_FILES['fotoProduto']['size'] !=0){

                    //Verifica o tamnaho em BYTES (5 megaBytes)
                    if($_FILES['fotoProduto']['size'] > 5000000){ 
                        echo"<div class='alert alert-warning text-center'>A foto não deve ser maior que do que <strong>5 MB</strong></div>";
                        $erroUpload = true;
                    }

                    //Verifica se a foto está em formato permetido (jpg, jpeg, png ou webp)
                    if($tipoDaimagem != "jpg" && $tipoDaimagem != "jpeg" && $tipoDaimagem != 'png' && $tipoDaimagem != 'webp'){ 
                        echo"<div class='alert alert-warning text-center'>A foto deve estar mos formatos <strong>JPG, JPEG, PNG ou WEBP</strong></div>";
                        $erroUpload = true;
                    }

                    //Tenta mover a foto para o diretório img/ utilizando a função move_uploaded_file
                    if(!move_uploaded_file($_FILES['fotoProduto']['tmp_name'], $fotoProduto)){
                        echo"<div class='alert alert-warning text-center'>ERRO ao tentar mover a  <strong>FOTO</strong> para o diretório</div>";
                        $erroUpload = true;
                    }

                }
                else{
                    echo"<div class='alert alert-warning text-center'> ERRO ao tentar realizar<strong>UPLOUD DA FOTO</strong></div>";
                    $erroUpload = true;
                }

                //Se não houver erro de preenchimento, exibe uma mensagem de sucesso e uma tabela com os dados cadastrados
                if(!$erroPreenchimento && !$erroUpload){

                    //Criar uma QUERY responsável por realizar a inserção dos dados no BD
                    $inserirProduto = "INSERT INTO produtos(fotoProduto, nomeProduto, descricaoProduto, valorProduto) VALUES('$fotoProduto', '$nomeProduto', '$descricaoProduto', '$valorProduto')";

                    //Inclui o arquivo de conexão com o BD
                    include("conexaoBD.php");

                    //Se a QUERY for executada, exibe mensagem de sucesso e tabela com os dados
                    if(mysqli_query($conn, $inserirProduto)){


                        echo"<div class='alert alert-success text-center'> <strong>PRODUTO</strong> cadastrado com sucesso! </div>";

                        echo"
                            <div class='container mt-3'>
                                <div class='container mt-3 text-center'>
                                        <img src = '$fotoProduto' style='width:150px;' title='Foto de $nomeProduto'
                                </div>
                                <table class='table'>
                                    <tr>
                                        <th>NOME</th>
                                        <td>$nomeProduto</td>
                                    </tr>
                                    <tr>
                                        <th>DISCRIÇÃO</th>
                                        <td>$descricaoProduto</td>
                                    </tr>
                                    <tr>
                                        <th>VALOR</th>
                                        <td>$valorProduto</td>
                                    </tr>
                                </table>
                            </div>
                        ";
                        mysqli_close($conn); //Encerra a conexão com Banco de Dados
                    }
                    else{
                        echo"<div class='alert alert-danger text-center'> <strong>Erro ao tentar cadastrar Usuário(a)</strong> no Banco de Dados $database! </div>" .mysqli_error($conn);
                    }
                }
            }

            else{
                //Redireciona para a página formUsuario.php
                header("location:formUsuario.php");
            }
            
            //Função para filtrar as entradas de dados
            function filtrar_entrada($dado){
                $dado = trim($dado); //Remove espaçõs desnecessários
                $dado = stripslashes($dado); //Remove barras invertidas
                $dado = htmlspecialchars($dado); //Converte caracteres em entidades html

                return($dado);
            }
        ?>

    </div>

 <!-- Inclui o footer -->
 <?php include "footer.php"?>