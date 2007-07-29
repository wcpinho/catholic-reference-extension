<?php
/*
Plugin Name: Catholic Reference Extension
Plugin URI: http://blog.purepistos.net/index.php/cre/
Description: The Catholic Reference Extension makes scripture and Catechism references pop up the actual bible or Catechism text.
Version: 0.8.0
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

--------------------------------------

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
        'lv' => 3,
        'lev' => 3,
        'levit' => 3,
        'leviticus' => 3,
        'num' => 4,
        'numbers' => 4,
        'de' => 5,
        'deu' => 5,
        'deut' => 5,
        'deuter' => 5,
        'deutero' => 5,
        'deuteronomy' => 5,
        'jo' => 6,
        'jos' => 6,
        'josh' => 6,
        'joshua' => 6,
        'jg' => 7,
        'judges' => 7,
        'ru' => 8,
        'ruth' => 8,
        '1 sa' => 9,
        '1 sm' => 9,
        '1 sam' => 9,
        '1 samuel' => 9,
        '2 sa' => 10,
        '2 sm' => 10,
        '2 sam' => 10,
        '2 samuel' => 10,
        '1 kn' => 11,
        '1 kg' => 11,
        '1 ki' => 11,
        '1 kings' => 11,
        '3 kn' => 11,
        '3 kg' => 11,
        '3 ki' => 11,
        '3 kings' => 11,
        '2 kn' => 12,
        '2 kg' => 12,
        '2 ki' => 12,
        '2 kings' => 12,
        '4 kn' => 12,
        '4 kg' => 12,
        '4 ki' => 12,
        '4 kings' => 12,
        '1 ch' => 13,
        '1 chr' => 13,
        '1 chron' => 13,
        '1 chronicles' => 13,
        '1 paralipomenon' => 13,
        '2 ch' => 14,
        '2 chr' => 14,
        '2 chron' => 14,
        '2 chronicles' => 14,
        '2 paralipomenon' => 14,
        'ez' => 15,
        'ezra' => 15,
        '1 es' => 15,
        '1 esdras' => 15,
        'ne' => 16,
        'neh' => 16,
        'nehemiah' => 16,
        '2 es' => 16,
        '2 esdras' => 16,
        'tb' => 17,
        'tob' => 17,
        'tobit' => 17,
        'tobias' => 17,
        'judith' => 18,
        'es' => 19,
        'est' => 19,
        'esther' => 19,
        'jb' => 20,
        'job' => 20,
        'ps' => 21,
        'pss' => 21,
        'psa' => 21,
        'psalm' => 21,
        'psalms' => 21,
        'pr' => 22,
        'pb' => 22,
        'pro' => 22,
        'prov' => 22,
        'proverb' => 22,
        'proverbs' => 22,
        'ec' => 23,
        'ecc' => 23,
        'eccl' => 23,
        'eccles' => 23,
        'ecclesiastes' => 23,
        'sg' => 24,
        'song' => 24,
        'song of songs' => 24,
        'song of songs' => 24,
        'canticle' => 24,
        'canticles' => 24,
        'canticle of canticles' => 24,
        'ws' => 25,
        'wis' => 25,
        'wisdom' => 25,
        'sr' => 26,
        'sir' => 26,
        'sirach' => 26,
        'ecclesiasticus' => 26,
        'is' => 27,
        'isa' => 27,
        'isaiah' => 27,
        'isaias' => 27,
        'je' => 28,
        'jer' => 28,
        'jerem' => 28,
        'jeremiah' => 28,
        'jeremias' => 28,
        'la' => 29,
        'lm' => 29,
        'lament' => 29,
        'lamentation' => 29,
        'lamentations' => 29,
        'ba' => 30,
        'bar' => 30,
        'baruch' => 30,
        'ez' => 31,
        'ezekiel' => 31,
        'ezechiel' => 31,
        'da' => 32,
        'dn' => 32,
        'dan' => 32,
        'daniel' => 32,
        'ho' => 33,
        'hs' => 33,
        'hosea' => 33,
        'os' => 33,
        'osee' => 33,
        'jo' => 34,
        'joel' => 34,
        'am' => 35,
        'amo' => 35,
        'amos' => 35,
        'ob' => 36,
        'oba' => 36,
        'obad' => 36,
        'obadiah' => 36,
        'abdias' => 36,
        'jonah' => 37,
        'jonas' => 37,
        'mi' => 38,
        'mic' => 38,
        'micah' => 38,
        'michaes' => 38,
        'na' => 39,
        'nah' => 39,
        'nahum' => 39,
        'ha' => 40,
        'hab' => 40,
        'hb' => 40,
        'hk' => 40,
        'haba' => 40,
        'habak' => 40,
        'habac' => 40,
        'habacuc' => 40,
        'habakkuk' => 40,
        'zp' => 41,
        'zep' => 41,
        'zeph' => 41,
        'zephaniah' => 41,
        'sophonias' => 41,
        'ha' => 42,
        'hag' => 42,
        'haggai' => 42,
        'aggeus' => 42,
        'ze' => 43,
        'zech' => 43,
        'zechariah' => 43,
        'zacharias' => 43,
        'mal' => 44,
        'ml' => 44,
        'malachi' => 44,
        'malachias' => 44,
        '1 ma' => 45,
        '1 mc' => 45,
        '1 mac' => 45,
        '1 macc' => 45,
        '1 machabees' => 45,
        '2 ma' => 46,
        '2 mc' => 46,
        '2 mac' => 46,
        '2 macc' => 46,
        '2 machabees' => 46,
        'mt' => 47,
        'mat' => 47,
        'matt' => 47,
        'matthew' => 47,
        'mk' => 48,
        'mark' => 48,
        'lk' => 49,
        'luke' => 49,
        'jn' => 50,
        'john' => 50,
        'acts' => 51,
        'ac' => 51,
        'ro' => 52,
        'rm' => 52,
        'rom' => 52,
        'romans' => 52,
        '1 co' => 53,
        '1 cor' => 53,
        '1 corinthians' => 53,
        '2 co' => 54,
        '2 cor' => 54,
        '2 corinthians' => 54,
        'ga' => 55,
        'gal' => 55,
        'galatians' => 55,
        'ep' => 56,
        'eph' => 56,
        'ephesians' => 56,
        'ph' => 57,
        'phi' => 57,
        'phil' => 57,
        'philippians' => 57,
        'co' => 58,
        'cl' => 58,
        'col' => 58,
        'coloss' => 58,
        'colossians' => 58,
        '1 th' => 59,
        '1 thes' => 59,
        '1 thess' => 59,
        '1 thessalonians' => 59,
        '2 th' => 60,
        '2 thes' => 60,
        '2 thess' => 60,
        '2 thessalonians' => 60,
        '1 ti' => 61,
        '1 tm' => 61,
        '1 tim' => 61,
        '1 timothy' => 61,
        '2 ti' => 62,
        '2 tm' => 62,
        '2 tim' => 62,
        '2 timothy' => 62,
        'ti' => 63,
        'tit' => 63,
        'titus' => 63,
        'pm' => 64,
        'philemon' => 64,
        'he' => 65,
        'hb' => 65,
        'heb' => 65,
        'hebrews' => 65,
        'ja' => 66,
        'jam' => 66,
        'js' => 66,
        'james' => 66,
        '1 pe' => 67,
        '1 pt' => 67,
        '1 peter' => 67,
        '2 pe' => 68,
        '2 pt' => 68,
        '2 peter' => 68,
        '1 jo' => 69,
        '1 jn' => 69,
        '1 john' => 69,
        '2 jo' => 70,
        '2 jn' => 70,
        '2 john' => 70,
        '3 jo' => 71,
        '3 jn' => 71,
        '3 john' => 71,
        'jd' => 72,
        'jude' => 72,
        'rv' => 73,
        'rev' => 73,
        'revelation' => 73,
        'revelations' => 73,
        'apocalypse' => 73,
        'apo' => 73,
        'apoc' => 73,
    );
    
    public $book_names = array(
        1 => 'Genesis',
        2 => 'Exodus',
        3 => 'Leviticus',
        4 => 'Numbers',
        5 => 'Deuteronomy',
        6 => 'Joshua',
        7 => 'Judges',
        8 => 'Ruth',
        9 => '1 Samuel',
        10 => '2 Samuel',
        11 => '1 Kings',
        12 => '2 Kings',
        13 => '1 Chronicles',
        14 => '2 Chronicles',
        15 => 'Ezra',
        16 => 'Nehemiah',
        17 => 'Tobit',
        18 => 'Judith',
        19 => 'Esther',
        20 => 'Job',
        21 => 'Psalm',
        22 => 'Proverbs',
        23 => 'Ecclesiastes',
        24 => 'Song of Songs',
        25 => 'Wisdom',
        26 => 'Sirach',
        27 => 'Isaiah',
        28 => 'Jeremiah',
        29 => 'Lamentations',
        30 => 'Baruch',
        31 => 'Ezekiel',
        32 => 'Daniel',
        33 => 'Hosea',
        34 => 'Joel',
        35 => 'Amos',
        36 => 'Obadiah',
        37 => 'Jonah',
        38 => 'Micah',
        39 => 'Nahum',
        40 => 'Habakkuk',
        41 => 'Zephaniah',
        42 => 'Haggai',
        43 => 'Zechariah',
        44 => 'Malachi',
        45 => '1 Maccabees',
        46 => '2 Maccabees',
        47 => 'Matthew',
        48 => 'Mark',
        49 => 'Luke',
        50 => 'John',
        51 => 'Acts',
        52 => 'Romans',
        53 => '1 Corinthians',
        54 => '2 Corinthians',
        55 => 'Galatians',
        56 => 'Ephesians',
        57 => 'Philippians',
        58 => 'Colossians',
        59 => '1 Thessalonians',
        60 => '2 Thessalonians',
        61 => '1 Timothy',
        62 => '2 Timothy',
        63 => 'Titus',
        64 => 'Philemon',
        65 => 'Hebrews',
        66 => 'James',
        67 => '1 Peter',
        68 => '2 Peter',
        69 => '1 John',
        70 => '2 John',
        71 => '3 John',
        72 => 'Jude',
        73 => 'Revelation',
    );
    
    private $wp_option_name = "catholic-reference-extension-options";

    function __construct() {
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
            'draw_shadows' => true,
            'drb_dir' => dirname( __FILE__ ) . '/texts/drb',
            'ccc_dir' => dirname( __FILE__ ) . '/texts/ccc',
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
        $this->get_config();
    }
    
    function drb_text_exists() {
        $config = $this->get_config();
        return( file_exists( $config[ 'drb_dir' ] . "/1.book" ) );
    }
    
    function ccc_text_exists() {
        $config = $this->get_config();
        return( file_exists( $config[ 'ccc_dir' ] . "/ccc-0-99.txt" ) );
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
        $config = $this->get_config();
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
                
                $popup = "";
                    
                // Header
                $popup .= "<div class='scripture_header'>";
                $popup .= "<div class='cathref_close_button' closeid='$id'><div class='cathref_close_button_highlight'></div></div>";
                $popup .= "<span class='passage'>" . $this->book_names[ $book_number ] . " $chapter$verse_string</span><br />";
                $popup .= "<span class='alternates'>View in: ";
                
                $book_no_spaces = str_replace( ' ', '', $this->book_names[ $book_number ] );
                $nab_book = strtolower( $book_no_spaces );
                $popup .= "<a href='http://www.usccb.org/nab/bible/$nab_book/$nab_book$chapter.htm#v$start_verse' target='bible'>NAB</a>";
                
                if( $book_number < 47 ) {
                    $vulg_testament = 0;
                    $vulg_book = $book_number;
                } else {
                    $vulg_testament = 1;
                    $vulg_book = $book_number - 46;
                }
                $popup .= " <a href='http://www.latinvulgate.com/verse.aspx?t=$vulg_testament&b=$vulg_book&c=$chapter#$chapter" . "_" . $start_verse . "' target='bible'>Vulg</a>";
                
                if( $book_number < 47 ) {
                    $popup .= " <a href='http://septuagint.org/LXX/$book_no_spaces/$book_no_spaces$chapter.html' target='bible'>LXX</a>";
                } else {
                    $nt_book = $book_number - 46;
                    $popup .= " <a href='http://www.greekbible.com/index.php?b=$nt_book&c=$chapter' target='bible'>Greek</a>";
                }
                
                $popup .= "</span>";
                $popup .= "</div>";
                
                // Body
                $popup .= "<div class='scripture_text'>";
                $verses_added = 0;
                $lines = file( $config[ 'drb_dir' ] . "/$book_number.book", FILE_IGNORE_NEW_LINES );
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
                
                if( $verses_added > 0 ) {
                    $popup1 = "<div class=\"scripture_popup\" popid=\"$id\">";
                    $popup1 .= $popup;
                    $this->popups[] = $popup1;
                    
                    if( $config[ 'draw_shadows' ] ) {
                        $popup2 = "<div class=\"scripture_popup_shadow\" popid=\"$id\"></div>";
                        // $popup2 .= $popup;
                        $this->popups[] = $popup2;
                    }
                } else {
                    $retval = $original_span;
                }
            }
        }
        
        return $retval;
    }
    
    function substitute_ccc( $matches ) {
        $config = $this->get_config();
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
                if( $i >= 0 && $i <= 2865 ) {
                    $paras[] = $i;
                }
            }
            if( $range[ 'start' ] == $range[ 'end' ] ) {
                $range_strs[] = $range[ 'start' ];
            } else {
                $range_strs[] = $range[ 'start' ] . "-" . $range[ 'end' ];
            }
        }
        
        $id = ( microtime() + rand( 0, 1000 ) );
        
        $popup1 = "<div class=\"ccc_popup\" popid=\"$id\">";
        $popup2 = "<div class=\"ccc_popup_shadow\" popid=\"$id\"></div>";
        $popup = "";
            
        // Header
        $popup .= "<div class='ccc_header'>";
        $popup .= "<div class='cathref_close_button' closeid='$id'><div class='cathref_close_button_highlight'></div></div>";
        $popup .= "CCC " . join( ',', $range_strs );
        $popup .= "</div>";
        
        // Body
        
        $popup .= "<div class='ccc_text'>";
        
        $paragraphs_added = 0;
        foreach( $paras as $para ) {
            $x = ( (int)( $para / 100 ) ) * 100;
            $y = $x + 99;
            $lines = file( $config[ 'ccc_dir' ] . "/ccc-$x-$y.txt" , FILE_IGNORE_NEW_LINES );
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
                    $paragraphs_added++;
                }
            }
        }
        
        $popup .= "</div>";
        $popup .= "</div>";
        
        $popup1 .= $popup;
        // $popup2 .= $popup;
        
        if( $paragraphs_added > 0 ) {
            $this->popups[] = $popup1;
            $this->popups[] = $popup2;
            return "<span class=\"ccc_reference\" refid=\"$id\">$original_span</span>";
        } else {
            return $original_span;
        }
    }
    
    function filter( $content ) {
        $book_regexp = join( '|', array_keys( $this->book_numbers ) );
    
        if( $this->drb_text_exists() ) {
            $content = preg_replace_callback(
                "/(.)($book_regexp)\\.? +(\\d+)" . "(?: *: *(\\d+)(?: *(-|\\.{2,}) *(\\d+))?)?/i",
                array( &$this, 'substitute_scripture' ),
                $content
            );
        }
        if( $this->ccc_text_exists() ) {
            $content = preg_replace_callback(
                "/CCC p?(?:p|aragraphs?)? *(\\d+(?: *- *\\d+)?)" . "(?: *, *(\\d+(?: *- *\\d+)?))*/",
                array( &$this, 'substitute_ccc' ),
                $content
            );
        }
        
        foreach ( $this->popups as $popup ) {
            $content .= $popup;
        }
        
        return $content;
    }
    
    /* ******************************************
     * Options and configuration
     */
        
    // Ensure that we have the texts to use (Scripture, Catechism, etc.)
    // Returns NULL if all texts are found.
    // Returns a notice message in a string for any missing texts.
    function check_texts( $config ) {
        $message = "";
        
        if( ! $this->drb_text_exists() ) {
            $message .= "Scripture text files not found.  Scripture references will not be active.<br />";
        }
        if( ! $this->ccc_text_exists() ) {
            $message .= "Catechism text files not found.  References to the Catechism will not be active.<br />";
        }
        
        if( ! empty( $message ) ) {
            $message .= " The texts used by the CRE can be obtained <a href='http://blog.purepistos.net/index.php/cre/' target='cre'>here</a>.<br />";
            $this->notices .= $message;
        }
    }
    
    function options_page() {
        $config = $this->get_config();
        $this->notices = "";
        if( isset( $_POST[ 'cathref_submit' ] ) ) {
            if( isset( $_POST[ 'show_popup_on_hover' ] ) ) {
                $config[ 'show_popup_on_hover' ] = (bool) $_POST[ 'show_popup_on_hover' ];
            }
            if( isset( $_POST[ 'drb_dir' ] ) ) {
                $config[ 'drb_dir' ] = $_POST[ 'drb_dir' ];
            }
            if( isset( $_POST[ 'ccc_dir' ] ) ) {
                $config[ 'ccc_dir' ] = $_POST[ 'ccc_dir' ];
            }
            $config[ 'draw_shadows' ] = isset( $_POST[ 'draw_shadows' ] );
            /*
            if( isset( $_POST[ '' ] ) ) {
                
            }
            */
            
            update_option( $this->wp_option_name, $config );
            $this->notices .= __( 'Configuration saved.<br />', 'catholic-reference' );
        }
        $this->check_texts( $config );
        ?>
        
        <?php
        if( ! empty( $this->notices ) ) {
        ?>
            <div class="cathref_config_notice">
            <?php
            echo $this->notices;
            ?>
            </div>
            <?php
        }
        ?>
        
        <div class="cathref_config">
        
        <h2>Catholic Reference Extension</h2>
        
        <form method="POST" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
        
            <h3>General</h3>
        
            <div>
            Show popups when references are:
            <input type="radio" name="show_popup_on_hover" value="1" <?php
                $config[ 'show_popup_on_hover' ] ? _e( 'checked', 'catholic-reference' ) : ''
            ?> />hovered over &nbsp;
            <input type="radio" name="show_popup_on_hover" value="0" <?php
                ( ! $config[ 'show_popup_on_hover' ] ) ? _e( 'checked', 'catholic-reference' ) : ''
            ?> />clicked
            </div>
            
            <div>
            Douay-Rheims Bible text directory:
            <input type="text" name="drb_dir" value="<?php echo $config[ 'drb_dir' ] ?>" size="40" />
            </div>
            <div>
            Catechism of the Catholic Church text directory:
            <input type="text" name="ccc_dir" value="<?php echo $config[ 'ccc_dir' ] ?>" size="40" />
            </div>
            
            <br />
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