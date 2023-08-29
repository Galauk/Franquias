<?php
	$TplView->addFile("INCLUDE_PG", "view/principal.html");
	$compra = new Compra;
	$TplView->midiagram = $compra->contarProduto('midiagram');
	$TplView->otimizaragora = $compra->contarProduto('otimizaragora');
	$TplView->sitexis = $compra->contarProduto('sitexis');
	$TplView->agoralogo = $compra->contarProduto('agoralogo');

	//Array com os nomes dos meses para utilizar nos graficos 
	$mes = array(
		'01' => "Janeiro",
		'02' => "Fevereiro",
		'03' => "Março",
		'04' => "Abril",
		'05' => "Maio",
		'06' => "Junho",
		'07' => "Julho",
		'08' => "Agosto",
		'09' => "Setembro",
		'10' => "Outubro",
		'11' => "Novembro",
		'12' => "Dezembro"
	);

	if($_SESSION['acessoTipo'] == 'admin'){
		$TplView->block("GRAFICOS");
		//carrega as data atual e 3 meses anteriores 
		$mes1 = new DateTime;
		$mes1 = $mes1->sub(new DateInterval("P3M"));
		$mes2 = new DateTime;
		$mes2 = $mes2->sub(new DateInterval("P2M"));
		$mes3 = new DateTime;
		$mes3 = $mes3->sub(new DateInterval("P1M"));
		$mes4 = new DateTime;
		
		$TplView->mes1 = $mes[$mes1->format('m')];
		$TplView->mes2 = $mes[$mes2->format('m')];
		$TplView->mes3 = $mes[$mes3->format('m')];
		$TplView->mes4 = $mes[$mes4->format('m')];

		$TplView->midiaMes1 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$mes1->format("Y-m")."%")));
		$TplView->midiaMes2 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$mes2->format("Y-m")."%")));
		$TplView->midiaMes3 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$mes3->format("Y-m")."%")));
		$TplView->midiaMes4 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%")));

		$TplView->otimMes1 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$mes1->format("Y-m")."%")));
		$TplView->otimMes2 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$mes2->format("Y-m")."%")));
		$TplView->otimMes3 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$mes3->format("Y-m")."%")));
		$TplView->otimMes4 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%")));

		$TplView->siteMes1 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$mes1->format("Y-m")."%")));
		$TplView->siteMes2 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$mes2->format("Y-m")."%")));
		$TplView->siteMes3 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$mes3->format("Y-m")."%")));
		$TplView->siteMes4 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%")));

		$TplView->agoraMes1 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$mes1->format("Y-m")."%")));
		$TplView->agoraMes2 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$mes2->format("Y-m")."%")));
		$TplView->agoraMes3 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$mes3->format("Y-m")."%")));
		$TplView->agoraMes4 = count($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%")));


		//Valor total por mes

		function somavalor($array){
			$result = 0;
			foreach ($array as $compra) {
				$result = ($result + $compra['valor']);
			}
			return $result;
		} 

		
		$TplView->midiaValorMes1 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$mes1->format("Y-m")."%")));
		$TplView->midiaValorMes2 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$mes2->format("Y-m")."%")));
		$TplView->midiaValorMes3 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$mes3->format("Y-m")."%")));
		$TplView->midiaValorMes4 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%")));
		$TplView->midiaValorMes = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%"))),2,',','.');

		$TplView->otimValorMes1 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$mes1->format("Y-m")."%")));
		$TplView->otimValorMes2 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$mes2->format("Y-m")."%")));
		$TplView->otimValorMes3 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$mes3->format("Y-m")."%")));
		$TplView->otimValorMes4 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%")));
		$TplView->otimValorMes = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%"))),2,',','.');

		$TplView->siteValorMes1 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$mes1->format("Y-m")."%")));
		$TplView->siteValorMes2 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$mes2->format("Y-m")."%")));
		$TplView->siteValorMes3 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$mes3->format("Y-m")."%")));
		$TplView->siteValorMes4 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%")));
		$TplView->siteValorMes = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%"))),2,',','.');

		$TplView->agoraValorMes1 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$mes1->format("Y-m")."%")));
		$TplView->agoraValorMes2 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$mes2->format("Y-m")."%")));
		$TplView->agoraValorMes3 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$mes3->format("Y-m")."%")));
		$TplView->agoraValorMes4 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%")));
		$TplView->agoraValorMes = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$mes4->format("Y-m")."%"))),2,',','.');


		//Listando referencia de dias

		$dia1 = new DateTime;
		$dia1 = $dia1->sub(new DateInterval("P3D"));
		$dia2 = new DateTime;
		$dia2 = $dia2->sub(new DateInterval("P2D"));
		$dia3 = new DateTime;
		$dia3 = $dia3->sub(new DateInterval("P1D"));
		$dia4 = new DateTime;

		$TplView->dia1 = $dia1->format("d/m");
		$TplView->dia2 = $dia2->format("d/m");
		$TplView->dia3 = $dia3->format("d/m");
		$TplView->dia4 = $dia4->format("d/m");

		$compraHoje = $compra->listarFiltro("WHERE data LIKE :data ",array('data'=>$dia4->format("Y-m-d")." %"));

		$TplView->midiaValorDia1 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$dia1->format("Y-m-d")." %")));
		$TplView->midiaValorDia2 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$dia2->format("Y-m-d")." %")));
		$TplView->midiaValorDia3 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$dia3->format("Y-m-d")." %")));
		$TplView->midiaValorDia4 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'midiagram' AND status_pgto = 'paid'",array('data'=>$dia4->format("Y-m-d")." %")));

		$TplView->otimValorDia1 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$dia1->format("Y-m-d")."%")));
		$TplView->otimValorDia2 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$dia2->format("Y-m-d")."%")));
		$TplView->otimValorDia3 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$dia3->format("Y-m-d")."%")));
		$TplView->otimValorDia4 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'otimizaragora' AND status_pgto = 'paid'",array('data'=>$dia4->format("Y-m-d")."%")));

		$TplView->siteValorDia1 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$dia1->format("Y-m-d")."%")));
		$TplView->siteValorDia2 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$dia2->format("Y-m-d")."%")));
		$TplView->siteValorDia3 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$dia3->format("Y-m-d")."%")));
		$TplView->siteValorDia4 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'sitexis' AND status_pgto = 'paid'",array('data'=>$dia4->format("Y-m-d")."%")));

		$TplView->agoraValorDia1 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$dia1->format("Y-m-d")."%")));
		$TplView->agoraValorDia2 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$dia2->format("Y-m-d")."%")));
		$TplView->agoraValorDia3 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$dia3->format("Y-m-d")."%")));
		$TplView->agoraValorDia4 = somavalor($compra->listarFiltro("WHERE data LIKE :data AND origem_compra = 'agoralogo' AND status_pgto = 'paid'",array('data'=>$dia4->format("Y-m-d")."%")));

		$TplView->totalAnteOntem = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND status_pgto = 'paid' AND cod_vendedor = 0",array('data'=>$dia2->format("Y-m-d")."%"))),2,',','.');
		$TplView->totalOntem = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND status_pgto = 'paid' AND cod_vendedor = 0",array('data'=>$dia3->format("Y-m-d")."%"))),2,',','.');
		$TplView->totalDia = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND status_pgto = 'paid' AND cod_vendedor = 0",array('data'=>$dia4->format("Y-m-d")."%"))),2,',','.');
		$TplView->totalMes = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND status_pgto = 'paid'",array('data'=>$dia4->format("Y-m")."%"))),2,',','.');
		$TplView->totalAno = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND status_pgto = 'paid'",array('data'=>$dia4->format("Y")."%"))),2,',','.');
		$TplView->totalFranq = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND status_pgto = 'paid' AND cod_vendedor <> 0",array('data'=>$dia4->format("Y-m")."%"))),2,',','.');
		
		$TplView->totalAnteOntemFranq = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND status_pgto = 'paid' AND cod_vendedor <> 0",array('data'=>$dia2->format("Y-m-d")."%"))),2,',','.');
		$TplView->totalOntemFranq = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND status_pgto = 'paid' AND cod_vendedor <> 0",array('data'=>$dia3->format("Y-m-d")."%"))),2,',','.');
		$TplView->totalDiaFranq = number_format(somavalor($compra->listarFiltro("WHERE data LIKE :data AND status_pgto = 'paid' AND cod_vendedor <> 0",array('data'=>$dia4->format("Y-m-d")."%"))),2,',','.');

		$TplView->historico = "";

	}else{

		$TplView->mes1 = '';
		$TplView->mes2 = '';
		$TplView->mes3 = '';
		$TplView->mes4 = '';

		$TplView->dia1 = '';
		$TplView->dia2 = '';
		$TplView->dia3 = '';
		$TplView->dia4 = '';
	
		$TplView->midiaMes1 = '';
		$TplView->midiaMes2 = '';
		$TplView->midiaMes3 = '';
		$TplView->midiaMes4 = '';
	
		$TplView->otimMes1 = '';
		$TplView->otimMes2 = '';
		$TplView->otimMes3 = '';
		$TplView->otimMes4 = '';
	
		$TplView->siteMes1 = '';
		$TplView->siteMes2 = '';
		$TplView->siteMes3 = '';
		$TplView->siteMes4 = '';
	
		$TplView->midiaValorMes1 = '';
		$TplView->midiaValorMes2 = '';
		$TplView->midiaValorMes3 = '';
		$TplView->midiaValorMes4 = '';
	
		$TplView->otimValorMes1 = '';
		$TplView->otimValorMes2 = '';
		$TplView->otimValorMes3 = '';
		$TplView->otimValorMes4 = '';
	
		$TplView->siteValorMes1 = '';
		$TplView->siteValorMes2 = '';
		$TplView->siteValorMes3 = '';
		$TplView->siteValorMes4 = '';
		
		$TplView->midiaValorDia1 = '';
		$TplView->midiaValorDia2 = '';
		$TplView->midiaValorDia3 = '';
		$TplView->midiaValorDia4 = '';
	
		$TplView->otimValorDia1 = '';
		$TplView->otimValorDia2 = '';
		$TplView->otimValorDia3 = '';
		$TplView->otimValorDia4 = '';
	
		$TplView->siteValorDia1 = '';
		$TplView->siteValorDia2 = '';
		$TplView->siteValorDia3 = '';
		$TplView->siteValorDia4 = '';
	
		$TplView->totalDia = '';
		$TplView->totalMes = '';
		$TplView->totalAno = '';
		$TplView->totalFranq = '';

		$aviso = new Aviso;
		$historico = null;
		$i=0;
		foreach($aviso->listar() as $avisos){
			$data = new DateTime($avisos['data']);
			if($i>0)$historico.="<hr>";
			if(strripos($avisos['mensagem'],'[video]') === false){
				$historico.= "<b class='f14'>(".$data->format("d/m/Y H:i:s").")".$avisos['nome']."</b>:".$avisos['mensagem']. "
";
			}else{
				$historico.= "<b class='f14'>(".$data->format("d/m/Y H:i:s").")".$avisos['nome']."</b>:
<iframe class='mediaratio' src='https://www.youtube.com/embed/".substr($avisos['mensagem'],7,-8)."' frameborder='0' allowfullscreen></iframe>
";
			}
			$i++;
		}
	
		$TplView->historico = nl2br($historico);
		$TplView->block("AVISOS");

	}
?>