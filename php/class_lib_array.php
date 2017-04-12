<?php
class ConexionDB {
private $db;

	public function __construct($db){
	$this->db = $db;
	}
	public function getDB(){
	return $this->db;
	}
}

class ControladorAccess {
	private $db;
	private $conn;
	private $sql;
	
	public function __construct($db,$sql) {
		$this->db = $db;
		$this->sql = $sql;
	}

	public function execute_sql()	{
			$base = $this->db->getDB();
		$this->conn = odbc_connect("DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$base",'','') or exit('Cannot open with driver.');
		if(!$this->conn)
			  exit("Connection Failed: " . $this->conn);
		
		$rs = odbc_exec($this->conn, $this->sql);
		if(!$rs)
			  exit("Error in SQL");
		return $rs;
	}
	
	public function get_array_data() {
		$rs=$this->execute_sql();
		while ($arr = odbc_fetch_array($rs)){	  
			$array[] = $arr;
			}
		return $array;
	}
	
	public function get_json_data() {
		
		$array=$this->get_array_data();
		if (count($array)==0)
			{$result= null;} 
			else 
			{ $result=array_map(function($a){return array_values($a);},$array);}
		return json_encode($result);
	}
	
	public function get_json_data_full() {
		
		$array=$this->get_array_data();
		if (count($array)==0)
			{$result= null;} 
			else 
			{ $result=json_encode($array);}
		return $result;
	}
	
}

abstract class Parser {
	protected $sql;
	protected $filtro;
	protected $json;
	protected $json_full;
	protected $coleccion;
	
	protected function __construct($db,$filtro,$sql){
	$this->filtro = $filtro;
	$this->sql = $sql;
	$data = new ControladorAccess($db,$this->sql);
	$this->coleccion = $data->get_array_data();	
	$this->json = $data->get_json_data();
	$this->json_full= $data->get_json_data_full();
	
	}
	
	public function getJsonData(){	
		return $this->json;	
	}
	
	public function getArrayData(){	
		return $this->coleccion;	
	}	
	
	public function getJsonDataFull(){	
		return $this->json_full;	
	}
	
	public function getArrayFilter($campo,$filtro){
	$array = $this->coleccion;
	$salida = array();
	foreach ($array as $elemento) {
		if ($elemento[$campo]==$filtro) $salida[]=$elemento;
		}
	return $salida;
	}
}

class ParserTramites extends Parser {

	public function __construct($db,$numCarpeta){
	$sql = "SELECT Num_carpeta,Tramite,Aprobado,Fecha_recibido,Observaciones,t.ID,Fecha_pedido FROM tramites t,lista_tramites l WHERE t.Id_tramite=l.Id_tramite AND Num_carpeta=$numCarpeta";
	parent::__construct($db,$numCarpeta,$sql);
	}
	
	private function fecha($fecha){
				if (!is_null($fecha)){
					return date_format(date_create($fecha),"d/m/Y");
				} else {
					return '(en Tramite)';
				}
			}
			
	private function aprobado($vari){
			if($vari==0){$aprob='No Aprobado ';
			}else{$aprob='Aprobado. Fecha: ';
			}
			return $aprob;
			}

	public function getHtmlData(){	
	
	$array=json_decode(utf8_encode($this->json));
	$html = '<br>Carpeta= '.$this->filtro.'<br>'; 
	if (count($array)==0){
			$html .= 'No hay tramites para esta carpeta';
		} else {
		
		foreach ($array as $valor) {
			$html .= '<a href="javascript:editTramite('.$valor[5].','.$valor[0].',\''.$this->fecha($valor[6]).'\')">'.$valor[5].'</a>'.' Tramite: '.$valor[1]. ' - Estado: '.$this->aprobado($valor[2]).$this->fecha($valor[3]).' '.$valor[4].'<br>';
			}
		}
		return $html;	
	
	}
}

class ParserNomencla extends Parser {

	public function __construct($db,$numCarpeta){
	$sql = "SELECT Partido,Circunscripcion,Seccion,Chacra,Quinta,Fraccion,Manzana,Parcela FROM carpetas WHERE Num_carpeta=$numCarpeta";
	parent::__construct($db,$numCarpeta,$sql);
	}
	
	public function getHtmlData(){	
	
	$array=json_decode(utf8_encode($this->json));
	
		foreach ($array as $valor) {
			$html .= '<br>Partido: '.$valor[0].'<br>'
					.'Circunscripcion: '.$valor[1].'<br>'
					.'Seccion: '.$valor[2].'<br>'
					.'Chacra: '.$valor[3].'<br>'
					.'Quinta: '.$valor[4].'<br>'
					.'Fraccion: '.$valor[5].'<br>'
					.'Manzana: '.$valor[6].'<br>'
					.'Parcela: '.$valor[7].'<br>'
					.'<br>';
			}
		return $html;	
	
	}
}

class ParserCarpetas extends Parser {

	public function __construct($db,$numCarpeta){
	$sql = "SELECT Num_carpeta,Partido,Parcela,Obra,Propietario,Num_expediente,Observaciones,Num_plano,Fecha_aprobacion,Finalizado FROM carpetas";
	parent::__construct($db,$numCarpeta,$sql);
	}
}

class ParserExpedientes extends Parser {

	public function __construct($db,$numExped){
	$sql = "SELECT NumExp,TipoExp,Iniciador,Extracto,UbicacionFisica,expedientes.Partido AS NumPart,partidos.Partido AS Partido,NomCatastral,Partida,ExpPrincipal,Obra,Oficina,Fecha_actualizacion,Observaciones,partidos.Partido FROM expedientes,partidos WHERE expedientes.Partido=partidos.NumPartido";
	parent::__construct($db,$numExped,$sql);
	}
}

class ParserListaTramites extends Parser {

	public function __construct($db,$numCarpeta){
	$sql = "SELECT Id_tramite, Tramite FROM Lista_tramites";
	parent::__construct($db,$numCarpeta,$sql);
	}
}

class Tramite {
	private $sql;
	private $db;
	public function __construct($db){
	$this->db=$db;
	}
	public function insertar($numCarpeta,$IdTramite,$FechaPedido,$FechaRecibido,$Aprobado,$Observaciones){
	$arr = new ControladorAccess($this->db,"SELECT Max(ID) as MaxID FROM Tramites;");
	$last = odbc_fetch_array($arr->execute_sql());
	$last = array_shift($last);
	$new = intval($last)+1;
	if ($FechaRecibido=='0') {$FechaRecibido='NULL';}
	$this->sql = "INSERT INTO Tramites(ID,Num_Carpeta,Id_tramite,Fecha_pedido,Fecha_recibido,Aprobado,Observaciones) VALUES($new,$numCarpeta,$IdTramite,$FechaPedido,$FechaRecibido,$Aprobado,'$Observaciones');";
	//echo $this->sql;
	$acc = new ControladorAccess($this->db,$this->sql);
	$exec = $acc->execute_sql();
	return $sql;
	}

	public function modificar($id,$FechaRecibido,$Aprobado,$Observaciones){
	if ($FechaRecibido=='0') {$FechaRecibido='NULL';}
	$this->sql = "UPDATE Tramites SET Fecha_recibido=$FechaRecibido,Aprobado=$Aprobado,Observaciones='$Observaciones' WHERE ID=$id;";
	//echo $this->sql;
	$acc = new ControladorAccess($this->db,$this->sql);
	$exec = $acc->execute_sql();
	return $sql;
	}

	public function borrar($id){
	$this->sql = "DELETE * FROM Tramites WHERE ID=$id;";
	//echo $this->sql;
	$acc = new ControladorAccess($this->db,$this->sql);
	$exec = $acc->execute_sql();
	return $sql;
	}
}		
?>