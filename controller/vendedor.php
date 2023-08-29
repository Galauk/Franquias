<?php
    if(isset($url[1])){
        switch($url[1]){
            case "alterar":
                $vendedor = new Vendedor;
                $vend = $vendedor->buscar($url[2]);
                $acesso = new Acesso;
                $acesso->alteraAtivo($vend['0']['codAcesso']);
                echo "<script>window.location='".URL_ADMIN."/vendedor';</script>";
                break;
        }
        if(isset($_POST['action'])){
            switch($_POST['action']){
                case "reg":
                    $acesso = new Acesso;
                    $id = $acesso->registrar('vend');
                    $vendedor = new Vendedor;
                    $vendedor->registrar($id);
                    echo "<script>window.location='".URL_ADMIN."/vendedor';</script>";
                    break;
                case "edit":
                    $acesso = new Acesso;
                    $acesso->atualizar($_POST['id'],$_POST['login'],$_POST['senha']);
                    echo "<script>window.location='".URL_ADMIN."/vendedor';</script>";
                    break;
            }
        }

        switch($url[1]){
            case "adicionar":
                $TplView->addFile("INCLUDE_PG", "view/vendedorAdicionar.html");
                if($_SESSION['acessoTipo'] == 'admin'){
                    $franquia = new Franquia;
                    foreach($franquia->listar() as $franq){
                        $TplView->id = $franq['id'];
                        $TplView->franquia = $franq['nome'];
                        $TplView->block("FOREACH");
                    }
                }else{
                    $franquia = new Franquia;
                    $franq = $franquia->buscarFranquia($_SESSION['codigo']); 
                    $TplView->id = $franq[0]['id'];
                    $TplView->franquia = $franq[0]['nome'];
                    $TplView->block("FOREACH");
                }
                break;
            case "editar":
                $TplView->addFile("INCLUDE_PG", "view/vendedorEdita.html");
                $vendedor = new Vendedor;
                $vend = $vendedor->buscar($url[2]);
                $franquia = new Franquia;
                $franq = $franquia->buscar($vend['0']['codFranquia']);
                $acesso = new Acesso;
                $acess = $acesso->buscar($vend['0']['codAcesso']);
                $TplView->franquia = $franq['0']['nome'];
                $TplView->nome = $vend['0']['nome'];
                $TplView->cpf = $vend['0']['cpf'];
                $TplView->email = $vend['0']['email'];
                $TplView->login = $acess['0']['login'];
                $TplView->id = $acess['0']['id'];
                break;
        }
    }else{
        $TplView->addFile("INCLUDE_PG", "view/vendedor.html");
        $vendedor = new Vendedor;
        $i = 0;

        switch($_SESSION['acessoTipo']){
            case"franq":
                $franquia = new Franquia;
                $franq = $franquia->buscarFranquia($_SESSION['codigo']);
                if($vendedor->listarFranquia($franq[0]['id'])){
                    foreach($vendedor->listarFranquia($franq[0]['id']) as $vend){
                        $franquia = new Franquia;
                        $franq = $franquia->buscar($vend['codFranquia']);
                        $acesso = new Acesso;
                        $acess = $acesso->buscar($vend['codAcesso']);
                        $TplView->codigo = $vend['id'];
                        $TplView->franquia = $franq['0']['nome'];
                        $TplView->nome = $vend['nome'];
                        $TplView->email = $vend['email'];
                        $TplView->login = $acess['0']['login'];
                        if($acess['0']['ativo'] == "S"){
                            $TplView->view = "fa-eye";
                        }else{
                            $TplView->view = "fa-eye-slash";
                        }
                        if($i%2 == '0'){
                            $TplView->css = "";
                        }else{
                            $TplView->css = "alt";
                        }
                        $i++;
                        $TplView->block("FOREACH");
                    }
                }else {
                    $TplView->block("VAZIO");
                }
                break;
            default:
                if($vendedor->listar()){
                    foreach($vendedor->listar() as $vend){
                        $franquia = new Franquia;
                        $franq = $franquia->buscar($vend['codFranquia']);
                        $acesso = new Acesso;
                        $acess = $acesso->buscar($vend['codAcesso']);
                        $TplView->codigo = $vend['id'];
                        $TplView->franquia = $franq['0']['nome'];
                        $TplView->nome = $vend['nome'];
                        $TplView->email = $vend['email'];
                        $TplView->login = $acess['0']['login'];
                        if($acess['0']['ativo'] == "S"){
                            $TplView->view = "fa-user";
                        }else{
                            $TplView->view = "fa-user-slash";
                        }
                        if($i%2 == '0'){
                            $TplView->css = "";
                        }else{
                            $TplView->css = "alt";
                        }
                        $i++;
                        $TplView->block("FOREACH");
                    }
                }else {
                    $TplView->block("VAZIO");
                }
                break;
        }
    }
?>