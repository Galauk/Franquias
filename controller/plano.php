<?php
    if(isset($_POST['action'])){
        switch($_POST['action']){
            case "regPlan":
                $planos = new Plano;
                $planos->registrar($_POST); 
                $TplView->sucesso = "<div class='sucess'>Registro cadastrado com sucesso.</div>";
                break;
            case "atuPlan":
                $planos = new Plano;
                $planos->atualizar($_POST);
                $TplView->sucesso = "<div class='sucess'>Registro atualizado com sucesso.</div>";
                break;
        }
    }
    if(isset($url[1])){
        switch($url[1]){
            case "adicionar":
                $TplView->addFile("INCLUDE_PG", "view/planoAdicionar.html");
                $produtos = new Produto;
                foreach($produtos->listar() as $produto){
                    $TplView->block('REPETEPRODUTO');
                    $TplView->produto = $produto['nome'];
                    $TplView->produtoValue = $produto['id'];
                }
                break;
            case "editar":
                $TplView->addFile("INCLUDE_PG", "view/plano.html");
                $produtos = new Produto;
                $planos = new Plano;
                $plano = $planos->buscar($url[2]);
                foreach($produtos->listar() as $produto){
                    $TplView->block('REPETEPRODUTO');
                    $TplView->produto = $produto['nome'];
                    $TplView->produtoValue = $produto['id'];
                    $TplView->select = ($plano[0]['produto'] == $produto['id'])? 'selected' : '';
                }
                $TplView->nome = $plano[0]['nome'];
                $TplView->identificador = $plano[0]['identificador'];
                $TplView->codigo = $plano[0]['codigo'];
                $TplView->valor = $plano[0]['valor'];
                $TplView->comissao = $plano[0]['comissao'];
                $TplView->renovacao = $plano[0]['renovacao'];

                if(isset($url[2])){
                    $TplView->id = $url[2];
                    $TplView->block("IDPLANO");
                }
                $TplView->block("EDITA_PLANO");
                break;
        }
    }else{
        $TplView->addFile("INCLUDE_PG", "view/plano.html");
        $plano = new Plano;
        $i = 0;
        if($plano->listar()){
            foreach($plano->listar() as $planos){
                $produto = new Produto;
                $produtos = $produto->buscarId($planos['produto']);
                $TplView->produto = $produtos[0]['nome'];
                $TplView->id = $planos['id'];
                $TplView->nome = $planos['nome'];
                $TplView->identificador = $planos['identificador'];
                $TplView->valor = $planos['valor'];
                $TplView->comissao = $planos['comissao'];
                $TplView->renovacao = $planos['renovacao'];
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
        $TplView->block("LISTA_PLANO");
    }
?>