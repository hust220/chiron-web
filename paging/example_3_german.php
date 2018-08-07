<html>
<head>
    <title>Pagination Beispiel #3 (in deutsch)</title>
    
    <style type="text/css">
    
    body {
        font-family: Verdana;
        font-size: 85%;
    }
    
    </style>
    
</head>

<body>

<?php

    // Pagination Klasse laden
    include('class.pagination.php');

    // Ein neues Pagination Objekt anlegen
    $nav = new pagination();
    
    // Wie viele Einträge sollen auf
    // der Seite angezeigt werden
    $nav->set_per_page(15);    
    
    // Aktuelle Seite festlegen
    $nav->set_current_page($_GET['pg']);    
    
    // Wieviele Einträge enthält die Datenbank 
    // insgesamt
    $nav->set_max(70);
    
    // Alles berechnen
    $nav->calc();
    
    if (!$nav->exists_current_page()){
        // The requested page does not exist!
        die('Sorry the page does not exist!');
    }
    
?>

    <h1>Pagination Beispiel #3</h1>
    
    <p>
        Dieses Beipsiel simuliert eine Datenbank mit 70 Einträgen.
    </p>
    
    <hr/>

    <p>
    <?php
    
        print "SQL Abfrage: SELECT * FROM entries "; 
        print $nav->get_sql_limit();
        print "<hr/>"; 
    
    ?>
    </p>
    
    Ergebnisse:

    <ul>
    <?php
    
        // Datenbank abfrage simulieren:
        
        for ($i = $nav->get_first_id()+1; $i <= $nav->get_last_id(); $i++){
            print '<li>Eintrag nummer #' . $i . '</li>';
        }
    
    ?>
    </ul>
    
    <?php        
    
        print "<hr/>"; 
        
        print $nav->get_prev_link('<a href="?pg=%pg">Zurück</a> - ', '<s>Zurück</s> - ');
        
        print $nav->page_x_of('Zeige Seite %cur von %all') . " - "; 
        print $nav->showing_from_to('Sie sehen die Einträge %from bis %to'); 
        
        print $nav->get_next_link(' - <a href="?pg=%pg">Weiter</a>', ' - <s>Weiter</s>');
        
        print '<hr/>';
        
        print $nav->get_pages('<a href="?pg=%pg">Seite %pg</a>', ', ', '<b>', '</b>');
        print '<hr/>';
        
    ?>


<!-- Beispiel select box: -->

<form action="example_3_german.php" method="get">

<?php
    print $nav->get_select_box('pg', 'Seite ');
    print ' <input type="submit" value="Wechseln" />';

?>
</form>
