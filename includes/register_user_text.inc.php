<?php
require_once('../classes/Ps2/CheckPassword.php');
$usernameMinChars = 6;
$errors = array();
if (strlen($username) < $usernameMinChars) {
  $errors[] = "Username must be at least $usernameMinChars characters.";
}
if (preg_match('/\s/', $username)) {
  $errors[] = 'Username should not contain spaces.';
}
$checkPwd = new Ps2_CheckPassword($password, 10);
$checkPwd->requireMixedCase();
$checkPwd->requireNumbers(2);
$checkPwd->requireSymbols();
$passwordOK = $checkPwd->check();
if (!$passwordOK) {
  $errors = array_merge($errors, $checkPwd->getErrors());
}
if ($password != $retyped) {
  $errors[] = "Your passwords don't match.";
}
if(!$errors){
	// encrypt pw
	 $password = sha1($username.$password);
	 //open the file
	 $file = fopen($userfile, 'a+)');
	 if(filesize($userfile)===0){
	 	fwrite($file, "$username, $password");
	 	$result = "$username registered.";
	 } else {
	 	rewind($file);
	 	while(!feof($file)){
	 		$line = fgets($file);
	 		//split line at comma
	 		$tmp = explode(',',$line);
	 		if($tmp[0] == $username) {
	 			$result = "$username taken.";
	 			break;
	 		}
	 	}
	 	// if $result not set, usernmae is ok
	 	if(!isset($result)){
	 		// insert line break followed by usernam, comma, and pw
	 		fwrite($file, PHP_EOL . "$username, $password");
	 		$result = "$username registered.";
	 	}
	 	// close the file
	 	fclose($file);
	 }
}