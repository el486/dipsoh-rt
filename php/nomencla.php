    <?php
		$db = 'E:\\DatosGis\\Planos\\baseRT_bs.mdb';
		$conn = odbc_connect("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$db",'','') or exit('Cannot open with driver.');
       	$carpeta = $_REQUEST['carpeta'];
		
		if(!$conn)
              exit("Connection Failed: " . $conn);
        $sql = "SELECT Num_carpeta,Partido,Circunscripcion,Seccion,Chacra,Quinta,Fraccion,Manzana,Parcela 
				FROM carpetas
				WHERE Num_carpeta=$carpeta";
        
		$rs = odbc_exec($conn, $sql);
        if(!$rs)
              exit("Error in SQL");
		
		while (odbc_fetch_row($rs)){
		echo 'Partido: '.odbc_result($rs,"Partido").'<br>'
		.'Circunscripcion: '.odbc_result($rs,"Circunscripcion").'<br>'
		.'Seccion: '.odbc_result($rs,"Seccion").'<br>'
		.'Chacra: '.odbc_result($rs,"Chacra").'<br>'
		.'Quinta: '.odbc_result($rs,"Quinta").'<br>'
		.'Fraccion: '.odbc_result($rs,"Fraccion").'<br>'
		.'Manzana: '.odbc_result($rs,"Manzana").'<br>'
		.'Parcela: '.odbc_result($rs,"Parcela").'<br>';
		}
	?>