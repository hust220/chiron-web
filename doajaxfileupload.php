<?php
	require('config/config.inc.php');
	$uploaddir = "pdb";
	$error = "";
	$msg = "";
	$smlc = "no";
	$mlclist = "";
	$fileElementName = 'uploadedpdb';
	$nl = "\n";
	$jsonvar = array('error','msg','smlc','mlclist');
	$jsonstr = "";
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{
			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;

			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}elseif(empty($_FILES['uploadedpdb']['tmp_name']) || $_FILES['uploadedpdb']['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded..';
	}else 
	{
			$msg .= " <small>File: " . $_FILES['uploadedpdb']['name'] . ", ";
			$msg .= " Size: " . @filesize($_FILES['uploadedpdb']['tmp_name']);
			$msg .= " uploaded successfully </small>";
			$pdbfile = "pdb/Custom.pdb";
			if(!move_uploaded_file($_FILES['uploadedpdb']['tmp_name'],$pdbfile)) {
				$error = "<small>Error uploading file !</small>";
			}
			exec("$exec_checkPDB '$pdbfile'", $output);
			if( $output[0] == 0 ) { 
				$error = "<small>Invalid PDB file !</small>"; 
				unlink($pdbfile);
			}
			/*$pdbfh = fopen($pdbfile,"r");
			while(!feof($pdbfh)) {
				$line = fgets($pdbfh);
				if(preg_match('/^HETATM/',$line)==1) {
					$mlc = substr($line, 17, 3);
					if($mlc != "HOH" && $mlc != "SOL") {
						$smlc = "yes";
						break;
					}
				}
			}
			fclose($pdbfh);*/
			unset($output);
			exec("$getmlcs $pdbfile", $output);
			if(file_exists($output[0])) {
				$mlcfile = fopen($output[0],"r");
				$mlclist = str_replace("\n",":",fread($mlcfile, filesize($output[0])));
				$smlc = "yes";
			}
			//for security reason, we force to remove all uploaded file
			//@unlink($_FILES['fileToUpload']);		
	}		
	foreach ($jsonvar as $jv):
		$jsonstr .= '"' . $jv . '" : "' . ${$jv} . '",';
	endforeach;
	$jsonstr = rtrim($jsonstr,",");
	echo "{".$nl."\t".$jsonstr.$nl."}";
?>
