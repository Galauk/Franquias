<?php
    function geraRand(){
        return chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90));
    }

    if(isset($url[1])){
        //Verifica se foi passado informação de formulario
        if(isset($_POST['action'])){
            switch($_POST['action']){
                case "regVend":
                    $compra = new Compra;
                    $compra->aplicarVendedor($_POST['compra'],$_POST['vendedor']);
                    break;
                case "regObs": 
                    if(!empty($_POST['obs'])){
                        $compra = new Compra;
                        $comp = $compra->buscar($_POST['compra']);

                        $obs = new Observacoes;
                        $obs->registrar($_SESSION['acessoNome'],$_POST['obs'],$comp['0']['cod_assinatura']);
                    }
                    break;
                case "atuReno":
                    if(!empty($_POST['renovacao'])){
                        $compras = new Compra;
                        $compras->aplicarRenovacao($_POST['compra'],$_POST['renovacao']);

                    }
                case "regComp":
                    if(!empty($_POST['nome']) && !empty($_POST['cpf'])){
                        $clientes = new Cliente;
                        $cliente = $clientes->buscarCpf($_POST['cpf']);
                        if(isset($cliente[0])){
                            $codCliente = $cliente[0]['cod_cliente'];
                        }else{
                            $codCliente = geraRand();
                            $clienteA = array(
                                'nome' => $_POST['nome'],
                                'cpf_cnpj' => $_POST['cpf'],
                                'celular' => $_POST['celular'],
                                'email' => $_POST['email'],
                                'cep' => $_POST['cep'],
                                'endereco' => $_POST['endereco'],
                                'bairro' => $_POST['bairro'],
                                'numero' => $_POST['numero'],
                                'cidade' => $_POST['cidade'],
                                'estado' => $_POST['estado']
                            );
                            $clientes->registrar($clienteA);
                        }
                        $comissao = (($_POST['valor']/100)*40);
                        $array = array(
                            'cod_assinatura'=> geraRand(),
                            'cod_cliente'=> $codCliente,
                            'cod_fatura'=> geraRand(),
                            'plano'=> $_POST['plano'],
                            'metodo'=> $_POST['metodo'],
                            'origem_compra'=> 'painelFranqueado',
                            'valor'=> $_POST['valor'],
                            'renovacao'=> 1,
                            'status_pgto'=> 'paid',
                            'status_ass'=> 0,
                            'revendedor'=> 0,
                            'cod_vendedor'=> 0,
                            'dias_adicionados'=> 2,
                            'comprovante'=> '',
                            'ativo'=> 1,
                            'valor_comissao'=> $comissao,
                            'data' => date("Y-m-d H:i:s")
                        );
                        $compras = new Compra;
                        $compras->registrar($array);
                    }
                    break;
                case "atuReno":
                    
                    break;
                case "ExpComp":
                    $gatilho = new Gatilho;
                    $observacao = new Observacoes;
                    break;
            }
        }
        switch($url[1]){
            case "adicionar":
                $TplView->addFile("INCLUDE_PG", "view/compraAdicionar.html");
                break;

            case "expi":
                $TplView->addFile("INCLUDE_PG", "view/compraExpirar.html");
                $TplView->codigo = $url[2];
                break;
            case "dados":
                $TplView->addFile("INCLUDE_PG","view/compraDados.html");

                $compra = new Compra;
                if(isset($url[2]) && $compra->buscar($url[2])){
                    //busca qual acompra para detalhes 
                    $comp = $compra->buscar($url[2]);

                    //Verifica qual o site do produto
                    switch($comp[0]['origem_compra']){
                        case "":
                            $link = "https://www.midiagram.com.br/checkout/completar_cadastro/";
                            break;
                        case "otimizaragora":
                            $link = "https://www.otimizaragora.com.br/checkout/completar_cadastro/";
                            break;
                        default:
                            $link = "https://www.midiagram.com.br/checkout/completar_cadastro/";
                            break;
                    }
                    
                    //busca informações do cliente
                    $cliente = new Cliente;
                    $client = $cliente->buscar($comp[0]['cod_cliente']);

                    //apresenta as variaveis para o corpo
                    $TplView->cliente = $client[0]['nome'];
                    $TplView->celular = $client[0]['celular'];
                    $TplView->email = $client[0]['email'];
                    $TplView->cpf = $client[0]['cpf_cnpj'];
                    $TplView->endereco = $client[0]['endereco'];
                    $TplView->metodo = $compra->convert($comp[0]['metodo'],'metodo');
                    $TplView->status = $compra->convert($comp[0]['status_pgto'],'status_pgto');
                    $TplView->data = $compra->convert($comp[0]['data'],'data');
                    $TplView->url = $link.base64_encode(base64_encode(base64_encode($comp['0']['cod_assinatura'])));
                    $ig = new Iugut;
                    $fatura = $ig->buscaFatura($comp[0]['cod_fatura']);
                    if(isset($fatura->errors)){
                        $ig->setToken(IUGU_MIDIAGRAM);
                        $ig->setId(IUGU_MIDIAGRAM_ID);
                        $fatura = $ig->buscaFatura($comp[0]['cod_fatura']);
                    }
                    if(isset($fatura->errors)){
                        $TplView->fatura = "Fatura indisponivel";
                    }else{
                        $TplView->fatura = "https://faturas.iugu.com/".$fatura->secure_id;
                    }

                    $TplView->valor = $compra->convert($comp['0']['valor'],'valor');

                    $i = 0;
                    $observacao = new Observacoes;
                    foreach($observacao->buscar($comp[0]['cod_assinatura']) as $observa){
                        if($i > 0){
                            $TplView->hr = "<hr>";
                        }else{
                            $TplView->hr = "";
                        }
                        $data_publicado = new DateTime($observa['data']);
                        $TplView->data = $data_publicado->format("d/m/Y - H:i:s");
                        $TplView->postador = $observa['nome_postador'];
                        $TplView->mensagem = $observa['observacao'];
                        $TplView->block("OBSERVACAOREPETE");
                        $i++;
                    }

                    $TplView->id = $url[2];
                }
                break;
            case "edit":
                $TplView->addFile("INCLUDE_PG","view/compraEdita.html");
                $compra = new Compra;
                if(isset($url[2]) && $compra->buscar($url[2])){
                    //busca qual acompra para detalhes 
                    $comp = $compra->buscar($url[2]);

                    //Verifica qual o site do produto
                    switch($comp[0]['origem_compra']){
                        case "":
                            $link = "https://www.midiagram.com.br/checkout/completar_cadastro/";
                            break;
                        case "otimizaragora":
                            $link = "https://www.otimizaragora.com.br/checkout/completar_cadastro/";
                            break;
                        default:
                            $link = "https://www.midiagram.com.br/checkout/completar_cadastro/";
                            break;
                    }
                    $vendedor = new Vendedor;
                    foreach($vendedor->listar() as $vended){
                        $TplView->idVendedor = $vended['id'];
                        $TplView->nomeVendedor = $vended['id'].' - '.$vended['nome'];
                        $TplView->block("SELECTVENDE");
                    }
                    
                    //busca informações do cliente
                    $cliente = new Cliente;
                    $client = $cliente->buscar($comp[0]['cod_cliente']);

                    //apresenta as variaveis para o corpo
                    $TplView->cliente = $client[0]['nome'];
                    $TplView->celular = $client[0]['celular'];
                    $TplView->email = $client[0]['email'];
                    $TplView->cpf = $client[0]['cpf_cnpj'];
                    $TplView->endereco = $client[0]['endereco'];
                    $TplView->metodo = $compra->convert($comp[0]['metodo'],'metodo');
                    $TplView->status = $compra->convert($comp[0]['status_pgto'],'status_pgto');
                    $TplView->data = $compra->convert($comp[0]['data'],'data');
                    $TplView->url = $link.base64_encode(base64_encode(base64_encode($comp['0']['cod_assinatura'])));
                    $ig = new Iugut;
                    $fatura = $ig->buscaFatura($comp[0]['cod_fatura']);
                    if(isset($fatura->errors)){
                        $ig->setToken(IUGU_MIDIAGRAM);
                        $ig->setId(IUGU_MIDIAGRAM_ID);
                        $fatura = $ig->buscaFatura($comp[0]['cod_fatura']);
                    }
                    if(isset($fatura->errors)){
                        $TplView->fatura = "Fatura indisponivel";
                    }else{
                        $TplView->fatura = "https://faturas.iugu.com/".$fatura->secure_id;
                    }
                    $TplView->valor = $compra->convert($comp['0']['valor'],'valor');

                    $i = 0;
                    $observacao = new Observacoes;
                    foreach($observacao->buscar($comp[0]['cod_assinatura']) as $observa){
                        if($i > 0){
                            $TplView->hr = "<hr>";
                        }else{
                            $TplView->hr = "";
                        }
                        $data_publicado = new DateTime($observa['data']);
                        $TplView->data = $data_publicado->format("d/m/Y - H:i:s");
                        $TplView->postador = $observa['nome_postador'];
                        $TplView->mensagem = $observa['observacao'];
                        $TplView->block("OBSERVACAOREPETE");
                        $i++;
                    }

                    $TplView->id = $url[2];
                }
                break;
        }
    }else{
        
    
        $TplView->addFile("INCLUDE_PG", "view/compra.html");
        $compra = new Compra;
        $i = 0;

        if($_SESSION['acessoTipo'] == "admin"){
            $TplView->block("ADICIONAVENDA");
        }


        verificaFiltro($_POST,$_SESSION,$TplView);

        if(isset($_POST['datainicio'])){
            $TplView->datainicio = $_POST['datainicio'];
        }else{
            $hoje = new DateTime();
            $TplView->datainicio = $hoje->format("Y-m-d");
        }
        if(isset($_POST['datafim'])){
            $TplView->datafim = $_POST['datafim'];
        }else{
            $TplView->datafim = "";
        }

    }

    function montaFiltros($TplView,$accountType,$accountId){
        $TplView->block("FILTRODATA");

        //Faz leitura de todos os Vendedores
        $vendedor = new Vendedor;

        $TplView->idVend = '';
        $TplView->nomeVend = "Todos";
        $TplView->block("SELECTVEND");

        if($accountType == 'admin' || $accountType == 'franq'){
            if($accountType == 'admin'){
                foreach($vendedor->listar() as $vended){
                    $TplView->idVend = $vended['id'];
                    $TplView->nomeVend = $vended['nome'];
                    $TplView->block("SELECTVEND");
                    $vendedores[$vended['id']] = $vended['nome'];
                    $vendedoresFranquia[$vended['id']] = $vended['codFranquia'];
                }
            }else{
                $franquia = new Franquia;
                foreach($franquia->listar() as $franq){
                    if($franq['codAcesso'] == $accountId){
                       $idFranquia = $franq['id']; 
                    }
                }
                foreach($vendedor->listarFranquia($idFranquia) as $vended){
                    $TplView->idVend = $vended['id'];
                    $TplView->nomeVend = $vended['nome'];
                    $TplView->block("SELECTVEND");
                    $vendedores[$vended['id']] = $vended['nome'];
                    $vendedoresFranquia[$vended['id']] = $vended['codFranquia'];
                }
            }
        }
        if($accountType == 'vend'){
            foreach($vendedor->buscarVendedor($accountId) as $vended){
                $TplView->idVend = $vended['id'];
                $TplView->nomeVend = $vended['nome'];
                $TplView->block("SELECTVEND");
                $vendedores[$vended['id']] = $vended['nome'];
                $vendedoresFranquia[$vended['id']] = $vended['codFranquia'];
            }
        }

        //Faz leitura de todas as franquias
        $franquia = new Franquia;

        $TplView->idFranq = '';
        $TplView->nomeFranq = "Todos";
        $TplView->block("SELECTFRANQ");

        if($accountType == 'admin'){
            foreach($franquia->listar() as $franq){
                $TplView->idFranq = $franq['id'];
                $TplView->nomeFranq = $franq['nome'];
                $TplView->block("SELECTFRANQ");
                $franquias[$franq['id']] = $franq['nome']; 
            }
        }
        if($accountType == 'franq'){
            foreach($franquia->buscarFranquia($accountId) as $franq){
                $TplView->idFranq = $franq['id'];
                $TplView->nomeFranq = $franq['nome'];
                $TplView->block("SELECTFRANQ");
            }
        }
        $planos = new Plano;
        foreach($planos->listar() as $plano){
            $TplView->plan = $plano['nome'];
            $TplView->planid = $plano['identificador'];
            $TplView->block("SELECT_PLAN_FILTER");
        }
        $TplView->block("FILTROPLAN");

        $TplView->block("FILTROPGTO");
        $TplView->block("FILTROCLIENTE");
        $TplView->block("FILTRORENOVACAO");
        $TplView->block("DATARENOVACAO");
    }

    function checaVariavel($var,$key){
        if(isset($var[$key])){
            return $var[$key];
        }else{
            return '';
        }
    }

    function verificaFiltro($post,$session,$TplView){
        $accountType = checaVariavel($session,'acessoTipo');
        $accountId = checaVariavel($session,'codigo');
        switch($accountType){  
            case "admin":
                $postFranquia= checaVariavel($post,'franquia');
                $postVendedor= checaVariavel($post,'vendedor');
                break;
            case "franq":
                $franquia = new Franquia;
                foreach($franquia->buscarFranquia($accountId) as $franq){
                    $idFranquia = $franq['id'];
                }
                $postFranquia=$idFranquia;
                $postVendedor=checaVariavel($post,'vendedor');
                break;
            case "vend":
                $vendedor = new Vendedor;
                foreach($vendedor->buscarVendedor($accountId) as $vend){
                    $idVendedor = $vend['id'];
                }
                $postFranquia=null;
                $postVendedor=$idVendedor;
                break;
        }
        $dados = verificaFiltroDados($postFranquia,$postVendedor,$post);
        $filtro = verificaFiltroWhere($postFranquia,$postVendedor,$post,$accountType,$accountId);
        
        montaFiltros($TplView,$accountType,$accountId);
        montaLista($filtro,$dados,$TplView,$accountType,$accountId);
    }

    function verificaFiltroWhere($postFranquia,$postVendedor,$post,$accountType,$accountId){

        $postPlano=checaVariavel($post,'plano');
        $postStatuspgto=checaVariavel($post,'statuspgto');
        $postCliente=checaVariavel($post,'cliente');
        $postDatainicio=checaVariavel($post,'datainicio');
        $postDatafim=checaVariavel($post,'datafim');
        $postRenovacao=checaVariavel($post,'renovacao');

        //Verificação dos filtros
        if(!empty($postFranquia)){
            $vendedor = new Vendedor;
            $listaVendedores = null;
            foreach($vendedor->listar() as $vend){
                if($vend['codFranquia'] == $postFranquia){
                    $listaVendedores[] = $vend['id'];
                }
            }
            if(!empty($postVendedor)){
                $where[] = "cod_vendedor = :vendedor ";
            }else{
                if(is_array($listaVendedores)){
                    $where[] = "cod_vendedor IN (".implode(",",$listaVendedores).") ";
                }else{
                    $where[] = "cod_vendedor IN (".$listaVendedores.") ";
                }
            }
        }

        //Filtro Renovação
        if(!empty($postRenovacao)){
            $where[] = "renovacao = :renovacao ";
        }

        //Filtro vendedor
        if(!empty($postVendedor) && empty($postFranquia)){
            $where[] = "cod_vendedor = :vendedor ";
        }

        //Filtlro produto
        if(!empty($postPlano)){
            $where[] = "plano LIKE :plano ";
        }

        //Filtro pot estado de pagamento
        if(!empty($postStatuspgto)){
            $where[] = "status_pgto LIKE :statuspgto ";
        }

        //filtro por cliente
        if(!empty($postCliente)){
            $cliente = new Cliente;
            $listaClientes = null;
            foreach($cliente->listarNome($postCliente) as $client){
                $listaClientes[] = $client['cod_cliente'];
            }
            if(is_array($listaClientes)){
                $where[] = "cod_cliente IN ('".implode("','",$listaClientes)."') ";
            }else{
                $where[] = "cod_cliente IN ('".$listaClientes."') ";
            }
        }

        //filtro por data
        if(isset($postDatainicio)){
            if(!empty($postDatainicio) && !empty($postDatafim)){
                $where[] = " data BETWEEN :datainicio AND :datafim ";
            }elseif(!empty($postDatainicio) && empty($postDatafim)) {
                $where[] = " data LIKE :datainicio ";
            }
        }

        //Monta where para execução 
        if(isset($where)){
            $filtro = "WHERE ".implode(" AND ",$where);
        }else{
            $filtro = "WHERE data LIKE :datainicio ";
        }
        return $filtro;

    }
    
    function verificaFiltroDados($postFranquia,$postVendedor,$post){

        $postPlano=checaVariavel($post,'plano');
        $postStatuspgto=checaVariavel($post,'statuspgto');
        $postCliente=checaVariavel($post,'cliente');
        $postDatainicio=checaVariavel($post,'datainicio');
        $postDatafim=checaVariavel($post,'datafim');
        $postRenovacao=checaVariavel($post,'renovacao');

        //Verificação dos filtros
        if(!empty($postFranquia)){
            if(!empty($postVendedor)){
                $dados['vendedor'] = $postVendedor;
            }
        }

        //Filtro Renovação
        if(!empty($postRenovacao)){
            $dados['renovacao'] = $postRenovacao;
        }

        //Filtro vendedor
        if(!empty($postVendedor) && empty($postFranquia)){
            $dados['vendedor'] = $postVendedor;
        }

        //Filtro plano
        if(!empty($postPlano)){
            $dados['plano'] = $postPlano;
        }

        //Filtro pot estado de pagamento
        if(!empty($postStatuspgto)){
            $dados['statuspgto'] = $postStatuspgto;
        }


        //filtro por data
        if(isset($postDatainicio)){
            if(!empty($postDatainicio) && !empty($postDatafim)){
                $dados['datainicio'] = $postDatainicio." 00:00:00";
                $dados['datafim'] = $postDatafim." 23:59:59";
            }elseif(!empty($postDatainicio) && empty($postDatafim)) {
                $dados['datainicio'] = $postDatainicio."%";
            }
        }

        if(!isset($dados)){
            $dados['datainicio'] = date("Y-m-d").'%';
        }

        return $dados;

    }

    function montaLista($filtro,$dados,$TplView,$accountType,$accountId){
        $i = 0;
        $total = 0;
        $compra = new Compra;
        $vendedor = new Vendedor;
        $franquia = new Franquia;
        foreach($vendedor->listar() as $vend){
            $vendedores[$vend['id']] = $vend['nome'];
            $franq = $franquia->buscar($vend['codFranquia']);
            $franquias[$vend['id']] = $franq['0']['nome'];
        }

        if($compra->listarFiltro($filtro,$dados)){
            foreach($compra->listarFiltro($filtro,$dados) as $comp){
                if($comp['cod_vendedor'] != '0' && isset($vendedores[$comp['cod_vendedor']])){
                    $TplView->franquia = $franquias[$comp['cod_vendedor']];
                }else{
                    $TplView->franquia = "Compra Direta";
                }
                if(isset($vendedores[$comp['cod_vendedor']])){
                    $TplView->vendedor = $vendedores[$comp['cod_vendedor']];
                }else{
                    $TplView->vendedor = 'Vendedor interno';
                }
                $planos = new Plano;
                $plano = $planos->buscarIdentificador($comp['plano']);
                $produtos = new Produto;
                if(!empty($plano[0]['produto'])){
                    $produto = $produtos->buscarId($plano[0]['produto']);
                    $TplView->produto =  $produto[0]['nome'].' - '.$plano[0]['nome'];
                }else{
                    $TplView->produto = ' - ';
                }
                $TplView->valor = $compra->convert($comp['valor'],"valor");
                $total = $total + $comp['valor'];

                $TplView->status = $compra->convert($comp['status_pgto'],'status_pgto'); //"<i class='fa fa-money-bill' style='color:green;' title='Pago'></i>";
                
                $TplView->data = $compra->convert($comp['data'],"data");
                $TplView->id = $comp['id'];

                $data = new DateTime($comp['data']);
                switch($comp['plano']){
                    case "mensal_plan":
                        $data->add(new DateInterval("P1M"));
                        break;
                    case "trimestral_plan":
                        $data->add(new DateInterval("P3M"));
                        break;
                    case "semestral_plan":
                        $data->add(new DateInterval("P6M"));
                        break;
                    case "anual_plan":
                        $data->add(new DateInterval("P1Y"));
                        break;
                    default:
                        $data = $comp['data'];
                }
                $hoje = new DateTime();
                if($data != $comp['data']){
                    $dias = $data->diff($hoje);
                    if($data > $hoje){
                        $TplView->restante = $dias->days;
                    }else{
                        $TplView->restante = "0";
                    }
                }else{
                    $TplView->restante = "-";
                }

                if($comp['renovacao'] == '1'){
                    $TplView->renovacao = "Não"; //"<i class='fa fa-times-circle' style='color:red;'></i>";
                }else{
                    $TplView->renovacao = "Sim"; // "<i class='fa fa-check-circle' style='color:green;'></i>";
                }

                $cliente = new Cliente;
                if($cliente->buscar($comp['cod_cliente'])){
                    $client = $cliente->buscar($comp['cod_cliente']);
                    $TplView->cliente = $client['0']['nome'];
                }else{
                    $TplView->cliente = "-";
                }
                if($i%2 == '0' ){
                    $TplView->css = "";
                }else{
                    $TplView->css = "alt";
                }
                $i++;
                if($accountType == "admin"){
                    $TplView->block("REPETE2");
                }else{
                    $TplView->block("REPETE");
                }
            }
            $TplView->total = number_format($total,2,',','.');
        }else{
            $TplView->block("VAZIO");
        }
    }
?>