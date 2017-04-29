<?php
class DataBase {
	private $mysqli;

  	function __construct() {
    	$this->mysqli = new mysqli("localhost", "test", "test", "test");
		$this->mysqli->set_charset("utf8");

  }

	private function DBquery($query){
  		return $this->mysqli->query($query);

	}
	

 	public function CreateTable(){
 		$this->DBquery("CREATE TABLE IF NOT EXISTS `users` ( `id` INT NOT NULL AUTO_INCREMENT , `name` TEXT NOT NULL , `email` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM;");

	}

	public function GetUserInfoById($id) {
		$res = $this->DBquery('SELECT  name, email FROM users WHERE id = ' . $id);
		while ($row = $res->fetch_assoc()) {
				$userInfo[] = $row;

			}
		return $userInfo;

	}

	public function CreateUser($name, $email) {
		if ((!empty($name)) AND (!empty($email))) {
		$res = $this->DBquery("INSERT INTO `users` (`id`, `name`, `email`) VALUES (NULL, ' ". $name ."', '" . $email . "');");

	}
		if ($res) return TRUE;
			else return FALSE;

	}

	public function ChangeUserInfo($id, $fields) {
		if (isset($fields['name']) AND isset($fields['email'])){
			$this->DBquery("UPDATE `users` SET `name` = '" . $fields['name'] . "', `email` = '" . $fields['email'] . "' WHERE `users`.`id` = " . $id);
			return $id;
			

		}
		if (isset($fields['name'])) {
			$this->DBquery("UPDATE `users` SET `name` = '" . $fields['name'] . "' WHERE `users`.`id` = " . $id);
			return $id;
			
			
		}

		if (isset($fields['email'])) {
			$this->DBquery("UPDATE `users` SET  `email` = '" . $fields['email'] . "' WHERE `users`.`id` = " . $id);
			return $id;
			
			
		}

	}



}





?>