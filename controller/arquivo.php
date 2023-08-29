<?php
    if(isset($url[1])){
        if($url[1]=='del'){
            $arquivo = new Arquivo;
            $arquivo->remover($url[2]);
        }
    }
    if(isset($_POST['action'])){
        switch($_POST['action']){
            case "regCat": 
                $categoria = new Categoria;
                if(isset($_POST['categoria'])){
                    $categoria->registrar($_POST['categoria']);
                }
                break;
            case "regArq": 
                if(isset($_FILES['arquivo'])){
                    date_default_timezone_set("Brazil/East"); //Definindo timezone padrão

                    $ext = strtolower(substr($_FILES['arquivo']['name'],-4)); //Pegando extensão do arquivo
                    $new_name = date("Y.m.d.H.i.s") . $ext; //Definindo um novo nome para o arquivo
                    $dir = '../administrar/uploads/'; //Diretório para uploads
                    $uploadName = $dir.$new_name;

                    move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadName); //Fazer upload do arquivo
                }else{
                    $new_name = "";
                }
                $arquivo = new Arquivo;
                $arquivo->registrar($_POST,$new_name);
                break;
        }
    }
    switch($_SESSION['acessoTipo']){
        case "admin":
            $TplView->addFile("INCLUDE_PG", "view/arquivoAdicionar.html");
            $categoria = new Categoria;
            $categorias = $categoria->listar();
            foreach($categorias as $array){
                $TplView->categoriaid = $array['id'];
                $TplView->categorianome = $array['nome'];
                $TplView->block("SELECTCATEGORIA");
            }
            $TplView->addFile("MATERIAL", "view/arquivo.html");
            break;
        default :
           $TplView->addFile("INCLUDE_PG", "view/arquivo.html");
           break;
    }
    $categoria = new Categoria;
    $categorias = $categoria->listar();
    foreach($categorias as $cat){
        $TplView->categorias = $cat['nome'];
        $arquivo = new Arquivo;
        $arquivos = $arquivo->listarPorCategoria($cat['id']);
        foreach($arquivos as $files){
            $TplView->id = $files['id'];
            if($_SESSION['acessoTipo'] == "admin"){
                $TplView->block("UNLINKAR"); 
            }
            $TplView->arquivo = $files['nome'];
            $TplView->link = $files['arquivo'];
            $TplView->block("ARQUIVOS");
        }
        $TplView->block("CATEGORIAS");
    }

?>