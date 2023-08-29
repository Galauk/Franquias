<?php
    $TplView->addFile("INCLUDE_PG", "view/treinamento.html");
    $treinamento = new Treinamento;
    foreach($treinamento->listar() as $treina){
        $TplView->nome = $treina['nome'];
        $TplView->video = $treina['video'];
        $TplView->block("REPETE");
    }
?>