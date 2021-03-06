#!/usr/bin/env ruby

require 'open-uri'
require 'hpricot'

def scrape_one_paragraph( pnum )
    cache_file = $out_dir + "/raw/#{pnum}.htm"
    if File.exist?( cache_file )
        raw_text = File.read( cache_file )
    else
        raw_text = `lynx --dump http://www.scborromeo.org/ccc/para/#{pnum}.htm`
        File.open( cache_file, 'w' ) do |f|
            f.puts raw_text
        end
        sleep 0.5  # throttle speed
    end
        
    text = raw_text[ /   #{pnum} (.+)   _____/m, 1 ]
    if text.nil?
        puts "Failed to parse raw text:\n#{raw_text}"
        exit 1
    else
        paras = text.split( /\n\n/ )
    end
    "#{pnum}\t" + paras.collect { |p| p.gsub( /\s+/m, ' ' ) }.join( "\t" )
end

def one_set( start_num )
    paras = Hash.new
    
    if start_num < 1
        start_num = 1
    end
    end_num = start_num + 99
    if end_num > 2865
        end_num = 2865
    end
    ( start_num..end_num).each do |num|
        paras[ num ] = scrape_one_paragraph( num )
        $stdout.print "."; $stdout.flush
    end
    
    File.open( $out_dir + "/ccc-#{start_num}-#{end_num}.txt", 'w' ) do |f|
        paras.keys.sort.each do |num|
            f.puts paras[ num ]
        end
    end
    puts "#{start_num} to #{end_num} done."
end

$out_dir = ARGV[ 0 ] or ( puts "#{$0} <output dir>"; exit )

(0..28).each do |set|
    one_set set*100
end