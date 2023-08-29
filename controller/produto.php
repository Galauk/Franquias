<?php
    if(isset($url[1])){
        $TplView->addFile("INCLUDE_PG", "view/produtoAdicionar.html");
    }else{
        $TplView->addFile("INCLUDE_PG", "view/produto.html");
        $produto = new Produto;
        $i = 0;
        if($produto->listar()){
            foreach($produto->listar() as $prod){
                $TplView->nome = $prod['nome'];
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