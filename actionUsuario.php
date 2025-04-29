<!-- Inclui o header -->
 <!-- SQL injoction = ruim, eles tentam roubar dados -->
 <?php include "header.php"?>

    <div class="container mt-3 mb-3">

        <?php

            //Verifica o método de requesição do servidor
            if($_SERVER["REQUEST_METHOD"] =="POST"){

                //Bloco para declaração das variavéis
                $fotoUsuario = $nomeUsuario = $dataNascimentoUsuario = $cidadeUsuario = $telefoneUsuario = $emailUsuario = $senhaUsuario = $confirmarSenhaUsuario = "";

                //Variável para controle de erros de preenchimento
                $erroPreenchimento = false;


                //Validação do campo nomeUsuario
                //Utiliza a função empty() para vereficar se o campo está vazio
                if(empty($_POST["nomeUsuario"])){
                    echo"
                        <div class='alert alert-warning text-center'>
                            O campo <strong>NOME</strong> é obrigatorio!
                        </div>
                    ";
                    $erroPreenchimento = true;
                }
                else{
                    $nomeUsuario = filtrar_entrada($_POST["nomeUsuario"]);
                    //Utiliza a função preg_match() para vereficar o padrão de caracteres
                    if(!preg_match('/^[\p{L} ]+$/u', $nomeUsuario)){
                        echo"<div class='alert alert-warning text-center'> O campo <strong>NOME</strong> deve conter apenas letras! </div>";
                        $erroPreenchimento = true;

                    }
                }


                //Validação do campo dataNascimentoUsuario
                //Utiliza a função empty() para vereficar se o campo está vazio
                if(empty($_POST["dataNascimentoUsuario"])){
                    echo"<div class='alert alert-warning text-center'> O campo <strong>DATA DE NASCIMENTO</strong> é obrigatorio! </div>";
                    $erroPreenchimento = true;
                }
                else{
                    $dataNascimentoUsuario = filtrar_entrada($_POST["dataNascimentoUsuario"]);
                    //Aplica a função strlen() para contabilizar a quantidade de caracteres
                    if(strlen($dataNascimentoUsuario) == 10){
                        //Aplica a função subtr() para segmentar a string dataNascimentoUsuario
                        $diaNascimentoUsuario = substr($dataNascimentoUsuario, 8, 2);
                        $mesNascimentoUsuario = substr($dataNascimentoUsuario, 5, 2);
                        $anoNascimentoUsuario = substr($dataNascimentoUsuario, 0, 4);

                        //Aplica a função chekdate() para verificar se trate-se de uma data valida
                        if(!checkdate($mesNascimentoUsuario, $diaNascimentoUsuario, $anoNascimentoUsuario)){
                            echo"<div class='alert alert-warning text-center'><strong>DATA DATA INVÁLIDA</strong></div>";
                            $erroPreenchimento = true;
                        }

                        
                    }
                    else{
                        echo"<div class='alert alert-warning text-center'><strong>DATA DATA INVÁLIDA</strong></div>";
                        $erroPreenchimento = true;
                    }
                }


                //Validação do campo cidadeUsuario
                //Utiliza a função empty() para vereficar se o campo está vazio
                if(empty($_POST["cidadeUsuario"])){
                    echo"<div class='alert alert-warning text-center'> O campo <strong>CIDADE</strong> é obrigatorio! </div>";
                    $erroPreenchimento = true;
                }
                else{
                    $cidadeUsuario = filtrar_entrada($_POST["cidadeUsuario"]);
                }


                //Validação do campo telefoneUsuario
                //Utiliza a função empty() para vereficar se o campo está vazio
                if(empty($_POST["telefoneUsuario"])){
                    echo"<div class='alert alert-warning text-center'> O campo <strong>TELEFONE</strong> é obrigatorio! </div>";
                    $erroPreenchimento = true;
                }
                else{
                    $telefoneUsuario = filtrar_entrada($_POST["telefoneUsuario"]);
                }


                //Validação do campo emailUsuario
                //Utiliza a função empty() para vereficar se o campo está vazio
                if(empty($_POST["emailUsuario"])){
                    echo"<div class='alert alert-warning text-center'> O campo <strong>EMAIL</strong> é obrigatorio! </div>";
                    $erroPreenchimento = true;
                }
                else{
                    $emailUsuario = filtrar_entrada($_POST["emailUsuario"]);
                }


                //Validação do campo senhaUsuario
                //Utiliza a função empty() para vereficar se o campo está vazio
                if(empty($_POST["senhaUsuario"])){
                    echo"<div class='alert alert-warning text-center'> O campo <strong>SENHA</strong> é obrigatorio! </div>";
                    $erroPreenchimento = true;
                }
                else{
                    $senhaUsuario = md5(filtrar_entrada($_POST["senhaUsuario"]));
                }


                //Validação do campo confirmarSenhaUsuario
                //Utiliza a função empty() para vereficar se o campo está vazio
                if(empty($_POST["confirmarSenhaUsuario"])){
                    echo"<div class='alert alert-warning text-center'> O campo <strong>CORFIRMAR SENHA</strong> é obrigatorio! </div>";
                    $erroPreenchimento = true;
                }
                else{
                    $confirmarSenhaUsuario = md5(filtrar_entrada($_POST["confirmarSenhaUsuario"]));
                    //Compara as senhas
                    if($senhaUsuario != $confirmarSenhaUsuario){
                        echo"<div class='alert alert-warning text-center'> AS <strong>SENHAS</strong> informadas são diferentes! </div>";
                        $erroPreenchimento = true;
                    }
                }


                //Início da valídação da FOTO DO USUÁRIO 
                $diretorio = "img/"; //Define para qual diretório do sistema as imagens serão movidas
                $fotoUsuario = $diretorio . basename($_FILES['fotoUsuario']['name']); //Img/Usuario.png
                $erroUpload = false; //Varíavel para controle de erros de uploud da foto
                $tipoDaimagem = strtolower(pathinfo($fotoUsuario, PATHINFO_EXTENSION)); //Pegar extensão do arquivo e converter para validação

                //Verifica se o tamanho do arquivo é maior do que ZERO
                if($_FILES['fotoUsuario']['size'] !=0){

                    //Verifica o tamnaho em BYTES (5 megaBytes)
                    if($_FILES['fotoUsuario']['size'] > 5000000){ 
                        echo"<div class='alert alert-warning text-center'>A foto não deve ser maior que do que <strong>5 MB</strong></div>";
                        $erroUpload = true;
                    }

                    //Verifica se a foto está em formato permetido (jpg, jpeg, png ou webp)
                    if($tipoDaimagem != "jpg" && $tipoDaimagem != "jpeg" && $tipoDaimagem != 'png' && $tipoDaimagem != 'webp'){ 
                        echo"<div class='alert alert-warning text-center'>A foto deve estar mos formatos <strong>JPG, JPEG, PNG ou WEBP</strong></div>";
                        $erroUpload = true;
                    }

                    //Tenta mover a foto para o diretório img/ utilizando a função move_uploaded_file
                    if(!move_uploaded_file($_FILES['fotoUsuario']['tmp_name'], $fotoUsuario)){
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
                    $inserirUsuario = "INSERT INTO Usuarios(fotoUsuario, nomeUsuario, dataNascimentoUsuario, cidadeUsuario, telefoneUsuario, emailUsuario, senhaUsuario) VALUES('$fotoUsuario', '$nomeUsuario', '$dataNascimentoUsuario', '$cidadeUsuario', '$telefoneUsuario', '$emailUsuario', '$senhaUsuario')";

                    //Inclui o arquivo de conexão com o BD
                    include("conexaoBD.php");

                    //Se a QUERY for executada, exibe mensagem de sucesso e tabela com os dados
                    if(mysqli_query($conn, $inserirUsuario)){


                        echo"<div class='alert alert-success text-center'> <strong>USUÁRIO</strong> cadastrado com sucesso! </div>";

                        echo"
                            <div class='container mt-3'>
                                <div class='container mt-3 text-center'>
                                        <img src = '$fotoUsuario' style='width:150px;' title='Foto de $nomeUsuario'
                                </div>
                                <table class='table'>
                                    <tr>
                                        <th>NOME</th>
                                        <td>$nomeUsuario</td>
                                    </tr>
                                    <tr>
                                        <th>DATA DE NASCIMENTO</th>
                                        <td>$diaNascimentoUsuario/$mesNascimentoUsuario/$anoNascimentoUsuario</td>
                                    </tr>
                                    <tr>
                                        <th>CIDADE</th>
                                        <td>$cidadeUsuario</td>
                                    </tr>
                                    <tr>
                                        <th>TELEFONE</th>
                                        <td>$telefoneUsuario</td>
                                    </tr>
                                    <tr>
                                        <th>EMAIL</th>
                                        <td>$emailUsuario</td>
                                    </tr>  


                                    <!-- 
                                            Não precissa aparecer!!!
                                            <tr>
                                                <th>SENHA</th>
                                                <td>$senhaUsuario</td>
                                            </tr>
                                            <tr>
                                                <th>CONFIRMA SENHA</th>
                                                <td>$confirmarSenhaUsuario</td>
                                            </tr>
                                    -->


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