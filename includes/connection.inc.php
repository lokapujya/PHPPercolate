<?php
function dbConnect($usertype, $connectionType = 'mysqli') {
  $host = 'phpsolsranbrook.db.2610709.hostedresource.com';
  $db = 'phpsolsranbrook';/*Aloha333*/
  if ($usertype  == 'read') {
	$user = 'phpsolsranbrook';
	$pwd = 'Aloha333';
  } elseif ($usertype == 'write') {
	$user = 'phpsolsranbrook';
	$pwd = 'Aloha333';
  } else {
	exit('Unrecognized connection type');
  }
  if ($connectionType == 'mysqli') {
		$result = new mysqli($host, $user, $pwd, $db);
		if(!$result) {
		  die ('Cannot open database');
	  } else {
	  	return $result;
	  }
  } else {
    /*try {
      return new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
    } catch (PDOException $e) {
      echo 'Cannot connect to database';
      exit;
    }*/
  }
}
