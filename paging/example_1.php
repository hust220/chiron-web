<html>
<head>
    <title>pagination demo #1</title>
    
    <style type="text/css">
    
    body {
        font-family: Verdana;
        font-size: 85%;
    }
    
    </style>    
</head>

<body>

<?php

    // load the pagination class
    include('class.pager.php');

    // create a new object:
    $nav = new pager();
    
    // how many entries should be visible on
    // one page?
    $nav->set_records_per_page(15);    
    
    // set the current page value (from query)
    $nav->set_current_page($_GET['pg']);    
    
    // how many entries does the database contain
    $nav->set_max(70);
    
    // calculate everything
    $nav->pageCount();
    
    if (!$nav->exists_current_page()){
        // The requested page does not exist!
        die('Sorry the page does not exist!');
    }
    
?>

    <h1>pagination demo #1</h1>
    
    <p>
        Simulating a Database with 70 entries.
    </p>
    
    <hr/>

    <p>
    <?php
    
        print "SQL statement: SELECT * FROM entries "; 
        print $nav->get_sql_limit();
        print "<hr/>"; 
    
    ?>
    </p>
    
    <b>Results:</b>

    <ul>
    <?php
    
        // simulation a database request:
        
		  print $nav->get_first_id();
		  print $nav->current_page;
        for ($i = $nav->get_first_id()+1; $i <= $nav->get_last_id(); $i++){
            print '<li>Entry Num #' . $i . '</li>';
        }
    
    ?>
    </ul>
    
    <?php        
    
        print "<hr/>"; 
        
        print $nav->get_prev_link('<a href="?pg=%pg">Previous</a> - ', '<s>Previous</s> - ');
        
        print $nav->page_x_of('Showing page %cur of %all pages') . " - "; 
        print $nav->showing_from_to('Showing entries %from till %to'); 
        
        print $nav->get_next_link(' - <a href="?pg=%pg">Next</a>', ' - <s>Next</s>');
        
        print '<hr/>';
        
        print $nav->get_pages('<a href="?pg=%pg">Page %pg</a>', ', ', '<b>', '</b>');
        print '<hr/>';
        
    ?>


<!-- example select box: -->

<form action="example_1.php" method="get">

<?php
    print $nav->get_select_box('pg', 'Page ');
    print ' <input type="submit" value="Switch page" />';

?>
</form>
