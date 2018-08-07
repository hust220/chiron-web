<?php
function myerror($str){
	$errormessage = $_SESSION["error_$str"];
	echo "<td colspan=3 align=center> <font color=#990000> $errormessage </font> </td>";
	unset($_SESSION["error_$str"]);
}
function myform($str){
	$message = $_SESSION["save_$str"];
	echo "$message";
	unset($_SESSION["save_$str"]);
}
?>
