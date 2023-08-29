<?php



if ($url[0] == "Inicial")                       {include "principal.php";}
elseif ($url[0] == "produto" && $_SESSION['acessoTipo'] == 'admin'){
    include "produto.php";
}elseif ($url[0] == "plano" && $_SESSION['acessoTipo'] == 'admin'){
    include "plano.php";
}elseif ($url[0] == "franquia" && $_SESSION['acessoTipo'] == 'admin'){
    include "franquia.php";
}elseif ($url[0] == "vendedor" && ($_SESSION['acessoTipo'] == 'admin' || $_SESSION['acessoTipo'] == 'franq')){
    include "vendedor.php";
}elseif ($url[0] == "fatura")                    {include "fatura.php";
}elseif ($url[0] == "compra")                    {include "compra.php";
}elseif ($url[0] == "treinamento")               {include "treinamento.php";
}elseif ($url[0] == "avisos" && $_SESSION['acessoTipo'] == 'admin'){
    include "avisos.php";
}elseif ($url[0] == "arquivo")                   {include "arquivo.php";
}elseif ($url[0] == "relatorio" && $_SESSION['acessoTipo'] == 'admin'){
    include "relatorio.php";
}elseif ($url[0] == "comissao" && $_SESSION['acessoTipo'] == 'admin'){
    include "comissao.php";
}else {
    echo "<script>window.location='".URL_ADMIN."';</script>";
    exit();
}
?>