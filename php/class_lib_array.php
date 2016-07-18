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
	
	public function get_array_data() {
		$base = $this->db->getDB();
		$this->conn = odbc_connect("DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$base",'','') or exit('Cannot open with driver.');
		if(!$this->conn)
			  exit("Connection Failed: " . $this->conn);
		
		$rs = odbc_exec($this->conn, $this->sql);
		if(!$rs)
			  exit("Error in SQL");
		while ($arr = odbc_fetch_array($rs)){	  
			$array[] = $arr;
			}
		return $array;
	}
	
	public function get_json_data() {
		
		$array=$this->get_array_data();
		$result=array_map(function($a){return array_values($a);},$array);
		return json_encode($result);
	}
	
}

abstract class Parser {
	protected $sql;
	protected $filtro;
	protected $json;
	protected $coleccion;
	
	protected function __construct($db,$filtro,$sql){
	$this->filtro = $filtro;
	$this->sql = $sql;
	$data = new ControladorAccess($db,$this->sql);
	$this->coleccion = $data->get_array_data();	
	$this->json = $data->get_json_data();
	
	}
	
	public function getJsonData(){	
		return $this->json;	
	}
	
	public function getArrayData(){	
		return $this->coleccion;	
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
	$sql = "SELECT Num_carpeta,Tramite,Aprobado,Fecha_recibido FROM tramites t,lista_tramites l WHERE t.Id_tramite=l.Id_tramite AND Num_carpeta=$numCarpeta";
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
	
		foreach ($array as $valor) {
			$html .= 'Tramite: '.$valor[1]. ' - Estado: '.$this->aprobado($valor[2]).$this->fecha($valor[3]).'<br>';
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
?>