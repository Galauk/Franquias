<?php
    if(isset($_POST['action'])){
        switch($_POST['action']){
            case "reg":
                $aviso = new Aviso;
                $aviso->registrar();
            break;
        }
    }


    $TplView->addFile("INCLUDE_PG", "view/avisosInsere.html");

    $aviso = new Aviso;
    $historico = null;
    foreach($aviso->listar() as $avisos){
        $data = new DateTime($avisos['data']);
        if(strripos($avisos['mensagem'],'[video]') === false){
            $historico.= "(".$data->format("d/m/Y H:i:s").")".$avisos['nome'].":".$avisos['mensagem']."
";
        }else{
            $historico.= "(".$data->format("d/m/Y H:i:s").")".$avisos['nome'].":".$avisos['mensagem']."
";
        }
    }

    $TplView->historico = $historico;
    $TplView->nome = $_SESSION['acessoNome'];
?>