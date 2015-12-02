<?php

require_once("functions.php");

class Worker {
	
	private $connection;
	
	function __construct($mysqli){
		$this->connection = $mysqli;
	}
	
	function getPacketData($keyword=""){
		
		$search = "%%";
		
		if($keyword!=""){
			
			$search = "%".$keyword."%";
			
		}
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT packet_id, arrival, departure, fromc, comment, offices.office FROM post_import join offices on post_import.office_id=offices.office_id WHERE deleted IS NULL AND (packet_id LIKE ? OR arrival LIKE ? OR departure LIKE ? OR fromc LIKE ? OR comment LIKE ? OR offices.office LIKE ?)");
		echo $mysqli->error;
		$stmt->bind_param("issssi", $search, $search, $search, $search, $search, $search);
		$stmt->bind_result($id, $arrival, $departure, $fromc, $comment, $office_id);
		$stmt->execute();
		$packet_array = array();
		while($stmt->fetch()){
			$packet = new StdClass();
			$packet->id = $id;
			$packet->arrival = $arrival;
			$packet->departure = $departure;
			$packet->fromc = $fromc;
			$packet->comment = $comment;
			$packet->office_id = $office_id;
			array_push($packet_array, $packet);
			
		}
		$stmt->close();
		return $packet_array;
		
	}
}

?>