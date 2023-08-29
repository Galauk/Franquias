<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('upload-max-filesize', '20M');
ini_set('post_max_size', '20M');
error_reporting(E_ALL);
require ("../class/config.php");
require ("../class/Template.php");

//Verifica o retorno do login de acesso
if(isset($_POST['login']) && isset($_POST['senha']) && isset($_POST['action'])){
    if($_POST['action'] == "login"){
        $acesso = new Acesso;
        if($acesso->logar($_POST['login'],$_POST['senha'])){
            $_SESSION['acessoNome'] = $acesso->getNome();
            $_SESSION['acessoTipo'] = $acesso->getTipo();
            $_SESSION['codigo']     = $acesso->getCodigo();
            $_SESSION['lastLogin']  = $acesso->getLastLogin();
        }
    }
}

//Verifica se existe session do acesso
if(isset($_SESSION['acessoNome']) && isset($_SESSION['acessoTipo'])){
	$acesso = new Acesso();
	$logado = $acesso->verifica($_SESSION['acessoNome'], $_SESSION['acessoTipo']);
}else{
    echo "<script>window.location='".URL_ADMIN."/login.php';</script>";
    exit();
}

//
$TplView = new Template("view/index.html");

$banco = new Banco;

if(isset($_SESSION['acessoTipo'])){
    switch($_SESSION['acessoTipo']){
        case "admin":
            $TplView->block('ADMIN');
            break;
        case "franq":
            $TplView->block('FRANQ');
            $banco->read("franquia",array('codAcesso'=>$_SESSION['codigo']));
            $franquia = $banco->getResult();
            if($franquia[0]['pendencia'] == 'S'){
                $TplView->block('PENDENCIAS');
            }
            break;
        case "vend":
            $TplView->block('VEND');
            $vend = new Vendedor;
            $vendedor = $vend->buscarVendedor($_SESSION['codigo']);
            $banco->read("franquia",array('id'=>$vendedor[0]['codFranquia']));
            $franquia = $banco->getResult();
            if($franquia[0]['pendencia'] == 'S'){
                $TplView->block('PENDENCIAS');
            }
        break;

        default:

        break;
    }
}

if(isset($_GET['Secao'])) {
    $Sec = $_GET['Secao'];
    $url = explode('/', $_GET['Secao']);
}else{
    $Sec = "";
    $url[0] = "";
}

if(($url[0] == "") || ($url[0] == "Inicial")) {
    require ("controller/principal.php");
} else {
    require ("controller/secao.php");
}

//$TplView->URL_BASE = URL_BASE;
$TplView->URL_ADMIN = URL_ADMIN;
$TplView->NOME_PROJETO = NOME_PROJETO;
$nome = explode(' ',rtrim($_SESSION['acessoNome']));
$TplView->USER = $nome[0];
switch($_SESSION['acessoTipo']){
    case "admin":
        $TplView->NIVEL = "Administrador";
        break;
        case "franq":
        $TplView->NIVEL = "Franqueado";
        break;
    default:
        $TplView->NIVEL = "Vendedor";
        break;
}
if(isset($_SESSION['lastLogin'])){
    $data = new DateTime($_SESSION['lastLogin']);
}else{
    $data = new DateTime();
}
$TplView->lastLogin = $data->format("d/m/Y");


$TplView->show();
?>