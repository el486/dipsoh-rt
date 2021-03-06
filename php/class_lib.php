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
	private $field_list;

	public function __construct($db,$sql) {
		$this->db = $db;
		$this->sql = $sql;
	}
	
	public function set_field_list($new_field_list) {
	$this->field_list = $new_field_list;
	}
	
	private function not_null($var,$tipo){
		$value='';
			if (!is_null($var)){
				if ($tipo!='num') $value.='"';
				if ($tipo=='fecha'){
					$value .= date_format(date_create($var),"d/m/Y");
				} else {
					$value .= str_replace(array("\r\n","\r","\""),"-", $var);
				}
				if ($tipo!='num') $value.='"';
			} else {
				$value = '"--"';
			}
		return $value;
		}
		
	public function get_json_data() {
		$lista = json_decode($this->field_list);
		$base = $this->db->getDB();
		$this->conn = odbc_connect("DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$base",'','') or exit('Cannot open with driver.');
		if(!$this->conn)
			  exit("Connection Failed: " . $this->conn);
		
		$rs = odbc_exec($this->conn, $this->sql);
		if(!$rs)
			  exit("Error in SQL");
		$value = '[';
		while (odbc_fetch_row($rs)){
			$value .= '[';
			foreach ($lista as $valor) {
				$value .= $this->not_null(odbc_result($rs,$valor[0]),$valor[1]).',';
			}
			$value .='],';
		}
		$value .= ']';
		$value = str_replace(",]","]",$value);
		odbc_close_all ();
		//$value = utf8_encode($value);
		return $value;
	}
}

abstract class Parser {
	protected $sql;
	protected $filtro;
	protected $fieldList;
	protected $json;
	
	protected function __construct($db,$filtro,$sql,$fieldList){
	$this->filtro = $filtro;
	$this->sql = $sql;
	$this->fieldList = $fieldList;
	$data = new ControladorAccess($db,$this->sql);
	$data->set_field_list($this->fieldList);
	$this->json = $data->get_json_data();	
	}
	
	public function getJsonData(){	
		return $this->json;	
	}
	
	public function getArrayData(){	
		$array=json_decode(mb_convert_encoding($this->json,'UTF-8','UTF-8'));
		return $array;	
	}	
	
	public function getArrayFilter($campo,$filtro){
	$array = $this->getArrayData();
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
	$fieldList = '[["Num_carpeta","num"],["Tramite","txt"],["Aprobado","num"],["Fecha_recibido","fecha"]]';
	parent::__construct($db,$numCarpeta,$sql,$fieldList);
	}
	
	private function fecha($fecha){
				if (!is_null($fecha)){
					return $fecha;
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
	$sql = "SELECT * FROM carpetas WHERE Num_carpeta=$numCarpeta";
	$fieldList = '[["Partido","num"],["Circunscripcion","txt"],["Seccion","txt"],["Chacra","txt"],["Quinta","txt"],["Fraccion","txt"],["Manzana","txt"],["Parcela","txt"]]';
	parent::__construct($db,$numCarpeta,$sql,$fieldList);
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
	$sql = "SELECT * FROM carpetas";
	$fieldList = '[["Num_carpeta","num"],["Partido","num"],["Parcela","txt"],["Obra","txt"],["Propietario","txt"],["Num_expediente","txt"],["Observaciones","txt"],["Num_plano","txt"],["Fecha_aprobacion","fecha"],["Finalizado","txt"]]';
	parent::__construct($db,$numCarpeta,$sql,$fieldList);
	}
}

class ParserExpedientes extends Parser {

	public function __construct($db,$numExped){
	$sql = "SELECT NumExp,TipoExp,Iniciador,Extracto,UbicacionFisica,expedientes.Partido AS NumPart,partidos.Partido AS Partido,NomCatastral,Partida,ExpPrincipal,Obra,Oficina,Fecha_actualizacion,Observaciones,partidos.Partido FROM expedientes,partidos WHERE expedientes.Partido=partidos.NumPartido";
	$fieldList = '[["NumExp","txt"],["TipoExp","txt"],["Iniciador","txt"],["Extracto","txt"],["UbicacionFisica","txt"],["NumPart","num"],["Partido","txt"],["NomCatastral","txt"],["Partida","txt"],["ExpPrincipal","txt"],["Obra","txt"],["Oficina","num"]]';
	parent::__construct($db,$numExped,$sql,$fieldList);
	}
}
?>