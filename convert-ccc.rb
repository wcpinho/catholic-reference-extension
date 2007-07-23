#!/usr/bin/env ruby

# Takes the downloaded CCC HTML files and extracts only the number paragraphs,
# and places them into a few text files.
# ./convert-ccc.rb <source directory> [output directory]

require 'ostruct'
require 'cgi'
require 'hpricot'

class String
    def clean
        CGI.unescapeHTML( gsub( /\s+/m, ' ' ) )
    end
end

def write_para( para )
    x = ( para / 100 ) * 100
    filename = "#{$output_dir}/ccc-#{x}-#{x+99}.txt"
    File.open( filename, 'a' ) do |f|
        f.puts "#{para}\t#{$paragraphs[ para ]}"
    end
end

source_dir = ARGV[ 0 ] or exit
$output_dir = ARGV[ 1 ] || '.'

$paragraphs = Hash.new

`rm #{$output_dir}/ccc*.txt`

counter = 0
para = nil
Dir[ "#{source_dir}/__*" ].each do |filename|
    puts filename
    
    active = false
    doc = Hpricot( File.read( filename ) )
    (doc/'p.MsoNormal').each do |p|
        inner = p.inner_html
        if inner =~ /^ *(\d+)/m
            if para
                write_para( para )
            end
            para = $1.to_i
            text = inner.clean
            if text =~ /^#{para} *(.+)/m
                text = $1
            end
            $paragraphs[ para ] = text
        elsif p[ :style ] == "margin-left:35.4pt"
            if para
                $paragraphs[ para ] << "\t" + inner.clean
            else
                puts "styled without leader:"
                puts inner
            end
        else
            #puts "** unknown"
            #puts p.to_html
            #puts "** end unknown"
        end
    end
    counter += 1
    #break if counter >= 20
end

if para
    write_para( para )
end
