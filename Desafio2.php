<?php 
 
 

    if (empty($params["filtros"]["idevento"])) {
        throw new Exception("Nenhum evento selecionado");
    }
	
	$db = Db::Read()->clear()
		->select([
			"c.dsccategoria",
			"at.dscacesso_tipo",
            "COUNT('a.idacesso') as acessos"
			])
		->from("circus_acesso a")
        ->leftJoin("circus_acesso_tipo at", "a.idacesso_tipo = at.idacesso_tipo")
        ->leftJoin("circus_categoria c", "at.idcategoria = c.idcategoria")
		->whereAND(["a.idevento" => $params["filtros"]["idevento"]])
		->groupBy([1, 2])
        ->orderByDESC("dsccategoria")
		->fetchAll();

    return [
        "rows" => $db
	];