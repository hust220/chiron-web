<?php
require('config/config.inc.php');
require('config/functions.inc.php');
?>

<?php
	if (empty($_SESSION['username'])){
		header("Location: login.php");
	}
?>
<?php

#------------Variable Declaration-------------------#
$dbparams   = array();
$jsonparams = array('jobid'=>'0','error'=>'');
$sq         = "'";
$queue      = $_POST[ 'queue'];

#-------Get submitted data and check format--------#
if(isset($_POST['title'])) {
	$title=$_POST['title'];
	$title=str_replace(" ","-",$title);
} else {
	$title="Custom";
}
$dbparams[ 'title'] = $sq.$title.$sq;

if(isset($_POST['pdbid'])) {
	$pdbid=$_POST['pdbid'];
	//$pdbid = trim(strtoupper($pdbid));
}
$dbparams[ 'ip'] = $sq.$_SERVER['REMOTE_ADDR'].$sq;
$dbparams[ 'queue'] = $sq.$queue.$sq;

#-----------check if the pdb file exists in database------------#

#---------- check if the pdb file has already been processed and show results ---------#	

if($pdbid) {
	$query = "SELECT id,queue,authKey,status,minimize FROM $tablejobs WHERE pdbid='$pdbid' AND queue='$queue' ORDER BY id DESC LIMIT 1";
	$result = mysql_query($query);
	if($result) {
		$row = mysql_fetch_array($result);
		$jsonparams[ 'queue']      = $row[ 'queue'];
		$jsonparams[ 'minimize']   = $row[ 'minimize'];
		$jsonparams[ 'jobid']      = $row[ 'id'];
		$jsonparams[ 'authKey']    = $row[ 'authKey'];
		$jsonparams[ 'jobstatus']  = $row[ 'status'];
		if($queue == $row[ 'queue']) {
			$jsonparams[ 'jobexists'] = "1";
			echo getJSONString($jsonparams);
			die;
		}
	}
}

$pdbfile = (($pdbid) ? "pdb/$pdbid.pdb" : "pdb/Custom.pdb");

if(check_pdb($pdbfile)) {
	unset($output);
	exec("gzip -f pdb/temp_renum.pdb",$output);
	if (! is_file("pdb/temp_renum.pdb.gz")) {
		$jsonparams[ 'error'] = "Failed to compress pdb file";
		echo getJSONString($jsonparams);
		die;
		//mydie("Failed to compress pdb file $pdbid.",'pdberr');
	}
	$pdbfilegz = "pdb/temp_renum.pdb.gz";
	$fp      = fopen($pdbfilegz, 'r');
	$content = fread($fp, filesize($pdbfilegz));
	$content = addslashes($content);
	fclose($fp);
	$dbparams[ 'ipdb'] = $sq.$content.$sq;
	//unlink("pdb/temp_filtered.pdb");
	//unlink("pdb/temp_renum.pdb.gz");
} else {
	$jsonparams[ 'error'] = "Failed during quality control of pdb";
	echo getJSONString($jsonparams);
	die;
	//mydie("Failed during quality control of pdb",'pdberr');
}
### now we have the pdbfileid for the submission ###

#-----------------update entry in job database-------------------------#
$dbparams[ 'pdbid']         = (($pdbid) ? $sq.$pdbid.$sq : "'Custom'");
$authKey                    = getRandomString(24);
$jsonparams[ 'authKey']     = $authKey;

$dbparams[ 'harmonic']      = (isset($_POST[ 'constrain'])) ? $sq.$_POST[ 'constrain'].$sq : $sq."0".$sq;
$dbparams[ 'emailFlag']     = (isset($_POST[ 'notify'])) ? $sq.$_POST[ 'notify'].$sq : $sq."0".$sq;
$dbparams[ 'created_by']    = $sq.$_SESSION['userid'].$sq;
$dbparams[ 'authKey']       = $sq.$authKey.$sq;
$dbparams[ 'flag']          = $sq."0".$sq;
$dbparams[ 'mlcs']          = $sq.$_POST[ 'mlcs'].$sq;
$dbparams[ 'mlclist']       = $sq.$_POST[ 'mlclist'].$sq;
if($_SESSION[ 'username'] == "guest") {
	$dbparams[ 'guestEmail'] = $sq.$_POST[ 'guestEmail'].$sq;
}
$dbparams[ 'queue']         = $sq.$queue.$sq;
if($queue == "gaia") {
	$dbparams[ 'minimize']   = $sq."0".$sq; # The default is 1 in the database - for chiron
}
$dbparams[ 'tsubmit']       = "NOW()";
$query = prepareInsert($tablejobs, $dbparams);
$result = mysql_query($query) or mydie("Failed to insert pdb file in database",'pdberr');
$jobid = mysql_insert_id();
//$jobid = 1;
$jsonparams[ 'jobid']         = $jobid;
echo getJSONString($jsonparams);

//unlink($pdbfile);
?>
