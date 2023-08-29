<?php
    if(isset($url[1])){
        if(isset($_POST['action'])){
            if($_POST['action'] == "reg"){
                $acesso = new Acesso;
                $id = $acesso->registrar('franq');
                $franquia = new Franquia;
                $franquia->registrar($id);
                echo "<script>window.location='".URL_ADMIN."/franquia';</script>";
            }
            if($_POST['action'] == "editFranq"){
                $acesso = new Acesso;
                $acesso->atualizar($_POST['id'],$_POST['login'],$_POST['senha']);
            }
        }
        switch($url[1]){
            case "adicionar":
                $TplView->addFile("INCLUDE_PG", "view/franquiaAdicionar.html");
                break;
            case "edit":
                $TplView->addFile("INCLUDE_PG", "view/franquiaEditar.html");
                $franquia = new Franquia;
                $franq = $franquia->buscar($url[2]);
                $TplView->nome = $franq[0]['nome'];
                $TplView->razao = $franq[0]['razao'];
                $TplView->cnpj = $franq[0]['cnpj'];
                $TplView->endereco = $franq[0]['endereco'];
                $TplView->cidade = $franq[0]['cidade'];
                $TplView->estado = '';
                $TplView->responsavel = $franq[0]['responsavel'];
                $TplView->contato = $franq[0]['contato'];
                $TplView->celular = $franq[0]['celular'];
                $TplView->qtAcesso = $franq[0]['qtAcesso'];
                $acesso = new Acesso;
                $acess = $acesso->buscar($franq[0]['codAcesso']);
                $TplView->login = $acess[0]['login'];
                $TplView->id = $acess[0]['id'];
                
                break;
            case "pendencia":
                $franq = new Franquia;
                $franq->alteraPendencia($url[2]);
                echo "<script>window.location='".URL_ADMIN."/franquia';</script>";
                break;
        }
    }else{
        $TplView->addFile("INCLUDE_PG", "view/franquia.html");
        $franquia = new Franquia;
        $i = 0;
        if($franquia->listar()){
            foreach($franquia->listar() as $franq){
                $acesso = new Acesso;
                $acess = $acesso->buscar($franq['codAcesso']);
                $TplView->numb = $i;
                $TplView->id = $franq['id'];
                $TplView->franquia = $franq['razao'];
                $TplView->nome = $franq['nome'];
                $TplView->contato = $franq['contato'];
                $TplView->celular = $franq['celular'];
                $TplView->login = $acess['0']['login'];
                if($acess['0']['ativo'] == "S"){
                    $TplView->view = "fa-user";
                }else{
                    $TplView->view = "fa-user-slash";
                }
                if($franq['pendencia'] == "S"){
                    $TplView->pendencia = "fa-circle red";
                }else{
                    $TplView->pendencia = "fa-circle green";
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
    }
?>