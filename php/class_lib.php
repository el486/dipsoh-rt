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
		$this->conn = odbc_connect("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$base",'','') or exit('Cannot open with driver.');
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

class Parser {
	private $sql;
	private $numCarpeta;
	private $fieldList;
	private $json;

}

class ParserTramites extends Parser {


	public function __construct($db,$numCarpeta){
	$this->numCarpeta = $numCarpeta;
	$this->sql = "SELECT Num_carpeta,Tramite,Aprobado,Fecha_recibido FROM tramites t,lista_tramites l WHERE t.Id_tramite=l.Id_tramite AND Num_carpeta=$this->numCarpeta";
	$this->fieldList = '[["Num_carpeta","num"],["Tramite","txt"],["Aprobado","num"],["Fecha_recibido","fecha"]]';
	$data = new ControladorAccess($db,$this->sql);
	$data->set_field_list($this->fieldList);
	$this->json = $data->get_json_data();	
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
	$html = '<br>Carpeta= '.$this->numCarpeta.'<br>'; 
	
		foreach ($array as $valor) {
			$html .= 'Tramite: '.$valor[1]. ' - Estado: '.$this->aprobado($valor[2]).$this->fecha($valor[3]).'<br>';
			}
		return $html;	
	
	}
	
	public function getJsonData(){	
		return $this->json;	
	}

}

class ParserNomencla extends Parser {


	public function __construct($db,$numCarpeta){
	$this->numCarpeta = $numCarpeta;
	$this->sql = "SELECT * FROM carpetas WHERE Num_carpeta=$this->numCarpeta";
	$this->fieldList = '[["Partido","num"],["Circunscripcion","txt"],["Seccion","txt"],["Chacra","txt"],["Quinta","txt"],["Fraccion","txt"],["Manzana","txt"],["Parcela","txt"]]';
	$data = new ControladorAccess($db,$this->sql);
	$data->set_field_list($this->fieldList);
	$this->json = $data->get_json_data();	
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
	
	public function getJsonData(){	
		return $this->json;	
	}

}
?>