<?php

class pager {
    
    /*
    ** Configuration:
    */
    
    // Number of records per page
    var $records_per_page   = 10;
    
    // the currently selected page
    var $current_page       = 1; 

    // Total number of records
    var $num_records        = 0;

    // Should the page numbers get padded with zeros ? 
    // Example: "03" will become "003" if padding=3 
    // - zero_padding: -1 = disabled
    // - zero_padding: 3 = 003
    var $zero_padding       = -1;
    

    // these variables are needed for internal calculations:
    var $half_pages = 0;
    var $complete_pages = 0;
    var $pages = 0;
    var $_given_current_page = 0;

    /*
    ** set_records_per_page
    **
    ** Sets the number of records that are displayed on each
    ** page.
    */
    
    function set_records_per_page($n){ $this->records_per_page = $n; }

    /*
    ** get_records_per_page
    **
    ** Returns the number of entries on each page.
    */
    
    function get_records_per_page(){ return $this->records_per_page; }
    
    
    /*
    ** set_current_page
    **
    ** Sets the current page
    */
    
    function set_current_page($p){    
    
        // fix number:
        $curr_page = intval(preg_replace('/[^0-9]/', '', $p));
        $curr_page = ($curr_page<=0) ? 1 : $curr_page;
        
        $this->current_page = $curr_page;    
        $this->_given_current_page = $curr_page;    
    }
    
    
    /*
    ** get_currpage
    **
    ** Returns the current page
    */
    
    function get_currpage(){ return $this->current_page; }
    
    
    /*
    ** set_max
    **
    ** 
    */
    
    function set_max($n){ $this->num_records = intval($n); }
    
    
    /*
    ** pageCount
    **
    ** Calculates the page count.
    */
    
    function pageCount(){    
    
        $this->half_pages = $this->num_records % $this->records_per_page;
        
        $this->complete_pages = $this->num_records - $this->half_pages;
        
        $this->pages = $this->complete_pages / $this->records_per_page;
        if ($this->half_pages != 0){ $this->pages++; }
        
        // Page number is too high -> does not exist,
        // replace with last number
        if ($this->current_page > $this->pages){ 
            $this->current_page = $this->pages;
        }

    }
    
    
    /*
    ** get_first_id
    **
    ** Returns the first id.
    */
    
    function get_first_id(){            
        return ($this->current_page * $this->records_per_page) - $this->records_per_page; 
    }
    
    
    /*
    ** get_last_id
    **
    ** Returns the last id.
    */
    
    function get_last_id(){            
        $r = $this->records_per_page - $this->half_pages;
        if ($this->pages == $this->current_page){
            return ($this->records_per_page * $this->current_page) - $r; 
        }
        else {
            return ($this->records_per_page * $this->current_page); 
        }
    }
    
    
    /*
    ** get_sql_limit
    **
    ** Returns the SQL-Limit statement.
    */
    
    function get_sql_limit(){            
        return 'LIMIT '.$this->get_first_id().',' . $this->records_per_page;    
    }
    
    
    /*
    ** get_prev_page
    **
    ** Returns the previous page.
    */
    
    function get_prev_page(){
        $id = $this->current_page - 1;        
        return ($id <= 0) ? 1 : $id;
    }
    
    
    /*
    ** get_page_count
    **
    ** Returns the count of all pages.
    */
    
    function get_page_count(){ return $this->pages; }
    
    
    /*
    ** get_next_page
    **
    ** 
    */
    
    function get_next_page(){
        // create back page
        $id = $this->current_page + 1;        
        return ($id > $this->pages) ? $this->pages : $id;
    }    
    
    
    /*
    ** exists_page
    **
    ** Returns whether a page exists or not.
    */
    
    function exists_page($p){
	 echo $this->_given_current_page;
        $p = intval($p);
        return ($p > $this->pages || $p <= 0) ? false : true;
    }    
    
    
    /*
    ** exists_current_page
    **
    ** Returns whether the current page exists or not.
    */
    
    function exists_current_page(){
        return $this->exists_page($this->_given_current_page);
    }   
    
    
    /*
    ** get_prev_link
    **
    ** Returns the previous link.
    */
    
    function get_prev_link($link_template, $no_link=''){
        
        if ($this->pages <= 1){ return ''; }
        
        // create back page
        $back_id = $this->get_prev_page();       
            
        if ($this->current_page > 1){
            $link_template = str_replace('%pg', $back_id, $link_template);
        }
        else { $link_template = $no_link; }
        
        return $link_template;        
    }
    
    
    /*
    ** get_next_link
    **
    ** Returns the next link.
    */
    
    function get_next_link($link_template, $no_link=''){
                
        // create back page
        $id = $this->get_next_page();       
            
        if ($this->current_page < $this->pages){
            $link_template = str_replace('%pg', $id, $link_template);
        }
        else { $link_template = $no_link; }
        
        return $link_template;        
    }
    
    
    /*
    ** showing_from_to
    **
    ** 
    */
    
    function showing_from_to($link_template){
        
        $link_template = str_replace('%from', $this->get_first_id(), $link_template);
        $link_template = str_replace('%to', $this->get_last_id(), $link_template);
        
        return $link_template; 
        
    }
    
    
    /*
    ** get_pages
    **
    ** Returns the listing of all pages. The links can be formatted
    ** trought the parameters.
    */
    
    function get_pages($link_template, $spacer=" ", $s, $e){        

        $page_links = array();
        
        for ($x=1; $x <= $this->pages; $x++){        
            $num_title = ($this->zero_padding != -1 && ($this->zero_padding - strlen($x)) > 0) ? str_repeat('0', $this->zero_padding - strlen($x)) . $x : $x;
            $link = str_replace('%pg', $x, $link_template);                        
            $link = str_replace('%title', $num_title, $link);                        
            $link = ($x == $this->current_page) ? $s.$link.$e : $link;            
            $page_links[] = $link;        
        }
        
        $page_links = implode($spacer, $page_links);
        
        return $page_links;        
    }

    
    /*
    ** get_dynamic_centered_pages
    **
    ** link_template  =  Link template (Example: '<a href="?pg=%pg">%title</a>')
    ** spacer         =  Spacer (Example: ' ' or ',')
    ** start          =  Current page start Tag (Example: '<b>')
    ** end            =  Current page end Tag (Example: '</b>')
    ** per_page       =  Show X page numbers in navigation (Example: 3)
    **
    ** Returns: "«« 01 02 03 04 05 06 07 »»" (HTML)
    */
    
    function get_dynamic_centered_pages($_link_template, $_spacer, $_start, $_end, $_per_page = 3){        

        $page_links = array();
        
        $start = $this->current_page - $_per_page;
        $start = ($start < 1) ? 1 : $start;
        
        $end = $this->current_page + $_per_page;
        $end = ($end > $this->pages) ? $this->pages : $end;
        
        // first page:
        $x = 1; $num_title = '&laquo;&laquo;';
        $link = str_replace('%pg', $x, $_link_template);                        
        $link = str_replace('%title', $num_title, $link);                        
        $link = ($x == $this->current_page) ? $_start.$link.$_end : $link;            
        $page_links[] = $link;
        
        
        $dx = (($_per_page*2)-($end - $start));
        
        if (($end+$dx) > $this->pages)
            $start -= $dx;
        else
            $end += $dx;
            
        
        // repeat pages:
        for ($x = $start; $x <= $end; $x++){        
            $num_title = ($this->zero_padding != -1 && ($this->zero_padding - strlen($x)) > 0) ? str_repeat('0', $this->zero_padding - strlen($x)) . $x : $x;
            $link = str_replace('%pg', $x, $_link_template);                        
            $link = str_replace('%title', $num_title, $link);                        
            $link = ($x == $this->current_page) ? $_start.$link.$_end : $link;            
            $page_links[] = $link;
        }
        
        // last page:
        $x = $this->pages; $num_title = '&raquo;&raquo;';
        
        $link = str_replace('%pg', $x, $_link_template);                        
        $link = str_replace('%title', $num_title, $link);                        
        $link = ($x == $this->current_page) ? $_start.$link.$_end : $link;            
        $page_links[] = $link;        
        
        // combine links:
        $page_links = implode($_spacer, $page_links);        
        return $page_links;        
    }
    
    
    /*
    ** get_dynamic_parent_pages
    **
    ** link_template  =  Link template (Example: '<a href="?pg=%pg">%title</a>')
    ** spacer         =  Spacer (Example: ' ' or ',')
    ** start          =  Current page start Tag (Example: '<b>')
    ** end            =  Current page end Tag (Example: '</b>')
    ** per_page       =  Show X page numbers in navigation (Example: 8)
    **
    ** Returns: "«« « 001 002 003 004 005 006 » »»" (HTML)
    */
    
    function get_dynamic_parent_pages($_link_template='<a href="?pg=%pg">%title</a>', $_spacer=' ', $_start='<b>', $_end='</b>', $_per_page = 8){        
    
        $p_pages = ceil($this->pages / $_per_page);
        
        $start = floor($this->current_page / $_per_page) * $_per_page;
        
        if (($this->current_page % $_per_page) == 0)
            $start = (floor($this->current_page / $_per_page)-1) * $_per_page;
        
        $end = $start + $_per_page;
        
        $start++;
        
        $page_links = array();
        
        // first page:
        $x = 1; $num_title = '&laquo;&laquo;';
        $link = str_replace('%pg', $x, $_link_template);                        
        $link = str_replace('%title', $num_title, $link);                        
        $page_links[] = $link;
        
        // prev. page:
        
        $start = ($start < 1) ? 1 : $start;
        
        $x = $start - 1; 
        $x = ($x < 1) ? 1 : $x;
        
        $num_title = '&laquo;';
        $link = str_replace('%pg', $x, $_link_template);                        
        $link = str_replace('%title', $num_title, $link);                        
        $page_links[] = $link;
        
        // remove pages on last:
        if (ceil($this->current_page / $_per_page) == ceil($this->pages / $_per_page))
            $end -= $_per_page - ($this->pages % $_per_page);        

        for ($x = $start; $x <= $end; $x++){        
            $num_title = ($this->zero_padding != -1 && ($this->zero_padding - strlen($x)) > 0) ? str_repeat('0', $this->zero_padding - strlen($x)) . $x : $x;
            $link = str_replace('%pg', $x, $_link_template);                        
            $link = str_replace('%title', $num_title, $link);                        
            $link = ($x == $this->current_page) ? $_start.$link.$_end : $link;            
            $page_links[] = $link;        
        }
        
        // next page:
        $x = $end+1; 
        $x = ($x > $this->pages) ? $this->pages : $x;
        $num_title = '&raquo;';
        $link = str_replace('%pg', $x, $_link_template);                        
        $link = str_replace('%title', $num_title, $link);                        
        $page_links[] = $link;  
        
        // last page:
        $x = $this->pages; $num_title = '&raquo;&raquo;';
        $link = str_replace('%pg', $x, $_link_template);                        
        $link = str_replace('%title', $num_title, $link);                        
        $page_links[] = $link;       
        
        // combine links:
        $page_links = implode($_spacer, $page_links);
        return $page_links;  
    }
    
    
    /*
    ** get_select_box
    **
    ** Creates a select box by page numbers
    */
    
    function get_select_box($name, $prefix='', $onchange=''){        

        $options = array();
            
        for ($x=1; $x <= $this->pages; $x++){   
            $sel = ($x == $this->current_page) ? ' selected="selected"' : '';        
            $link = '<option value="'.$x.'"'.$sel.'>' . $prefix . $x . '</option>';
            $options[] = $link;        
        }
        
        $onchange = ($onchange == '') ? '' : ' onchange="'.$onchange.'"';
        
        $options = implode("\n", $options);
        $options = "<select name=\"$name\"$onchange>\n$options\n</select>";
        
        return $options;        
    }
    
    
    /*
    ** page_x_of
    **
    ** 
    */
    
    function page_x_of($link_template){
        
        $link_template = str_replace('%current', $this->current_page, $link_template);
        $link_template = str_replace('%cur', $this->current_page, $link_template);
        $link_template = str_replace('%all', $this->pages, $link_template);
        
        return $link_template;           
    }
    
    // Follow the white rabbit.
}

?>
