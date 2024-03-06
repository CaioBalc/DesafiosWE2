function WebServiceExec($params, $data){
	$parcelas = Db::Read()->clear()
    	    ->select([
				'c.num_contrato',
				'cr.idcontrato',
				'cr.status',
				'cr.dt_vencimento',
				'cr.valor_desconto',
				'cr.valor_desconto_manual',
				'cr.valor_juros',
				'cr.valor_multa',
				'cr.valor_adesao',
				'cr.valor_pago',
				'cr.valor'
     	       ])
     	   	->from('gs_contrato c')
			->join('cx_conta_receber cr', 'c.idcontrato = cr.idcontrato')
			->whereAND([
				'cr.status' => 'AP',
				'c.num_contrato' => $params['num_contrato']
			])
			->orderByASC('cr.dt_vencimento')
			#->limitDB(1)
			->fetchAll();
	
	foreach($parcelas as &$valor){
		$valor['dt_vencimento'] =  we_formatarData($valor['dt_vencimento']);
		$valor['valor_desconto'] = round($valor['valor_desconto'], 2);
		$valor['valor_desconto_manual'] = round($valor['valor_desconto_manual'], 2);
		$valor['valor_juros'] = round($valor['valor_juros'], 2);
		$valor['valor_multa'] = round($valor['valor_multa'], 2);
		$valor['valor_adesao'] = round($valor['valor_adesao'], 2);
		$valor['valor_pago'] = round($valor['valor_pago'], 2);
		$valor['valor'] = round($valor['valor'], 2);
		$valor['total'] = round((array_sum([$valor['valor_adesao'], $valor['valor_juros'], $valor['valor_multa'], $valor['valor']]))
			- (array_sum([$valor['valor_desconto'], $valor['valor_desconto_manual']])), 2);
	}
	
	return $parcelas;
	#var_dump($parcelas);
}