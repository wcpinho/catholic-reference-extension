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

To prevent the CRE from transforming text which appears to be a scripture reference,
put an exclamation mark before the reference (e.g. !Matthew 28:20).

To reference the Catechism of the Catholic Church, use paragraph numbers.
Multiple paragraphs can be enumerated using commas and dashes.  Examples:

CCC 1234,1237-1239
CCC pp1234,1237-1239
CCC paragraph 1234
CCC paragraphs 1234,1237

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

class CathRefExt {
    public $book_numbers = array(
        'ge' => 1,
        'gen' => 1,
        'genesis' => 1,
        'ex' => 2,
        'exo' => 2,
        'exod' => 2,
        'exodus' => 2,
    );
    
    public $book_names = array(
        1 => 'Genesis',
        2 => 'Exodus',
    );
    
    private $wp_option_name = "catholic-reference-extension-options";

    function __construct() {
        $this->drb_dir = "/misc/svn/catholic-reference/trunk/texts/drb";
        $this->ccc_dir = "/misc/svn/catholic-reference/trunk/texts/ccc";
        $this->popups = array();

        add_action( 'wp_head', array( &$this, 'header' ) );
        add_action( 'admin_head', array( &$this, 'admin_header' ) );
        add_filter( 'the_content', array( &$this, 'filter' ) );
        add_action( 'admin_menu', array( &$this, 'options_page_adder' ) );
        add_action( 'activate_catholic-reference/catholic-reference.php', array( &$this, 'on_activation' ) );
    }
    
    function get_config() {
        // Defaults
        $config = array(
            'show_popup_on_hover' => true,
        );
        
        // Stored options
        $stored_config = get_option( $this->wp_option_name );
        if( ! empty( $stored_config ) ) {
            foreach( $stored_config as $key => $value ) {
                $config[ $key ] = $value;
            }
        }
        
        // Save options
        update_option( $this->wp_option_name, $config );
        
        return $config;
    }
    
    function on_activation() {
        get_config();
    }
    
    /* ****************************************** */
    
    function header() {
        ?>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php print get_settings( 'siteurl' ); ?>/wp-content/plugins/catholic-reference/catholic-reference.css" />
        <script type="text/javascript" src="<?php print get_settings( 'siteurl' ); ?>/wp-includes/js/jquery/jquery.js"></script>
        <script type="text/javascript" src="<?php print get_settings( 'siteurl' ); ?>/wp-content/plugins/catholic-reference/catholic-reference.js"></script>
        <?php
        $config = $this->get_config();
        if( $config[ 'show_popup_on_hover' ] ) {
            ?><script type="text/javascript" src="<?php print get_settings( 'siteurl' ); ?>/wp-content/plugins/catholic-reference/js/option-hover.js"></script><?php
        } else {
            ?><script type="text/javascript" src="<?php print get_settings( 'siteurl' ); ?>/wp-content/plugins/catholic-reference/js/option-click.js"></script><?php
        }
    }
    
    function admin_header() {
        ?>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php print get_settings( 'siteurl' ); ?>/wp-content/plugins/catholic-reference/catholic-reference.css" />
        <script type="text/javascript" src="<?php print get_settings( 'siteurl' ); ?>/wp-includes/js/jquery/jquery.js"></script>
        <script type="text/javascript" src="<?php print get_settings( 'siteurl' ); ?>/wp-content/plugins/catholic-reference/catholic-reference.js"></script>
        <?php
    }
        
    function substitute_scripture( $matches ) {
        $retval = $original_span = $matches[ 0 ];
        $lead_char = $matches[ 1 ];
        if( $lead_char == "!" ) {
            $retval = substr( $retval, 1 );
        } else {
            $original_book = $matches[ 2 ];
            $book = strtolower( $original_book );
            $book_number = $this->book_numbers[ $book ] + 0;
            
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
                $popup .= "<div class='cathref_close_button' closeid='$id'><div class='cathref_close_button_highlight'></div></div>";
                $popup .= $this->book_names[ $book_number ] . " $chapter$verse_string";
                $popup .= "</div>";
                
                // Body
                $popup .= "<div class='scripture_text'>";
                $verses_added = 0;
                $lines = file( $this->drb_dir . "/$book_number.book", FILE_IGNORE_NEW_LINES );
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
                            $verses_added++;
                        }
                    }
                }
                
                $popup .= "</div>";
                $popup .= "</div>";
                
                $popup1 .= $popup;
                $popup2 .= $popup;
                
                if( $verses_added > 0 ) {
                    $this->popups[] = $popup1;
                    $this->popups[] = $popup2;
                } else {
                    $retval = $original_span;
                }
            }
        }
        
        return $retval;
    }
    
    function substitute_ccc( $matches ) {
        $original_span = array_shift( $matches );
        $ranges = array();
        foreach ( $matches as $range ) {
            if( preg_match( "/(\\d+)[^0-9]+(\\d+)/", $range, $range_matches ) ) {
                $ranges[] = array( 'start' => $range_matches[ 1 ], 'end' => $range_matches[ 2 ] );
            } else {
                preg_match( "/(\\d+)/", $range, $range_matches );
                $ranges[] = array( 'start' => $range_matches[ 1 ], 'end' => $range_matches[ 1 ] );
            }
        }
        
        $paras = array();
        $range_strs = array();
        foreach( $ranges as $range ) {
            for( $i = $range[ 'start' ]; $i <= $range[ 'end' ]; $i++ ) {
                $paras[] = $i;
            }
            if( $range[ 'start' ] == $range[ 'end' ] ) {
                $range_strs[] = $range[ 'start' ];
            } else {
                $range_strs[] = $range[ 'start' ] . "-" . $range[ 'end' ];
            }
        }
        
        $id = ( microtime() + rand( 0, 1000 ) );
        
        $popup1 = "<div class=\"ccc_popup\" popid=\"$id\">";
        $popup2 = "<div class=\"ccc_popup_shadow\" popid=\"$id\">";
        $popup = "";
            
        // Header
        $popup .= "<div class='ccc_header'>";
        $popup .= "<div class='cathref_close_button' closeid='$id'><div class='cathref_close_button_highlight'></div></div>";
        $popup .= "CCC " . join( ',', $range_strs );
        $popup .= "</div>";
        
        // Body
        
        $popup .= "<div class='ccc_text'>";
        
        foreach( $paras as $para ) {
            $x = ( (int)( $para / 100 ) ) * 100;
            $y = $x + 99;
            $lines = file( $this->ccc_dir . "/ccc-$x-$y.txt" , FILE_IGNORE_NEW_LINES );
            foreach ( $lines as $line ) {
                $parts = explode( "\t", $line );
                $file_para = array_shift( $parts );
                if( $para == $file_para ) {
                    $popup .= "<div class='cccp'>";
                    $popup .= "<span class='paragraph_number'>&para;$para</span> ";
                    $popup .= array_shift( $parts );
                    if( count( $parts ) > 0 ) {
                        $popup .= "<p>";
                        $popup .= join( '</p><p>', $parts );
                        $popup .= "</p>";
                    }
                    $popup .= "</div>";
                }
            }
        }
        
        $popup .= "</div>";
        $popup .= "</div>";
        
        $popup1 .= $popup;
        $popup2 .= $popup;
        
        $this->popups[] = $popup1;
        $this->popups[] = $popup2;
        
        return "<span class=\"ccc_reference\" refid=\"$id\">$original_span</span>";
    }
    
    function filter( $content ) {
        $content = preg_replace_callback(
            "/(.)((?:\\d+ +)?[A-Z][a-z]+)\\.? +(\\d+)(?: *: *(\\d+)(?: *(-|\\.{2,}) *(\\d+))?)?/",
            array( &$this, 'substitute_scripture' ),
            $content
        );
        $content = preg_replace_callback(
            "/CCC p?(?:p|aragraphs?)? *(\\d+(?: *- *\\d+)?)" . "(?: *, *(\\d+(?: *- *\\d+)?))*/",
            array( &$this, 'substitute_ccc' ),
            $content
        );
        
        foreach ( $this->popups as $popup ) {
            $content .= $popup;
        }
    
        return $content;
    }
    
    /* ******************************************
     * Options and configuration
     */
    
    function options_page() {
        $config = $this->get_config();
        if( isset( $_POST[ 'cathref_submit' ] ) ) {
            if( isset( $_POST[ 'show_popup_on_hover' ] ) ) {
                $config[ 'show_popup_on_hover' ] = (bool) $_POST[ 'show_popup_on_hover' ];
            }
            /*
            if( isset( $_POST[ '' ] ) ) {
                
            }
            */
            
            update_option( $this->wp_option_name, $config );
            ?>
            <div class="cathref_config_notice">
            <?php
            _e( 'Configuration saved.', 'catholic-reference' );
            ?>
            </div>
            <?php
        }
        ?>
        <div class="cathref_config">
        
        <h2>Catholic Reference Extension</h2>
        
        <form method="POST" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
        
            <div>
            Show popups when references are:
            <input type="radio" name="show_popup_on_hover" value="1" <?php
                $config[ 'show_popup_on_hover' ] ? _e( 'checked', 'catholic-reference' ) : ''
            ?> />hovered over &nbsp;
            <input type="radio" name="show_popup_on_hover" value="0" <?php
                ( ! $config[ 'show_popup_on_hover' ] ) ? _e( 'checked', 'catholic-reference' ) : ''
            ?> />clicked
            </div>
            
            <input type="submit" id="cathref_submit" name="cathref_submit" value="<?php _e( 'Save Changes', 'catholic-reference' ); ?>" />
        
        </form>
        
        </div>
        <?php
    }
    
    function options_page_adder() {
        if( function_exists( 'add_options_page' ) ) {
            add_options_page(
                __(
                    'Catholic Reference Extension',
                    'catholic-reference'
                ),
                __(
                    'Catholic Reference',
                    'catholic-reference'
                ),
                'administrator',
                basename(__FILE__),
                array( &$this, 'options_page' )
            );
        }
    }
}

$catholic_reference_extension = new CathRefExt();

?>