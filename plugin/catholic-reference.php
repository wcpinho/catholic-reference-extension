<?php
/*
Plugin Name: Catholic Reference Extension
Plugin URI: http://blog.purepistos.net
Description: The Catholic Reference Extension makes scripture and Catechism references pop up the actual bible or Catechism text on hover.
Version: 0.7.0
Author: Pistos
Author URI: http://blog.purepistos.net

Usage:

The CRE will take most common Scripture references in posts and convert them
automatically to the HTML code necessary to show popups, etc.  You can use
Full book names (e.g. Genesis 1:1) or abbreviated book names, with or without
a period (e.g. Exo. 2:10-15, Jn 3:16).

To prevent the CRE from transforming your text, put an exclamation mark
before the reference (e.g. !Matthew 28:20).


Copyright (c) 2007 Pistos
Released under the GPL license, version 2
http://www.gnu.org/licenses/gpl.txt

    This file is part of WordPress.
    WordPress is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$cathref_drb_dir = "/misc/svn/catholic-reference/trunk/texts/drb";
$cathref_ccc_dir = "/misc/svn/catholic-reference/trunk/texts/ccc";

$cathref_book_numbers = array(
    'ge' => 1,
    'gen' => 1,
    'genesis' => 1,
    'ex' => 2,
    'exo' => 2,
    'exod' => 2,
    'exodus' => 2,
);

$cathref_book_names = array(
    1 => 'Genesis',
    2 => 'Exodus',
);

function cathref_header() {
    ?>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php print get_settings( 'siteurl' ); ?>/wp-content/plugins/catholic-reference/catholic-reference.css" />
    <script type="text/javascript" src="<?php print get_settings( 'siteurl' ); ?>/wp-includes/js/jquery/jquery.js"></script>
    <script type="text/javascript" src="<?php print get_settings( 'siteurl' ); ?>/wp-content/plugins/catholic-reference/catholic-reference.js"></script>
    <?php
}
    
function cathref_substitute_scripture( $matches ) {
    global $cathref_book_numbers, $cathref_book_names, $cathref_popups, $cathref_drb_dir;
    
    $retval = $matches[ 0 ];
    $lead_char = $matches[ 1 ];
    if( $lead_char == "!" ) {
        $retval = substr( $retval, 1 );
    } else {
        $original_book = $matches[ 2 ];
        $book = strtolower( $original_book );
        $book_number = $cathref_book_numbers[ $book ] + 0;
        
        if( $book_number ) {
        
            $chapter = $matches[ 3 ];
            if( $matches[ 4 ] ) {
                $start_verse = $matches[ 4 ];
                if( $matches[ 6 ] ) {
                    $verse_separator = $matches[ 5 ];
                    $end_verse = $matches[ 6 ];
                } else {
                    $end_verse = $start_verse;
                }
            }
            
            $id = ( microtime() + rand( 0, 1000 ) );
            
            $retval = "$lead_char<span class=\"scripture_reference\" refid=\"$id\">$original_book $chapter";
            
            $verse_string = '';
            if( $start_verse ) {
                $verse_string .= ":" . $start_verse;
                if( $verse_separator && $end_verse ) {
                    $verse_string .= $verse_separator . $end_verse;
                }
            }
            $retval .= $verse_string;
            
            $retval .= "</span>";
            
            $popup1 = "<div class=\"scripture_popup\" popid=\"$id\">";
            $popup2 = "<div class=\"scripture_popup_shadow\" popid=\"$id\">";
            $popup = "";
                
            // Header
            $popup .= "<div class='scripture_header'>";
            $popup .= "<div class='close_button' closeid='$id'><div class='close_button_highlight'></div></div>";
            $popup .= $cathref_book_names[ $book_number ] . " $chapter$verse_string";
            $popup .= "</div>";
            
            // Body
            $popup .= "<div class='scripture_text'>";
            $lines = file( $cathref_drb_dir . "/$book_number.book", FILE_IGNORE_NEW_LINES );
            foreach ( $lines as $line ) {
                $parts = explode( "\t", $line, 3 );
                $line_chapter = $parts[ 0 ];
                $line_verse = $parts[ 1 ];
                $line_text = $parts[ 2 ];
                if( $line_chapter == $chapter ) {
                    if( ( $start_verse <= $line_verse ) && ( $line_verse <= $end_verse ) ) {
                        $popup .= "<div class='verse'>";
                        $popup .= "<span class='verse_number'>$line_verse</span>$line_text";
                        $popup .= "</div>";
                    }
                }
            }
            
            $popup .= "</div>";
            $popup .= "</div>";
            
            $popup1 .= $popup;
            $popup2 .= $popup;
            
            $cathref_popups[] = $popup1;
            $cathref_popups[] = $popup2;
        }
    }
    
    return $retval;
}

function cathref_filter( $content ) {
    global $cathref_popups;
    
    $drb_file = "/misc/pistos/unpack/douay-rheims.txt";
    
    $content = preg_replace_callback(
        "/(.)((?:\\d+ +)?[A-Z][a-z]+)\\.? +(\\d+)(?: *: *(\\d+)(?: *(-|\\.{2,}) *(\\d+))?)?/",
        'cathref_substitute_scripture',
        $content
    );
    
    foreach ( $cathref_popups as $popup ) {
        $content .= $popup;
    }

    return $content;
}

add_action( 'wp_head', 'cathref_header' );
add_filter( 'the_content', 'cathref_filter' );

?>