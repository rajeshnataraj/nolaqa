<?php
@include('includesrestore/database.class.php');

class Table extends MysqliDatabase {
	
	protected $id;
	protected $term;
	protected $description;
	protected $createddate;
	protected $modifieddate;
	protected $delstatus;
	private $datbase;
		
	public function __construct()
	{
		$this->database = new MysqliDatabase(DB_CONNECTION);
	}
	
	public static function GetDatabase() {
	}
	
	public function LoadAll($qry) {
		$res = $this->database->query($qry);
		$obj = $res->fetch_object();
		return $obj;
	}
	
	public function Count($qry) {
		$res = $this->database->query($qry);
		return $res->num_rows;
	}
	
	public function QueryObject($qry) {
		$res = $this->database->query($qry);
		return $res;
	}
	
	public function NonQuery($qry) {
		$res = $this->database->query($qry);
		return $res;
	}
	
	public function NonQueryWithMaxValue($qry) {
		$res = $this->database->query($qry);
		$retunval = $this->database->insert_id;
		return $retunval;
	}
	
	public function SelectSingleValue($qry) {
		$res = $this->database->query_value($qry,'string');
		return $res;
	}
	
	public function SelectSingleValueInt($qry) {
		$res = $this->database->query_value($qry,'integer');
		return $res;
	}
	
	public function Pagination($query,$recperpage,$page) {
		$res = $this->database->query_page($query,$recperpage,$page);
		return $res;
	}
	
	public function GetPrepareDB() {
		$sqliconn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		return $sqliconn;
	}
	
	public function EscapeStrAll($qry) {
		$regex = '/<[^>]*>[^<]*<[^>]*>/';
		$res = preg_replace($regex, '', trim($this->database->real_escape_string($qry)));
		return $res;
	}
	
	public function EscapeStr($qry) {
		$res = preg_replace('/<script[^>]*>([\s\S]*?)<\/script[^>]*>/', '',(trim($this->database->real_escape_string($qry))));
		return $res;
	}
	
	public function closedb() {
		$this->database->close();
	}
}

$ObjDB = new Table();