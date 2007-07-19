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
    'gen' => 1,
    'genesis' => 1,
    'exo' => 2,
    'exod' => 2,
    'exodus' => 2,
);
    
function cathref_substitute_scripture( $matches ) {
    $book = strtolower( $matches[ 1 ] );
    $book_number = $cathref_book_numbers[ $book ];
    $chapter = $matches[ 2 ];
    if( $matches[ 3 ] ) {
        $start_verse = $matches[ 3 ];
        if( $matches[ 5 ] ) {
            $verse_separator = $matches[ 4 ];
            $end_verse = $matches[ 5 ];
        } else {
            $end_verse = $start_verse;
        }
    }
    
    $retval = "<span class=\"scripture\">BN $book_number $book $chapter";
    if( $start_verse ) {
        $retval .= " " . $start_verse;
        if( $end_verse ) {
            $retval .= $verse_separator . $end_verse;
        }
    }
    $retval .= "</span>";
    
    return $retval;
}

function cathref_filter( $content ) {
    $drb_file = "/misc/pistos/unpack/douay-rheims.txt";
    
    $content = preg_replace_callback(
        "/((?:\\d+ +)?[A-Z][a-z]+)\\.? +(\\d+)(?: *: *(\\d+)(?: *(-|\\.{2,}) *(\\d+))?)?/",
        'cathref_substitute_scripture',
        $content
    );

    return $content;
}

add_filter( 'the_content', 'cathref_filter' );

?>