<?php
    $TplView->addFile("INCLUDE_PG", "view/relatorio.html");
    $hoje = new DateTime;
    switch($hoje->format("m")){
        case "01":
            $TplView->m01 = "selected";
            break;
        case "02":
            $TplView->m02 = "selected";
            break;
        case "03":
            $TplView->m03 = "selected";
            break;
        case "04":
            $TplView->m04 = "selected";
            break;
        case "05":
            $TplView->m05 = "selected";
            break;
        case "06":
            $TplView->m06 = "selected";
            break;
        case "07":
            $TplView->m07 = "selected";
            break;
        case "08":
            $TplView->m08 = "selected";
            break;
        case "09":
            $TplView->m09 = "selected";
            break;
        case "10":
            $TplView->m10 = "selected";
            break;
        case "11":
            $TplView->m11 = "selected";
            break;
        case "12":
            $TplView->m12 = "selected";
            break;
    }
    if(isset($_POST['action'])){
        switch($_POST['action']){
            case "filtro1":
                $atual = new DateTime;
                $hoje = new DateTime($_POST['ano'].'-'.$_POST['mes'].'-'.$atual->format("d H:i:s"));
                break;
        }
    }

    $franquia = new Franquia;
    $i = 0;
    foreach($franquia->listar() as $franq){
        $TplView->id = $franq['id'];
        $TplView->numero = $i;
        $TplView->franquias = $franq['nome'];
        $TplView->block("SELECTFRANQUIAS");
        $i++;
    }

    $dataInicio = new DateTime($hoje->format("Y-m")."-29 00:00:00");
    $dataInicio->sub(new DateInterval('P1M'));
    $dataFim = new DateTime($hoje->format("Y-m")."-28 23:59:59");
    
    $TplView->dataInicio = $dataInicio->format("d/m/Y");
    $TplView->dataFim = $dataFim->format("d/m/Y");
    $TplView->block("FILTRO");

    $data = new DateTime;
    $TplView->datainicial = $data->format("d/m/Y");

    if(isset($_POST['action'])){
        switch($_POST['action']){
            case "vend":
                $vendedores = new Vendedor;
                $hoje = new DateTime;
                $dateBegin = (empty($_POST['datebegin']))? new DateTime($hoje->format("Y-m-d")." 00:00:00") : new DateTime($_POST['datebegin']." 00:00:00");
                $dateEnd = (empty($_POST['dateend']))? new DateTime($hoje->format("Y-m-d")." 23:59:59") : new DateTime($_POST['dateend']." 23:59:59");
                foreach($vendedores->listar() as $vendedor){
                    $compras = new Compra;
                    $valorTotal = 0;
                    $total = 0;
                    foreach($compras->listarVendedor($vendedor['id']) as $compra){
                        $data = new DateTime($compra['data']);
                        if($dateBegin < $data && $data < $dateEnd && $compra['status_pgto'] == 'paid'){
                            $valorTotal += $compra['valor'];
                            $total++;
                        }
                    }
                    $TplView->codigoVendedor = $vendedor['id'];
                    $TplView->nomeVendedor = $vendedor['nome'];
                    $TplView->totalVendas = $total;
                    $TplView->totalVendedor = number_format($valorTotal,'2',',','.');
                    $TplView->block("PORVENDEDORLIST");
                }
                $TplView->dateBegin = $dateBegin->format("d/m/Y");
                $TplView->dateEnd = $dateEnd->format("d/m/Y");
                $TplView->block("PORVENDEDOR");
                break;
            case "franq":
                if(isset($_POST['franq'])){
                    $hoje = new DateTime;
                    $dateBegin = (empty($_POST['datebegin']))? new DateTime($hoje->format('Y-m-d')." 00:00:00") : new DateTime($_POST['datebegin']." 00:00:00");
                    $dateEnd = (empty($_POST['dateend']))? new DateTime($hoje->format('Y-m-d')." 23:59:59") : new DateTime($_POST['dateend']." 23:59:59");
                    foreach($_POST['franq'] as $franquiaId){
                        //busca franquia a partir do id
                        $franquias = new Franquia;
                        $franquia = $franquias->buscar($franquiaId);
                        //busca valores de compra
                        $compras = new Compra;
                        $valorTotal = 0;
                        $valorComic = 0;
                        foreach($compras->listarFranquia($franquiaId) as $compra){
                            $data = new DateTime($compra['data']);
                            if($dateBegin < $data && $data < $dateEnd && $compra['status_pgto'] == 'paid'){
                                $valorTotal += $compra['valor'];
                                $valorComic += $compra['valor_comissao'];
                            }
                        }

                        $TplView->customName = $franquia[0]['nome'];
                        $TplView->customTotal = number_format($valorTotal,'2',',','.');
                        $TplView->customComic = $franquia[0]['nome'];
                        $TplView->customRoyal = number_format(($valorTotal/100),'2',',','.');
                        $TplView->block("FRANQUIACUSTOMLIST");
                    }
                    $TplView->block("FRANQUIACUSTOM");
                }
                break;
            case "filtro1":
                $franquias = new Franquia;
                foreach($franquias->listar() as $franquia){
                    $venda = 0;
                    $comissao = 0;
                    $royalties = 0;
                    $TplView->franquiaNome = $franquia['nome'];
                    $compras = new Compra;
                    foreach($compras->listarFranquia($franquia['id']) as $compra){
                        $data = new DateTime($compra['data']);
                        if($compra['status_pgto'] == 'paid' && $data >= $dataInicio && $data <= $dataFim){
                            $clientes = new Cliente;
                            $cliente = $clientes->buscar($compra['cod_cliente']);
                            if(isset($cliente['0'])){
                                $TplView->cliente = $cliente['0']['nome'];
                            }else{
                                $TplView->cliente = "-";
                            }
                            $planos = new Plano;
                            $plano = $planos->buscarIdentificador($compra['plano']);
                            $produtos = new Produto;
                            if(isset($plano[0])){
                                $produto = $produtos->buscarId($plano['0']['produto']);
                                $TplView->produto = $produto[0]['nome'];
                            }else{
                                $TplView->produto = "Não encontrado";
                            }

                            $data = new DateTime($compra['data']);

                            $TplView->data = $data->format("d/m/Y H:i:s");
                            
                            $TplView->valor = "R$".number_format($compra['valor'],2,',','.');
                            $venda += (float)$compra['valor'];
                            
                            $TplView->renovacao = ($compra['renovacao'] == 1)? "Não" : "Sim";

                            $TplView->comissao = (is_null($compra['valor_comissao']))? "R$"."0.00" : "R$".number_format($compra['valor_comissao'],2,',','.') ;
                            $comissao += (float)(is_null($compra['valor_comissao']))? 0 : $compra['valor_comissao'] ;

                            $TplView->royalties = "R$".number_format(($compra['valor']/100),2,',','.');
                            $royalties += (float)number_format(($compra['valor']/100),2);

                            $TplView->block("COMPRA_REPETE");
                        }
                    }
                    if($venda == 0){
                        $TplView->block("VAZIO");
                    }
                    $TplView->totalVenda = "R$ ".number_format($venda,2,',','.');
                    $TplView->totalComissao = "R$ ".number_format($comissao,2,',','.');
                    $TplView->totalRoyalties = "R$ ".number_format($royalties,2,',','.');

                    $TplView->block("FRANQUIA_REPETE");
                }
                $TplView->block("PORMES");
                break;
            case "filtro2":
                $franquias = new Franquia;
                $compras = new Compra;
                $i = 0;
                $totalHoje  = "0.00";
                $totalOntem = "0.00";
                $totalAnteOntem = "0.00";
                $totalMesAtual = "0.00";
                $totalMesAnterior = "0.00";
                $totalAnual = "0.00";
                foreach($franquias->listar() as $franquia){
                    $valorHoje = "0.00";
                    $valorOntem = "0.00";
                    $valorAnteOntem = "0.00";
                    $valorMesAtual = "0.00";
                    $valorMesAnterior = "0.00";
                    $valorAnual = "0.00";
                    foreach($compras->listarFranquia($franquia['id']) as $compra){
                        $hoje = new DateTime;
                        $dataCompra = new DateTime($compra['data']);
                        if($compra['status_pgto'] == "paid"){
                            if($dataCompra->format("Y-m-d") == $hoje->format("Y-m-d")){
                                $valorHoje += $compra['valor'];
                            }

                            if($hoje->format("d") >'28'){
                                $hoje->add(new DateInterval("P1M"));
                            }
                            $dataInicio = new DateTime($hoje->format("Y-").($hoje->format("m")-1)."-29 "."00:00:00");
                            $dataFim = new DateTime($hoje->format("Y-").$hoje->format("m")."-28 "."23:59:59");
                            if($dataInicio < $dataCompra && $dataCompra < $dataFim){
                                $valorMesAtual += $compra['valor'];
                            }
                            
                            $hoje = new DateTime;
                            $hoje->sub(new DateInterval("P1D"));
                            if($dataCompra->format("Y-m-d") == $hoje->format("Y-m-d")){
                                $valorOntem += $compra['valor'];
                            }
                            $hoje->sub(new DateInterval("P1D"));
                            if($dataCompra->format("Y-m-d") == $hoje->format("Y-m-d")){
                                $valorAnteOntem += $compra['valor'];
                            }

                            $hoje = new DateTime;
                            $hoje->sub(new DateInterval("P1M"));

                            if($hoje->format("d") >'28'){
                                $hoje->add(new DateInterval("P1M"));
                            }
                            $dataInicio = new DateTime($hoje->format("Y-").($hoje->format("m")-1)."-29 "."00:00:00");
                            $dataFim = new DateTime($hoje->format("Y-").$hoje->format("m")."-28 "."23:59:59");
                            if($dataInicio < $dataCompra && $dataCompra < $dataFim){
                                $valorMesAnterior += $compra['valor'];
                            }
                            //Verificar data por ano
                            $hoje = new DateTime;
                            $dataInicio = new DateTime($hoje->format("Y")."-01-01 00:00:00");
                            $dataFim = $hoje;
                            if($dataInicio < $dataCompra && $dataCompra < $dataFim){
                                $valorAnual += $compra['valor'];
                            }

                        }
                    }
                    $TplView->num = $i+1;
                    $TplView->nomeFranquia = $franquia['nome'];
                    $TplView->hoje = "R$ ".number_format($valorHoje,"2",",",".");
                    $TplView->ontem = "R$ ".number_format($valorOntem,"2",",",".");
                    $TplView->anteOntem = "R$ ".number_format($valorAnteOntem,'2',',','.');
                    $TplView->mesAtual = "R$ ".number_format($valorMesAtual,"2",",",".");
                    $TplView->mesAnterior = "R$ ".number_format($valorMesAnterior,"2",",",".");
                    $TplView->anual = "R$ ".number_format($valorAnual,"2",",",".");
                    $totalHoje += $valorHoje;
                    $totalOntem += $valorOntem;
                    $totalAnteOntem += $valorAnteOntem;
                    $totalMesAtual += $valorMesAtual;
                    $totalMesAnterior += $valorMesAnterior;
                    $totalAnual += $valorAnual;
                    if($i%2 == 0){
                        $TplView->css = "";
                    }else{
                        $TplView->css = "alt";
                    }
                    $TplView->block("FRANQUIA_POR");
                    $i++;
                }
                $TplView->totalHoje = "R$ ".number_format($totalHoje,"2",",",".");
                $TplView->totalOntem = "R$ ".number_format($totalOntem,"2",",",".");
                $TplView->totalAnteOntem = "R$ ".number_format($totalAnteOntem,"2",",",".");
                $TplView->totalMesAtual = "R$ ".number_format($totalMesAtual,"2",",",".");
                $TplView->totalMesAnterior = "R$ ".number_format($totalMesAnterior,"2",",",".");
                $TplView->totalAnual = "R$ ".number_format($totalAnual,"2",",",".");
                $TplView->block("PORFRANQUIA");
                break;
        }
    }
?>