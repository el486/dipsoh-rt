<?php
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
		$this->conn = odbc_connect("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$this->db",'','') or exit('Cannot open with driver.');
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
		$value = str_replace(",],]","]]",$value);
		odbc_close_all ();
		return $value;
	}
}
?>