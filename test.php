<?php
/*
Plugin Name: Catholic Reference Extension for Wordpress
Plugin URI: http://blog.purepistos.net
Description: The Catholic Reference Extension makes scripture and Catechism references pop up the actual bible or Catechism text on hover.
Version: 0.7.0
Author: Pistos
Author URI: http://blog.purepistos.net

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

$cathref_book_numbers = array(
    'ge' => 1,
    'gen' => 1,
    'genesis' => 1,
    'ex' => 2,
    'exo' => 2,
    'exod' => 2,
    'exodus' => 2,
);
    
function cathref_substitute_scripture( $matches ) {
    global $cathref_book_numbers;
    
    $lead_char = $matches[ 1 ];
    $original_book = $matches[ 2 ];
    $book = strtolower( $original_book );
    $book_number = $cathref_book_numbers[ $book ];
    $retval = $matches[ 0 ];
    
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
        
        $retval = "$lead_char<span class=\"scripture\">$original_book $chapter";
        if( $start_verse ) {
            $retval .= ":" . $start_verse;
            if( $end_verse ) {
                $retval .= $verse_separator . $end_verse;
            }
        }
        $retval .= "</span>";
    }
    
    return $retval;
}

function cathref_filter( $content ) {
    $drb_file = "/misc/pistos/unpack/douay-rheims.txt";
    
    $content = preg_replace_callback(
        "/([^!])((?:\\d+ +)?[A-Z][a-z]+)\\.? +(\\d+)(?: *: *(\\d+)(?: *(-|\\.{2,}) *(\\d+))?)?/",
        'cathref_substitute_scripture',
        $content
    );

    return $content;
}

print cathref_filter( file_get_contents( $argv[ 1 ] ) );

?>