<html>
<head>
    <title>pagination demo #4: array</title>
    
    <style type="text/css">
    
    body {
        font-family: Verdana;
        font-size: 85%;
    }
    
    </style>    
</head>

<body>

<?php

    // example array (a list of my code snippets)
    // could also come from a file 
    
    $entries = array(
        array('add_ending_slash', 'PHP'),
        array('apply_keys', 'PHP'),
        array('array2object', 'PHP'),
        array('array_changecase', 'PHP'),
        array('array_count_values', 'PHP'),
        array('array_get_path', 'PHP'),
        array('array_limit', 'PHP'),
        array('array_map', 'PHP'),
        array('array_randomize', 'PHP'),
        array('array_range', 'PHP'),
        array('array_remove_empty', 'PHP'),
        array('array_slice', 'PHP'),
        array('array_split', 'PHP'),
        array('array_walk', 'PHP'),
        array('array_walk_debug', 'PHP'),
        array('auth', 'PHP'),
        array('bin2php', 'PHP'),
        array('block_multiple_ips', 'PHP'),
        array('caching', 'PHP'),
        array('calc_age', 'PHP'),
        array('centred_div', 'HTML'),
        array('color_inverse', 'PHP'),
        array('contains', 'PHP'),
        array('crash_ie', 'HTML'),
        array('css_cursor_examples', 'HTML'),
        array('css_pagebreak', 'HTML'),
        array('curl_example', 'PHP'),
        array('darker_color', 'PHP'),
        array('database_full_size', 'PHP'),
        array('debug_function', 'PHP'),
        array('dec2hex', 'JavaScript'),
        array('dir_list', 'PHP'),
        array('dir_size', 'PHP'),
        array('dl_speed_limit', 'PHP'),
        array('domxml_example', 'PHP'),
        array('dval', 'PHP'),
        array('elapsed_microtime', 'PHP'),
        array('ends_with', 'PHP'),
        array('example_class', 'JavaScript'),
        array('exif_thumbnail', 'PHP'),
        array('extract_emails', 'PHP'),
        array('file_download', 'PHP'),
        array('fix_badwords', 'PHP'),
        array('frameset_example', 'HTML'),
        array('frameset_killer', 'JavaScript'),
        array('ftp_example', 'PHP'),
        array('function_overloading', 'PHP'),
        array('get_abc', 'PHP'),
        array('get_between', 'PHP'),
        array('get_days_for_month', 'PHP'),
        array('get_files_by_ext', 'PHP'),
        array('glob_examples', 'PHP'),
        array('go_to_top', 'HTML'),
        array('google_texter', 'PHP'),
        array('greasemonkey_template', 'JavaScript'),
        array('headers', 'PHP'),
        array('hex2rgb', 'PHP'),
        array('hex_string', 'PHP'),
        array('htmlsql_example', 'PHP'),
        array('iframe', 'HTML'),
        array('ipadress_is_valid', 'PHP'),
        array('listdir_by_date', 'PHP'),
        array('load_and_save_a_array_dump', 'PHP'),
        array('lwp_example', 'Perl'),
        array('min_and_max', 'PHP'),
        array('ming_example', 'PHP'),
        array('misc', 'PHP'),
        array('mysql_examples', 'PHP'),
        array('natsort_example', 'PHP'),
        array('navigator_info', 'HTML'),
        array('normalize_path', 'PHP'),
        array('number_suffix', 'PHP'),
        array('open_popup_window', 'JavaScript'),
        array('path_get_last_arg', 'PHP'),
        array('perl_open_file', 'Perl'),
        array('perl_opendir', 'Perl'),
        array('perl_write_file', 'Perl'),
        array('php_system_vars', 'PHP'),
        array('phpinfo2file', 'PHP'),
        array('post_request', 'PHP'),
        array('prefix_and_suffix', 'PHP'),
        array('printf_and_sscanf', 'PHP'),
        array('python_syntax_examples', 'Python'),
        array('python_write_to_file', 'Python'),
        array('rand_split', 'PHP'),
        array('rand_str', 'PHP'),
        array('random_color', 'PHP'),
        array('random_file', 'PHP'),
        array('random_number', 'JavaScript'),
        array('random_password', 'PHP'),
        array('random_quote', 'JavaScript'),
        array('random_readable_pwd', 'PHP'),
        array('read_and_write_tab_files', 'PHP'),
        array('readable_filesize', 'PHP'),
        array('remove_duplicated_values', 'PHP'),
        array('restore_array', 'PHP'),
        array('restore_hsc', 'PHP'),
        array('rgb2hex', 'PHP'),
        array('rot13', 'PHP'),
        array('scrollable-div-box', 'HTML'),
        array('secure_redirect', 'PHP'),
        array('select_text', 'HTML'),
        array('similar_text', 'PHP'),
        array('simple_syntax_highlighting', 'PHP'),
        array('simple_text_counter', 'PHP'),
        array('snoopy_example', 'PHP'),
        array('soundex', 'PHP'),
        array('split_by_length', 'PHP'),
        array('starts_with', 'PHP'),
        array('str_middle_reduce', 'PHP'),
        array('str_trim', 'PHP'),
        array('string_prototypes', 'JavaScript'),
        array('text2links', 'PHP'),
        array('time_is_older_than', 'PHP'),
        array('time_to_load', 'PHP'),
        array('toggle_button_js', 'HTML'),
        array('toggle_checkboxes', 'HTML'),
        array('trim_array', 'PHP'),
        array('url_functions', 'PHP'),
        array('values2keys', 'PHP'),
        array('whois_query', 'PHP'),
        array('window_resolution', 'JavaScript'),
        array('wordwrap_example', 'PHP'),
        array('write', 'PHP')
    );


    // load the pagination class
    include('class.pagination.php');

    // create a new object:
    $nav = new pagination();
    
    // how many entries should be visible on
    // one page?
    $nav->set_per_page(20);    
    
    // set the current page value (from query)
    $nav->set_current_page($_GET['pg']);    
    
    // how many entries does the database contain
    $nav->set_max(count($entries));
    
    // calculate everything
    $nav->calc();
    
?>

    <h1>pagination demo #4</h1>
    
    <p>
        Using a array as database.
    </p>
    
    <hr/>

    <p>
    <?php
    
        $entries = array_slice($entries, $nav->get_first_id(), $nav->get_per_page());
    
    ?>
    </p>
    
    <b>Results:</b>

    <ul>
    <?php
    
        // simulation a database request:
        
        while (list($pos, $snippet) = each($entries)){
            print '<li>' . $snippet[0] . ' (' . $snippet[1] . ')</li>';
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

<form action="<?php print $_SERVER['PHP_SELF']; ?>" method="get">

<?php
    print $nav->get_select_box('pg', 'Page ');
    print ' <input type="submit" value="Switch page" />';

?>
</form>
