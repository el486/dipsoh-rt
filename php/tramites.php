    <?php

		$db = 'E:\\DatosGis\\Planos\\baseRT_bs.mdb';
		$conn = odbc_connect("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$db",'','') or exit('Cannot open with driver.');
       	$carpeta = $_REQUEST['carpeta'];
		
		function fecha($fecha){
			if (!is_null($fecha)){
				return date_format(date_create($fecha),'d/m/Y');
			} else {
				return '(en Tramite)';
			}
		}
		
		function aprobado($vari){
		if($vari==0){$aprob='No Aprobado ';
		}else{$aprob='Aprobado. Fecha: ';
		}
		return $aprob;
		}
		
		if(!$conn)
              exit("Connection Failed: " . $conn);
        $sql = "SELECT Num_carpeta,Tramite,Aprobado,Fecha_recibido 
				FROM tramites t,lista_tramites l 
				WHERE t.Id_tramite=l.Id_tramite
				AND Num_carpeta=$carpeta";
        
		$rs = odbc_exec($conn, $sql);
        if(!$rs)
              exit("Error in SQL");
		
		$index=0;
		while (odbc_fetch_row($rs)){
		$index++;
		echo odbc_result($rs,"Num_carpeta").' - '.odbc_result($rs,"Tramite").' - '.aprobado(odbc_result($rs,"Aprobado")).fecha(odbc_result($rs,"Fecha_recibido")).'<br>';
		}
		echo 'Registros: '.$index;
	?>