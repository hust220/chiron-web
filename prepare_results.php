<?php

#require('config/config.inc.php');

function prepare_results($tableresults, $jobid, $pdbid, $filetype) {

    switch($filetype) {
    case("fpdb"):
        $extension = "pdb";break;
    case("iclashr"):
        $extension = "iclash";break;
    case("fclashr"):
        $extension = "fclash";break;
    case("pml"):
        $extension = "py";break;
    case("jpeg"):
        $extension = "jpeg";break;
    }

    $filename = "$jobid.$extension";
    if($extension == "jpeg") {
        $filename = "$pdbid-$jobid.$extension";
        $query = "SELECT ps FROM $tableresults WHERE jobid='$jobid'";
        $result = mysql_query($query);
        if ($result) {
            $row = mysql_fetch_array($result);
            $fp = fopen("download/$filename.tmpgs.gz",'w');
            fwrite($fp, $row['ps']);
            fclose($fp);
            exec("source /home/html/local/env.sh; cd /home/html/chiron; zcat download/$filename.tmpgs.gz |gs -dQUIET -sDEVICE=ppmraw -r300 -sPAPERSIZE=a4 -dBATCH -dNOPAUSE -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=- - |pamflip -cw|pnmtojpeg &> download/$filename",$output);
            #exec("/bin/rm download/$filename.tmpgs.gz");
        }
    } else if($extension == "py") {
        $query = "SELECT ipdb FROM $tablejobs WHERE id='$jobid'";
        // Login requirement relaxed due to journal restrictions. Uncomment the following line to impose restrictions.
        $result = mysql_query($query) or die(mysql_error());
        if ( mysql_num_rows($result) == 0) {
            die("jobid not found");
        }
        $row = mysql_fetch_array($result);
        $content = $row[ 'ipdb'];
        $fp = fopen("download/i-$jobid.pdb.gz",'w');
        fwrite($fp, $content);
        fclose($fp);
        unset($output);
        exec("gunzip -f download/i-$jobid.pdb.gz",$output);

        $query = "SELECT fpdb, iClashR, fClashR FROM $tableresults WHERE jobid='$jobid'";
        // Login requirement relaxed due to journal restrictions. Uncomment the following line to impose restrictions.
        $result = mysql_query($query) or die(mysql_error());
        if ( mysql_num_rows($result) == 0 ) {
            die("jobid not found");
        }
        $row = mysql_fetch_array($result);
        $fpdb = $row[ 'fpdb'];
        $iclr = $row[ 'iClashR'];
        $fclr = $row[ 'fClashR'];
        $fp   = fopen("download/f-$jobid.pdb.gz",'w');
        fwrite($fp, $fpdb);
        fclose($fp);
        unset($output);
        exec("gunzip -f download/f-$jobid.pdb.gz",$output);
        $fp   = fopen("download/i-$jobid.clash.gz",'w');
        fwrite($fp, $iclr);
        fclose($fp);
        unset($output);
        exec("gunzip -f download/i-$jobid.clash.gz",$output);
        $fp   = fopen("download/f-$jobid.clash.gz",'w');
        fwrite($fp, $fclr);
        fclose($fp);
        unset($output);
        exec("gunzip -f download/f-$jobid.clash.gz",$output);
        unset($output);
        exec("python bin/pyGenerator.py download/i-$jobid.pdb download/i-$jobid.clash download/f-$jobid.pdb download/f-$jobid.clash download/$filename", $output);
        if(! is_file("download/$filename")) {
            die("Could not write py file. Please report this event to the administrator");
        }
        //unlink("download/i-$jobid.pdb");
        //unlink("download/f-$jobid.pdb");
        //unlink("download/i-$jobid.clash");
        //unlink("download/f-$jobid.clash");
    } else {
        $query = "SELECT $filetype FROM $tableresults WHERE jobid='$jobid'";
        // Login requirement relaxed due to journal restrictions. Uncomment the following line to impose restrictions.
        $result = mysql_query($query) or die("connection to database failed");
        if ( mysql_num_rows($result) == 0) {
            die("jobid not found");
        }
        $row = mysql_fetch_array($result);
        $content = $row[ $filetype];
        $fp = fopen("download/$filename",'w');
        fwrite($fp, $content);
        fclose($fp);
    }
}

