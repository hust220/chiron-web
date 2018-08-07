<html>
<head>
    <title>pagination demo #2 (mysql)</title>
    
    <style type="text/css">
    
    body {
        font-family: Verdana;
        font-size: 85%;
    }
    
    </style>    
</head>

<body>

<?php

    $db = mysql_connect('localhost', 'test_user', 'test_password'); 
    mysql_select_db('test_database', $db);     


    // load the pagination class
    include('class.pagination.php');

    // create a new object:
    $nav = new pagination();
    
    // how many entries should be visible on
    // one page?
    $nav->set_per_page(20);    
    
    // set the current page value (from query)
    $nav->set_current_page($_GET['pg']);    
    
    
    $result = mysql_query('SELECT count(*) FROM entries', $db);    
    $array = mysql_fetch_array($result);
    $max = intval($array[0]);    
    
    
    // how many entries does the database contain
    $nav->set_max($max);
    
    // calculate everything
    $nav->calc();

    if (!$nav->exists_current_page()){
        // The requested page does not exist!
        die('Sorry the page does not exist!');
    }
    
?>

    <h1>pagination demo #2</h1>
    
    <p>
        Simulating a Database with 70 entries.
    </p>
    
    <hr/>

    <p>
    <?php
    
        print "SQL statement: SELECT title FROM entries "; 
        print $nav->get_sql_limit();
        print "<hr/>"; 
    
    ?>
    </p>
    
    Results:

    <ul>
    <?php
    

        $result = mysql_query('SELECT title FROM entries ' . $nav->get_sql_limit(), $db);
    
        // simulation a database request:
        while($entry = mysql_fetch_array($result)){
            print '<li>' . $entry['title'] . '</li>';
        }
    
    ?>
    </ul>
    
    <?php        
    
        print "<hr/>"; 
        print "<p>"; 
        
        print $nav->get_prev_link('<a href="?pg=%pg">Previous</a> - ', '<s>Previous</s> - ');
        
        print $nav->page_x_of('Showing page %cur of %all pages') . " - "; 
        print $nav->showing_from_to('Showing entries %from till %to'); 
        
        print $nav->get_next_link(' - <a href="?pg=%pg">Next</a>', ' - <s>Next</s>');
        
        print '</p>';
        print '<hr/>';
        print '<p>';
        
        print $nav->get_pages('<a href="?pg=%pg">Page %pg</a>', ', ', '<b>', '</b>');
        print '</p>';
        print '<hr/>';
        
    ?>


<!-- example select box: -->

<form action="example_2_mysql.php" method="get">

<?php
    print $nav->get_select_box('pg', 'Page ');
    print ' <input type="submit" value="Switch page" />';

?>
</form>

<?php

    mysql_close($db);

?>
