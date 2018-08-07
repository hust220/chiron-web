<?php
// Filename : showresults.php
# Mean and SD
# Buried Hbond -   0.134   1.308
# Shell Hbond  -   7.523   2.031
# MSA          -  67.104   8.949
# SASA         - 201.846  26.254
# Void Volume  -   0.209   0.690

require('config/config.inc.php');
require('config/functions.inc.php');

if (empty($_SESSION['username'])){
    #	header("Location: login.php");
}

?>
<html>
<?php 
include("txt/head.txt");
$jobid = $_GET[ 'jobid'];
$queue = $_GET[ 'queue'];
$minimize = $_GET[ 'minimize'];
$spacer        = "<div class=hspacer50></div>";
$invalid_url   = "<strong style='color:#990000'>The URL you supplied, is invalid ! <br><br></strong> Please click on the link you received by e-mail or carefully copy and paste the URL onto a web browser and try again.<br>";
$scrambled_url = "<strong style='color:#990000'>The URL you supplied, is scrambled ! <br><br></strong> Please verify the URL and try again later.<br>";
$suggestion    = "<br>If you think you reached this page by mistake, please contact the administrator via the contact page on the website. Please include the URL in your message to help the administrators debug the problem.";

// CONSTANTS
defined('CLASH_MEAN') or define('CLASH_MEAN', '0.0145');
defined('CLASH_SDEV') or define('CLASH_SDEV', '0.0059');
defined('HBOND_CORE_MEAN') or define('HBOND_CORE_MEAN','0.134');
defined('HBOND_CORE_SDEV') or define('HBOND_CORE_SDEV','1.308');
defined('HBOND_SHELL_MEAN') or define('HBOND_SHELL_MEAN','7.523');
defined('HBOND_SHELL_SDEV') or define('HBOND_SHELL_SDEV','2.031');
#defined('MSA_MEAN') or define('MSA_MEAN','67.104');
defined('MSA_MEAN') or define('MSA_MEAN','125.961');
#defined('MSA_SDEV') or define('MSA_SDEV','8.949');
defined('MSA_SDEV') or define('MSA_SDEV','15.887');
defined('SASA_MEAN') or define('SASA_MEAN','201.846');
defined('SASA_SDEV') or define('SASA_SDEV','26.254');
defined('VOID_MEAN') or define('VOID_MEAN','0.209');
defined('VOID_SDEV') or define('VOID_SDEV','0.690');

//if(!isset($_GET[ 'authKey'])) {
//    echo $status;
//    echo $spacer;
//    echo $invalid_url;
//    die;
//} else {
//    $authKey = $_GET[ 'authKey'];
//    if(strlen($authKey) != 24) {
//        echo $spacer;
//        echo $scrambled_url;
//        die;
//    }
//    $query = "SELECT authKey,status FROM $tablejobs WHERE id='$jobid'"; //queue='$queue'";
    $query = "SELECT status FROM $tablejobs WHERE id='$jobid'"; //queue='$queue'";
    $result= mysql_query($query);
    $rows  = mysql_num_rows($result);
    if($rows == 0 ) {
        echo $spacer;
        echo $invalid_url;
        echo $suggestion;
        die;
    }
    $row = mysql_fetch_array($result);
//    $db_authKey = $row[ 'authKey'];
    $status     = $row[ 'status'];
//    if($authKey != $db_authKey) {
//        echo $spacer;
//        echo $scrambled_url;
//        echo $suggestion;
//        die;
//    }
//}
?>
    <div id="content">
        <div id="nav">
<?php 
include("txt/menu.php");
?>
        </div>

        <div id="main_content">
            <div class="indexTitle"> Results </div>
<?php
$userid = $_SESSION['userid'];
if (isset($_SESSION['tlogin']) ) {
    $tlogin = $_SESSION['tlogin'];
} else {
    $tlogin = '0000-00-00 00:00:00';
}
echo '<div class="hspacer10"></div>';
if($status<2) {
    echo 'This job is not processed yet. Please check back later for results.';
    die;
}
if($minimize == 1) {
    $tableheader = '<table class="queryTable" width=100%> <tbody id="tbodytag"> <tr class="queryTitle">';
    $tableheader .= '<td>File</td><td>Action</td>';
    $tableheader .= '</tr>';
    echo $tableheader;

    # ---- Patched for accessibility to all users if the URL has the right authorization key ---- #
    $query = "SELECT $tableresults.*, $tablejobs.pdbid, $tablejobs.status FROM $tableresults LEFT JOIN $tablejobs ON (status='2' AND $tablejobs.id=$tableresults.jobid) WHERE $tableresults.jobid=$jobid AND $tablejobs.minimize=$minimize";
    //$query = "SELECT $tableresults.*, $tablejobs.pdbid, $tablejobs.status FROM $tableresults LEFT JOIN $tablejobs ON (status='2' AND authKey='".$authKey."' AND $tablejobs.id=$tableresults.jobid) WHERE $tableresults.jobid=$jobid AND $tablejobs.minimize=$minimize";
    //$query = "SELECT $tableresults.*, $tablejobs.pdbid, $tablejobs.status FROM $tableresults LEFT JOIN $tablejobs ON (status='2' AND created_by='$userid' AND $tablejobs.id=$tableresults.jobid) WHERE $tableresults.userid='$userid' AND $tableresults.jobid=$jobid";
    $result = mysql_query($query);

    if($result) {
        while ( $row = mysql_fetch_array($result)){
            $jobid    = $row['jobid'];
            $pdbid    = $row['pdbid'];
            $postat   = $row['tposted'];
            $iclashr  = getgzStreamAsArray($row['iClashR']);
            $fclashr  = getgzStreamAsArray($row['fClashR']);
            $fpdb     = getgzStreamAsArray($row['fpdb']);
            $ps       = getgzStreamAsArray($row['ps']);
            #				exec("convert -rotate 90 -shave 40x25 -density 300x300 download/tmp download/$pdbid-$jobid.jpeg",$output);
            exec("gs -dQUIET -sDEVICE=ppmraw -r300 -sPAPERSIZE=a4 -dBATCH -dNOPAUSE -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=- download/tmp |pamflip -cw|pnmtojpeg > download/$pdbid-$jobid.jpeg",$output);

            $fields = array('Atom1','Residue1','Atom2','Residue2','Accepted Distance (&Aring;)','Actual Distance (&Aring;)','VDW Repulsion (kcal/mol)');

            //$haspdb = $row['!ISNULL(fpdb)'];
            if ($haspdb) {
                $pdbstr = "<a href='downloadpdb.php?id=$jobid'><img src='style/img/download.png' title='download output pdb' border='0px'> </a>";
                //					$url = "loadPage(\"downloadpdb.php?id=$jobid\")";
                //					$pdbstr = "<img src='style/img/zipped.gif' title='download out pdb' onclick ='$url' style='cursor:pointer;' >";
            }else{
                $pdbstr = "";
            }
            //$href = "javascript:delentry($jobid)";
            //$viewstr = '<img src="style/img/view.png" id="imgclashr" title="view file" border="0px" style="cursor:pointer;" onclick="slide_iclashr.slideIt();">';
            $viewstr = '<img src="style/img/view.png" id="imgclashr" title="view file" border="0px" style="cursor:pointer;">';

            echo "<tr id='entrytag$jobid'>";
            echo "<td>Output PDB file</td>";
            echo "<td><a href='filedownload.php?type=fpdb&jobid=$jobid'> <img src='style/img/download.png' title='download file' border='0px'> </a></td>";
            echo "</tr>";
            echo "<tr id='entrytag$jobid'>";
            echo "<td> Initial clash report </td>";
            echo '<td> <a class="swapbtn" href="#"><img class="eye" src="style/img/view.png" border="0px"><img class="eye" src="style/img/hide.png" border="0px" style="display: none;"></a> <a href="filedownload.php?type=iclashr&jobid='.$jobid.'"> <img src="style/img/download.png" title="download file" border="0px"> </a></td>';
            echo "</tr>";
            echo "<tr id='entrytag$jobid'>";
            echo "<td colspan=2><div style='display:none;' id='iclashr'> <fieldset><legend>Initial Clash Report</legend><table class=queryTable width=100%><tbody id=tbodytag><tr class=queryTitle>";
            foreach ($fields as $f):
                echo "<td>$f</td>";
endforeach;
echo "</tr><tr>";
for($i=1;$i<count($iclashr);$i++):
    if(substr($iclashr[$i],0,5)=="Total") {
        break;
    }
echo "<tr>";
$preline = str_replace(" ","\t",$iclashr[$i]);
$values = split("\t+",$preline);
foreach ($values as $v):
    if($v != "---") echo "<td>$v</td>";
endforeach;
echo "</tr>";
endfor;
echo "</tbody></table>";
echo "<table>";
for($i=1;$i<count($iclashr);$i++):
    if(!preg_match("/---/",$iclashr[$i])) {
        echo "<tr><td><small>".$iclashr[$i]."</small></td></tr>";
    }
endfor;
echo "</table>";
echo '</fieldset></div>';
//echo "<script type='text/javascript'>var slide_iclashr = new animatedDiv('slide_iclashr','iclashr',100,'imgiclashr',true);</script>";
echo '</td></tr>';
echo "<tr id='entrytag$jobid'>";
echo "<td> Final clash report </td>";
echo '<td> <img src="style/img/view.png" id="imgfclashr" title="view file" border="0px" style="cursor:pointer;" onclick="slide_fclashr.slideIt();"> <a href="filedownload.php?type=fclashr&jobid='.$jobid.'"> <img src="style/img/download.png" title="download file" border="0px"> </a></td>';
echo "</tr>";
echo "<tr id='entrytag$jobid'>";
echo "<td colspan=2><div style='display:none;' id='fclashr'> <fieldset><legend>Final Clash Report</legend><table class=queryTable width=100%><tbody id=tbodytag><tr class=queryTitle>";
foreach ($fields as $f):
    echo "<td>$f</td>";
endforeach;
echo "</tr><tr>";
for($i=0;$i<count($fclashr);$i++):
    if(substr($fclashr[$i],0,5)=="Total") {
        break;
    }
echo "<tr>";
$preline = str_replace(" ","\t",$fclashr[$i]);
$values = split("\t+",$preline);
foreach ($values as $v):
    if($v != "---") echo "<td>$v</td>";
endforeach;
echo "</tr>";
endfor;
echo "</tbody></table>";
echo "<table>";
for($i=1;$i<count($fclashr);$i++):
    if(!preg_match("/---/",$fclashr[$i])) {
        echo "<tr><td><small>".$fclashr[$i]."</small></td></tr>";
    }
endfor;
echo "</table>";
echo '</fieldset></div>';
echo "<script type='text/javascript'>var slide_fclashr = new animatedDiv('slide_fclashr','fclashr',100,'imgfclashr',true);</script>";
echo '</td></tr>';
echo "<tr id='entrytag$jobid'>";
echo "<td> Visualization script <span class='formInfo'><a href='txt/pyscript.htm?width=400' class='jTip' id='help' name='Visualization Instructions'><img src='style/img/help.png' border='0px'></a></span></td>";
echo "<td><a href='filedownload.php?type=pml&jobid=$jobid'> <img src='style/img/download.png' title='download file' border='0px'></a></td>";
echo "</tr>";
echo "<tr id='entrytag$jobid'>";
echo "<td> Minimization Summary </td>";
echo '<td> <a href="imagebox.php?pdbid='.$pdbid.'&jobid='.$jobid.'" rel="lyteframe" title="Minimization Summary" rev="width: 800px; height: 600px; scrolling: yes;"><img src="style/img/view.png" title="view file" border="0px"></a> <a href="filedownload.php?type=jpeg&pdbid='.$pdbid.'&jobid='.$jobid.'"><img src="style/img/download.png" title="download file" border="0px"></a></td>';
echo "</tr>";

        }
        echo "</tbody></table>";
    }
    echo '<div class=footnote>';
    echo '<p>';
    echo '<strong>Ramachandran, S., Kota, P., Ding, F. and Dokholyan, N. V., <i>PROTEINS: Structure, Function and Bioinformatics,</i> 79: 261-270 (2011)</strong>';
    echo '</p></div>';
} else {
    # ---- Patched for accessibility to all users if the URL has the right authorization key ---- #
    $query = "SELECT $gaia_results.*, $tablejobs.status FROM $gaia_results LEFT JOIN $tablejobs ON ($tablejobs.id=$gaia_results.jobid) WHERE $gaia_results.jobid='".$jobid."' AND $tablejobs.queue='gaia'";
#    $query = "SELECT $gaia_results.*, $tablejobs.status FROM $gaia_results LEFT JOIN $tablejobs ON ($tablejobs.authKey='".$authKey."' AND $tablejobs.id=$gaia_results.jobid) WHERE $gaia_results.jobid='".$jobid."' AND $tablejobs.queue='gaia'";
    $result = mysql_query($query);
    if($result) {
        $row = mysql_fetch_array($result);
        $jobid     = $row[ 'jobid'];
        $pdbid     = $row[ 'pdbid'];
        $jsonstr   = $row[ 'jsonstr'];
        $clashR    = $row[ 'clashR'];
        $clashpdf  = $row[ 'clashpdf'];
        $hblist    = $row[ 'hblist'];
        $hbslist   = $row[ 'hbslist'];
        $hbspdf    = $row[ 'hbspdf'];
        $hbclist   = $row[ 'hbclist'];
        $hbcpdf    = $row[ 'hbcpdf'];
        $msapdf    = $row[ 'msapdf'];
        $sasapdf   = $row[ 'sasapdf'];
        $vlist     = $row[ 'vlist'];
        $voidpdf   = $row[ 'voidpdf'];
        $bonds     = $row[ 'bonds'];
        $angles    = $row[ 'angles'];
        $phipsi    = $row[ 'phipsi'];
        $phipsipdf = $row[ 'phipsipdf'];
        $omega     = $row[ 'omega'];
        $sczout    = $row[ 'sczout'];
        $summary   = $row[ 'summary'];
        $report    = $row[ 'report'];
        $pym       = $row[ 'pym'];

        $output    = array();
        $json_hash = parseJSONString($jsonstr);
        $tmpdir    = "$pdbid-$jobid";

        # ---- Compute p-values for all filters ---- #
        $p_clash   = pValue ( $json_hash[ 'clashscore']          , CLASH_MEAN       , CLASH_SDEV       );
        $p_hbs     = pValue ( $json_hash[ 'percent_shell']       , HBOND_SHELL_MEAN , HBOND_SHELL_SDEV );
        $p_hbc     = pValue ( $json_hash[ 'percent_unsatisfied'] , HBOND_CORE_MEAN  , HBOND_CORE_SDEV  );
        $p_sasa    = pValue ( $json_hash[ 'sasa_rescaled']       , SASA_MEAN        , SASA_SDEV        );
        $p_msa     = pValue ( $json_hash[ 'msa_rescaled']        , MSA_MEAN         , MSA_SDEV         );
        $p_voids   = pValue ( $json_hash[ 'voidvolume_rescaled'] , VOID_MEAN        , VOID_SDEV        );
        if($json_hash[ 'voidvolume_rescaled'] < 0.206 ) {
            $p_voids = 1.0;
        } else {
            $p_voids *= (0.59/0.8);
        }

        # ---- Deal with clashes ---- #

        echo '<input type=hidden name=jobid value='.$jobid.'>';
        echo '<input type=hidden name=pdbid value='.$pdbid.'>';
        echo '<div id="dialog-results" title="Job Status"></div>';
        echo '<div id="results clash" style="padding-bottom: 10px;">';
        echo '	<div class="ui-accordion ui-widget">';
        if($p_clash < 0.05) {
            echo '		<div id="clash_header" class="ui-state-error ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="clash_status" class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span><strong>Steric clashes</strong></p>';
            echo '		</div>';
        } else {
            echo '		<div id="clash_header" class="ui-state-highlight ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="clash_status" class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span><strong>Steric clashes</strong></p>';
            echo '		</div>';
        }
        echo '	</div>';
        generateImage($clashpdf, $tmpdir,"clash");
        echo '	<div id="clash_content" class="ui-accordion-content ui-widget-content ui-helper-reset ui-corner-bottom ui-accordion-content-active" style="border-top:0px;">';
        echo '		<div id="clash_content_main" class="result-content">';
        echo '			<table class=queryTable width=300 cellpadding=2 align=center>';
        echo '				<tr> <td> Clash score for the input structure </td> <td> : </td> <td> '.sprintf("%.3g",$json_hash[ 'clashscore']).'</td> </tr>';
        echo '				<tr> <td> p-value from the following distribution </td> <td> : </td> <td>'.sprintf("%.3g",$p_clash).' </td> </tr>';
        echo '			</table>';
        echo '			</br>';
        echo '			<img class="img550" src="exec/'.$tmpdir.'/'.$tmpdir.'-clash.jpeg">';
        echo '		</div>';
#        echo 'AAAAAAAAA';
#        echo $status;
        if($p_clash < 0.05) {
            echo '		<div id="submit_to_chiron">';
            echo '			<div class="results filterinfo">';
            echo '				The clash score for your structure is more than the acceptable clash score.';
            echo '			</div>';
            echo '			<div class="filteraction">';
            echo '				<input type=button class="submitbtn filteractionbtn" name="submit_to_chiron" id="remove_clashes" value="Remove clashes">';
            echo '			</div>';
            echo '			<div style="clear:both;"></div>';
            echo '		</div>';
        }
        echo '	</div>';
        echo '</div>';

        echo '<div id="hbond_shell" style="padding-bottom: 10px;">';
        echo '	<div class="ui-accordion ui-widget">';
        if($p_hbs < 0.05) {
            echo '		<div id="hbshell_header" class="ui-state-error ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="hbshell_status" class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span><strong>Hydrogen bonds in the shell</strong></p>';
            echo '		</div>';
        } else {
            echo '		<div id="hbshell_header" class="ui-state-highlight ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="hbshell_status" class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span><strong>Hydrogen bonds in the shell</strong></p>';
            echo '		</div>';
        }
        echo '	</div>';
        generateImage($hbspdf, $tmpdir,"hbond-shell");
        echo '	<div id="hbshell_content" class="ui-accordion-content ui-widget-content ui-helper-reset ui-corner-bottom ui-accordion-content-active result-content" style="border-top:0px;">';
        echo '		<div id="hbshell_content_main" class="result-content">';
        echo '			<table class=queryTable width=350 cellpadding=2 align=center>';
        echo '				<tr> <td> # Unsatisfied hydrogen bonds in the shell </td> <td> : </td> <td> '.$json_hash[ 'shell_unsatisfied'].'</td> </tr>';
        echo '				<tr> <td> p-value from the following distribution </td> <td> : </td> <td>'.sprintf("%.3g",$p_hbs).' </td> </tr>';
        echo '			</table>';
        echo '			</br>';
        echo '			<img class="img550" src="exec/'.$tmpdir.'/'.$tmpdir.'-hbond-shell.jpeg">';
        echo '		</div>';
        echo '	</div>';
        echo '</div>';

        echo '<div id="hbond_core" style="padding-bottom: 10px;">';
        echo '	<div class="ui-accordion ui-widget">';
        if($p_hbc < 0.05) {
            echo '		<div id="hbcore_header" class="ui-state-error ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="hbcore_status" class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span><strong>Hydrogen bonds in the core</strong></p>';
            echo '		</div>';
        } else {
            echo '		<div id="hbcore_header" class="ui-state-highlight ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="hbcore_status" class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span><strong>Hydrogen bonds in the core</strong></p>';
            echo '		</div>';
        }
        echo '	</div>';
        generateImage($hbcpdf, $tmpdir,"hbond-buried");
        echo '	<div id="hbcore_content" class="ui-accordion-content ui-widget-content ui-helper-reset ui-corner-bottom ui-accordion-content-active result-content" style="border-top:0px;">';
        echo '		<div id="hbcore_content_main" class="result-content">';
        echo '			<table class=queryTable width=350 cellpadding=2 align=center>';
        echo '				<tr> <td> # Unsatisfied hydrogen bonds in the core </td> <td> : </td> <td> '.$json_hash[ 'unsatisfied'].'</td> </tr>';
        echo '				<tr> <td> p-value from the following distribution </td> <td> : </td> <td>'.sprintf("%.3g",$p_hbc).' </td> </tr>';
        echo '			</table>';
        echo '			</br>';
        echo '			<img class="img550" src="exec/'.$tmpdir.'/'.$tmpdir.'-hbond-buried.jpeg">';
        echo '		</div>';
        echo '	</div>';
        echo '</div>';

        echo '<div id="sasa" style="padding-bottom: 10px;">';
        echo '	<div class="ui-accordion ui-widget">';
        if($p_msa < 0.05) {
            echo '		<div id="sasa_header" class="ui-state-error ui-helper-reset ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="sasa_status" class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span><strong>Surface area</strong></p>';
            echo '		</div>';
        } else {
            echo '		<div id="sasa_header" class="ui-state-highlight ui-helper-reset ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="sasa_status" class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span><strong>Surface area</strong></p>';
            echo '		</div>';
        }
        echo '	</div>';
        generateImage($msapdf, $tmpdir,"msa");
        echo '	<div id="sasa_content" class="ui-accordion-content ui-widget-content ui-helper-reset ui-corner-bottom ui-accordion-content-active result-content" style="border-top:0px;">';
        echo '		<div id="sasa_content_main" class="result-content">';
        echo '			<table class=queryTable cellpadding=2 width=400 align=center>';
        echo '				<thead> <tr class=queryTitle align=center> <td> Surface </td> <td> Area (&Aring;<sup>2</sup>)</td> <td> p-value </td> </thead>';
        echo '				<tbody> <tr align=center> <td> Solvent accessible </td> <td> '.$json_hash[ 'sasa'].' </td> <td> '.sprintf("%.3g",$p_sasa).' </td> </tr>';
        echo '				<tr align=center> <td> Solvent excluded </td> <td> '.$json_hash[ 'msa'].' </td> <td> '.sprintf("%.3g",$p_msa).' </td> </tr> </tbody>';
        echo '			</table></br>';
        echo '			<img class="img550" src="exec/'.$tmpdir.'/'.$tmpdir.'-msa.jpeg">';
        echo '		</div>';
        echo '	</div>';
        echo '</div>';

        echo '<div id="void" style="padding-bottom: 10px;">';
        echo '	<div class="ui-accordion ui-widget">';
        if($p_voids < 0.05) {
            echo '		<div id="void_header" class="ui-state-error ui-helper-reset ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="void_status" class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span><strong>Voids</strong></p>';
            echo '		</div>';
        } else {
            echo '		<div id="void_header" class="ui-state-highlight ui-helper-reset ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="void_status" class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span><strong>Voids</strong></p>';
            echo '		</div>';
        }
        echo '	</div>';
        generateImage($voidpdf, $tmpdir,"voids");
        echo '	<div id="void_content" class="ui-accordion-content ui-widget-content ui-helper-reset ui-corner-bottom ui-accordion-content-active result-content" style="border-top:0px;">';
        echo '		<div id="void_content_main" class="result-content">';
        echo '			<table class=queryTable width=350 cellpadding=2 align=center>';
        echo '				<tr> <td> Void volume score </td> <td> : </td> <td> '.sprintf("%.3g",$json_hash[ 'voidvolume_rescaled']).'</td> </tr>';
        echo '				<tr> <td> p-value from the following distribution </td> <td> : </td> <td>'.sprintf("%.3g",$p_voids).' </td> </tr>';
        echo '			</table>';
        echo '			</br>';
        echo '			<img class="img550" src="exec/'.$tmpdir.'/'.$tmpdir.'-voids.jpeg">';
        echo '		</div>';
        echo '	</div>';
        echo '</div>';

        echo '<div id="geom" style="padding-bottom: 10px;">';
        echo '	<div class="ui-widget">';
        if($json_hash[ 'bonds'] == 1 || $json_hash[ 'angles'] == 1 || $json_hash[ 'dihe'] == 1 || $json_hash[ 'omega'] == 1) {
            echo '		<div id="geom_header" class="ui-state-error ui-helper-reset ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="geom_status" class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span><strong>Protein geometry</strong></p>';
            echo '		</div>';
        } else {
            echo '		<div id="geom_header" class="ui-state-highlight ui-helper-reset ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="geom_status" class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span><strong>Protein geometry</strong></p>';
            echo '		</div>';
        }
        echo '	</div>';
        echo '	<div id="geom_content" class="ui-accordion-content ui-widget-content ui-helper-reset ui-corner-bottom ui-accordion-content-active result-content" style="border-top:0px;">';
        echo '		<div id="geom_content_main" class="result-content">';
        echo '			<div class=hspacer10></div>';
        echo '			<div id="tabs">';
        echo '				<ul>';
        echo '				<li><a href="#bonds">Bonds</a></li>';
        echo '				<li><a href="#angles">Angles</a></li>';
        echo '				<li><a href="#dihedrals">Dihedrals</a></li>';
        echo '				<li><a href="#backbone">Backbone</a></li>';
        echo '				</ul>';
        echo '				<div id="bonds" style="font-size: 0.9em;">';
        if($json_hash[ 'bonds'] == 0) {
            echo '				All bond lengths are within acceptable range.';
        } else {
            echo '				<table class=queryTable cellpadding=2 align=center width=100%>';
            echo '					<thead>';
            echo '						<tr align=center class=queryTitle>';
            echo '							<th>Chain</th>';
            echo '							<th>Bond</th>';
            echo '							<th>Length<sub>obs</sub></th>';
            echo '							<th>Length<sub>acc-min</sub></th>';
            echo '							<th>Length<sub>acc-max</sub></th>';
            echo '							<th>P(Length)</th>';
            echo '						</tr>';
            echo '					</thead>';
            echo '					<tbody>';
            $fh = fopen("exec/$tmpdir/$tmpdir.bonds.out.gz","w");
            fwrite($fh,$bonds);
            fclose($fh);
            unset($output);
            exec("gunzip -f exec/$tmpdir/$tmpdir.bonds.out.gz", $output);
            $fh = fopen("exec/$tmpdir/$tmpdir.bonds.out","r");
            while($line=fgets($fh)) {
                $fields = preg_split("/[\s\t]+/",$line);
                echo '					<tr align=center>';
                echo '						<td>'.$fields[1].'</td>';
                echo '						<td>'.$fields[2].':'.$fields[0].' --- '.$fields[6].':'.$fields[4].'</td>';
                echo '						<td>'.$fields[7].'</td>';
                echo '						<td>'.$fields[8].'</td>';
                echo '						<td>'.$fields[9].'</td>';
                echo '						<td>'.$fields[10].'</td>';
                echo '					</tr>';
            }
            fclose($fh);
            echo '					</tbody>';
            echo '				</table>';
        }
        echo '				</div>';
        echo '				<div id="angles" style="font-size: 0.9em;">';
        if($json_hash[ 'angles'] == 0) {
            echo '				The angles for all residues are within acceptable range.';
        } else {
            echo '				<table class=queryTable cellpadding=2 align=center width=100%>';
            echo '					<thead>';
            echo '						<tr align=center class=queryTitle>';
            echo '							<th>Chain</th>';
            echo '							<th>Angle</th>';
            echo '							<th>Angle<sub>obs</sub></th>';
            echo '							<th>Angle<sub>acc-min</sub></th>';
            echo '							<th>Angle<sub>acc-max</sub></th>';
            echo '							<th>P(angle)</th>';
            echo '						</tr>';
            echo '					</thead>';
            echo '					<tbody>';
            $fh = fopen("exec/$tmpdir/$tmpdir.angles.out.gz","w");
            fwrite($fh,$angles);
            fclose($fh);
            unset($output);
            exec("gunzip -f exec/$tmpdir/$tmpdir.angles.out.gz", $output);
            $fh = fopen("exec/$tmpdir/$tmpdir.angles.out","r");
            while($line=fgets($fh)) {
                $fields = preg_split("/[\s\t]+/",$line);
                echo '					<tr align=center>';
                echo '						<td>'.$fields[1].'</td>';
                echo '						<td>'.$fields[2].':'.$fields[0].' --- '.$fields[6].':'.$fields[4].' --- '.$fields[10].':'.$fields[8].'</td>';
                echo '						<td>'.$fields[11].'</td>';
                echo '						<td>'.$fields[12].'</td>';
                echo '						<td>'.$fields[13].'</td>';
                echo '						<td>'.$fields[14].'</td>';
                echo '					</tr>';
            }
            fclose($fh);
            echo '					</tbody>';
            echo '				</table>';
        }
        echo '				</div>';
        echo '				<div id="dihedrals" style="font-size: 0.9em;">';
        if($json_hash[ 'phipsi'] == 0) {
            echo '				The phi/psi angles for all residues are within acceptable range.';
        } else {
            $fh = fopen("exec/$tmpdir/$tmpdir-phipsi.pdf.gz","w");
            fwrite($fh, $phipsipdf);
            fclose($fh);
            unset($output);
            exec("gunzip -f exec/$tmpdir/$tmpdir-phipsi.pdf.gz",$output);
            unset($output);
            exec("convert -resize 600 -density 300x300 -flatten exec/$tmpdir/$tmpdir-phipsi.pdf exec/$tmpdir/$tmpdir-phipsi-thumb.jpeg", $output);
            echo '			<img src="exec/'.$tmpdir.'/'.$tmpdir.'-phipsi-thumb.jpeg" title="view file" border="0px">';
            echo '			<div>';
            echo '				<div class="filteraction" style="font-size: 0.9em;">';
            echo '					<input type=button class="submitbtn filteractionbtn" name="generate_rama_plot" id="generate_ramaplot" value="Generate Hi-res PDF">';
            echo '					<input type=button class="submitbtn filteractionbtn" name="download_rama_plot" id="download_ramaplot" value="Download">';
            echo '				</div>';
            echo '				<div class="cdiv"></div>';
            echo '				<div class="ui-overlay" id="ramaplot_shadow">';
            echo '					<div class="ui-widget-shadow ui-corner-all" style="width: 207px; height: 92px; position: absolute; left: 450px; top: 440px;">';
            echo '					</div>';
            echo '				</div>';
            echo '				<div id="ramaplot_content" style="position: absolute; width: 185px; height: 70px;left: 450px; top: 440px; padding: 10px;" class="ui-widget ui-widget-content ui-corner-all">';
            echo '					<div class="ui-dialog-content ui-widget-content" style="background: none; border: 0;">';
            echo '						<table align=center>';
            echo '							<tr>';
            echo '								<td><img src="style/img/logo.png" width=60>&nbsp;&nbsp;</td>';
            echo '								<td valign=middle><img src="style/img/generating.gif" width=30></td>';
            echo '								<td><img id="imgformat" src="style/img/pdf.png" width=60></td>';
            echo '							</tr>';
            echo '						</table>';
            echo '					</div>';
            echo '				</div>';	
            echo '			</div>';
            echo '				<div class=hspacer10></div>';
            echo '				<table class=queryTable cellpadding=2 align=center width=50%>';
            echo '					<thead>';
            echo '						<tr align=center class=queryTitle>';
            echo '							<th>Chain</th>';
            echo '							<th>Residue</th>';
            echo '							<th>&phi;</th>';
            echo '							<th>&psi;</th>';
            echo '						</tr>';
            echo '					</thead>';
            echo '					<tbody>';
            $fh = fopen("exec/$tmpdir/$tmpdir.dihe.out.gz","w");
            fwrite($fh,$phipsi);
            fclose($fh);
            unset($output);
            exec("gunzip -f exec/$tmpdir/$tmpdir.dihe.out.gz", $output);
            $fh = fopen("exec/$tmpdir/$tmpdir.dihe.out","r");
            while($line=fgets($fh)) {
                $fields = preg_split("/[\s\t]+/",$line);
                list($chain,$resid) = explode("-",$fields[0]);
                echo '					<tr align=center>';
                echo '						<td>'.$chain.'</td>';
                echo '						<td>'.$resid.'</td>';
                echo '						<td>'.$fields[1].'</td>';
                echo '						<td>'.$fields[2].'</td>';
                echo '					</tr>';
            }
            fclose($fh);
            echo '					</tbody>';
            echo '				</table>';
        }
        echo '				</div>';
        echo '				<div id="backbone" style="font-size: 0.9em;">';
        if($json_hash[ 'omega'] == 0) {
            echo '				The backbone of this protein is not distorted.';
        } else {
            echo '				<table class=queryTable cellpadding=2 align=center width=100%>';
            echo '					<thead>';
            echo '						<tr align=center class=queryTitle>';
            echo '							<th>Chain</th>';
            echo '							<th>Residue</th>';
            echo '							<th>Name</th>';
            echo '							<th>&omega;</th>';
            echo '							<th>&omega;<sub>acc-min</sub></th>';
            echo '							<th>&omega;<sub>acc-max</sub></th>';
            echo '							<th>P(&omega;)x10<sup>3</sup></th>';
            echo '						</tr>';
            echo '					</thead>';
            echo '					<tbody>';
            $fh = fopen("exec/$tmpdir/$tmpdir.omega.out.gz","w");
            fwrite($fh,$omega);
            fclose($fh);
            unset($output);
            exec("gunzip -f exec/$tmpdir/$tmpdir.omega.out.gz",$output);
            $fh = fopen("exec/$tmpdir/$tmpdir.omega.out","r");
            while($line=fgets($fh)) {
                $fields = preg_split("/[\s\t]+/",$line);
                echo '				<tr align=center>';
                echo '					<td>'.$fields[0].'</td>';
                echo '					<td>'.$fields[1].'</td>';
                echo '					<td>'.$fields[2].'</td>';
                echo '					<td>'.$fields[3].'</td>';
                echo '					<td>'.$fields[4].'</td>';
                echo '					<td>'.$fields[5].'</td>';
                echo '					<td>'.$fields[6].'</td>';
                echo '				</tr>';
            }
            fclose($fh);
            echo '					</tbody>';
            echo '				</table>';
        }
        echo '				</div>';
        echo '			</div>';
        echo '		</div>';
        echo '	</div>';
        echo '</div>';

        echo '<div id="schain" style="padding-bottom: 10px;">';
        echo '	<div class="ui-widget">';
        if($json_hash[ 'sczout'] == 1) {
            echo '		<div id="schain_header" class="ui-state-error ui-helper-reset ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="schain_status" class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span><strong>Side chain integrity</strong></p>';
            echo '		</div>';
        } else {
            echo '		<div id="schain_header" class="ui-state-highlight ui-helper-reset ui-corner-all" style="cursor: pointer; padding: 0pt 0.7em;">';
            echo '			<p><span id="schain_status" class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span><strong>Side chain integrity</strong></p>';
            echo '		</div>';
        }
        echo '	</div>';
        echo '	<div id="schain_content" class="ui-accordion-content ui-widget-content ui-helper-reset ui-corner-borrom ui-accordion-content-active result-content" style="border-top:0px;">';
        echo '		<div id="void_content_main" class="result-content">';
        if($json_hash[ 'sczout'] == 0) {
            echo '			None of the side chains have anamolous chi angles </br>';
        } else {
            echo '			<table class=queryTable cellpadding=2 align=center width=80%>';
            echo '				<thead>';
            echo '					<tr align=center class=queryTitle>';
            echo '						<th width=10><input class=chkbox type=checkbox id=selallres value="Select">Select</th>';
            echo '						<th>ID</th>';
            echo '						<th>Name</th>';
            echo '						<th>P(&Chi;<sub>1</sub>)x10<sup>3</sup></th>';
            echo '						<th>P(&Chi;<sub>2</sub>)x10<sup>3</sup></th>';
            echo '						<th>P(&Chi;<sub>3</sub>)x10<sup>3</sup></th>';
            echo '						<th>P(&Chi;<sub>4</sub>)x10<sup>3</sup></th>';
            echo '					</tr>';
            echo '				</thead>';
            echo '				<tbody>';
            $fh = fopen("exec/$tmpdir/$tmpdir.scz.out.gz","w");
            fwrite($fh,$sczout);
            fclose($fh);
            unset($output);
            exec("gunzip -f exec/$tmpdir/$tmpdir.scz.out.gz",$output);
            $fh = fopen("exec/$tmpdir/$tmpdir.scz.out","r");
            $chkbox_cnt = 0;
            while($line=fgets($fh)) {
                $fields = explode(" ",$line);
                $chkbox_cnt++;
                echo '				<tr>';
                echo '					<td align=center>';
                echo '						<input type=checkbox name="sel_'.$fields[0].'">';
                echo '					</td>';
                echo '					<td align=right>';
                echo '						'.$fields[0];
                echo '					</td>';
                echo '					<td align=center>';
                echo '						'.$fields[1];
                echo '					</td>';
                for($i=2;$i<6;$i++) {
                    echo '					<td align=center>';
                    if(!empty($fields[$i])) {
                        $chicolor = ($fields[$i]<0.045) ? "#990000" : "#333333";
                        echo '						<font color='.$chicolor.'>'.sprintf("%.3g",$fields[$i]).'</font>';
                    }
                    echo '					</td>';
                }
                echo '				</tr>';
            }
            fclose($fh);
            echo '				</tbody>';
            echo '			</table>';
            echo '			<div class=hspacer10></div>';
        }
        if($json_hash[ 'sczout'] == 1) {
            echo '		<div id="submit_to_medusa">';
            echo '			<div class="results filterinfo">';
            #echo '				Select the side residues to refine side chains.';
            echo '			</div>';
            echo '			<div class="filteraction">';
            echo '				<input type=button class="submitbtn filteractionbtn" name="dld-refined" id="download_refined" value="Download refined PDB">';
            echo '				<input type=button class="submitbtn filteractionbtn" name="submit_to_medusa" id="refine_sc" value="Refine side chains">';
            echo '			</div>';
            echo '		</div>';
        }
        echo '	</div>';
        echo '</div>';

        echo '<div id="optionbar" class="optionbar">';
        echo '<div id="computedby" class="ldiv">';
        echo '	Generated by : Gaia';
        echo '</div>';
        echo '<div id="downloadpdf" style="position:relative;" class="rdiv fakewindowcontain">';
        echo '	Generate : ';
        echo '	<span id="generate_fullreport" style="cursor:pointer; color:#990000;">';
        echo '		Full report';
        echo '	</span>';
        echo '	<span id="fullreport_options">';
        echo '		<a href="#" id="full_view"><img style="vertical-align: middle; border: 0px; width: 14px;" src="style/img/view.png"></a>';
        echo '		<a href="#" id="full_download"><img style="vertical-align: middle; border: 0px; width: 14px; cursor:pointer;" src="style/img/download.png"></a>';
        echo '	</span> ';
        echo '	| ';
        echo '	<span id="generate_summary" style="cursor:pointer; color: #990000;">';
        echo '		Summary ';
        echo '	</span>';
        echo '	<span id="summary_options">';
        echo '		<a href="#" id="summary_view"><img style="vertical-align: middle; border: 0px; width: 14px;" src="style/img/view.png"></a>';
        echo '		<a href="#" id="summary_download"><img style="vertical-align: middle; border: 0px; width: 14px;cursor: pointer;" src="style/img/download.png"></a>';
        echo '	</span>';
        echo '	| ';
        echo '	<span id="generate_session" style="cursor:pointer; color: #990000;">';
        echo '		PyMOL Script ';
        echo '	</span>';
        echo '	<span id="session_options">';
        #echo '		<a href="#" id="session_view"><img style="vertical-align: middle; border: 0px; width: 14px;" src="style/img/view.png"></a>';
        echo '		<a href="#" id="session_download"><img style="vertical-align: middle; border: 0px; width: 14px; cursor: pointer;" src="style/img/download.png"></a>';
        echo '	</span>';
        echo '	<div class="ui-overlay" id="processing_shadow">';
        echo '		<div class="ui-widget-shadow ui-corner-all" style="width: 207px; height: 92px; position: absolute; left: 100px; top: -40px;">';
        echo '		</div>';
        echo '	</div>';
        echo '	<div id="processing_content" style="position: absolute; width: 185px; height: 70px;left: 100px; top: -40px; padding: 10px;" class="ui-widget ui-widget-content ui-corner-all">';
        echo '		<div class="ui-dialog-content ui-widget-content" style="background: none; border: 0;">';
        echo '			<table align=center>';
        echo '				<tr>';
        echo '					<td><img src="style/img/logo.png" width=60>&nbsp;&nbsp;</td>';
        echo '					<td valign=middle><img src="style/img/generating.gif" width=30></td>';
        echo '					<td><img id="imgformat" src="style/img/pdf.png" width=60></td>';
        echo '				</tr>';
        echo '			</table>';
        echo '		</div>';
        echo '	</div>';	
        echo '	<div class=hspacer10></div>';
        echo '	<div class=hspacer10></div>';
        echo '</div>';
        echo '</div>';
    }
}

?>
        </div>
    </div>
</body>
</html>
