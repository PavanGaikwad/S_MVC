<?php

 class SQLQuery {
 	protected $_dbHandle;
 	protected $_result;
 	
 	/** connects to the database **/
 	
 	function connect($address, $user, $pwd, $name){
	$this->_dbHandle = @mysqli_connect($host, $user, $password,$name);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		return 0;
	}else{return 1;}
// 	if($this->_dbHandle){
// // 		if(mysqli_select_db($this->_dbHandle,$name)){
// 			return 1;
// 		}else{
// 			return 0;
// //		}
// 	} 		
 	}
 	
 	function disconnect(){
 		if(@mysqli_close($this->_dbHandle)!=0){
 			return 1;
 		}else{
 			return 0;
 		}
 	}
 	
 	function selectAll(){
 		
 		$query = "select * from $this->_table";
 		return $this->query($query);
 	}
 	
 	function selectId($id){
 		$id = (mysqli_real_escape_string($this->_dbHandle,$id));
 		$query = "select * from $this->_table where id = '$id'";
 		
 		
 		return $this->query($query, 1);
 	}
 	
 	
 	/** Custom SQL Query **/
 	
 	function query($query, $singleResult = 0) {
 	
 		$this->_result = mysqli_query($this->_dbHandle, $query);
 		
 		if (preg_match("/select/i",$query)) {
 			$result = array();
 			$table = array();
 			$field = array();
 			$tempResults = array();
 			$numOfFields = mysqli_field_count($this->_dbHandle);
 			
 			for ($i = 0; $i < $numOfFields; ++$i) {
 				array_push($table,mysqli_fetch_field_direct($this->_result, $i)->table);
 				array_push($field,mysqli_fetch_field_direct($this->_result, $i)->name);
 			}
 			
 			while ($row = mysqli_fetch_row($this->_result)) {
 				for ($i = 0;$i < $numOfFields; ++$i) {
 					$table[$i] = trim(ucfirst($table[$i]),"s");
 					$tempResults[$table[$i]][$field[$i]] = $row[$i];
 				}
 				if ($singleResult == 1) {
 					mysqli_free_result($this->_result);
 					return $tempResults;
 				}
 				array_push($result,$tempResults);
 			}
 			mysqli_free_result($this->_result);
 			return($result);
 		}
 	
 	}
 	
 	/** Get number of rows **/
 	function getNumRows() {
 		return mysqli_num_rows($this->_result);
 	}
 	
 	/** Free resources allocated by a query **/
 	
 	function freeResult() {
 		mysqli_free_result($this->_result);
 	}
 	
 	/** Get error string **/
 	
 	function getError() {
 		return mysqli_error($this->_dbHandle);
 	}
 	}