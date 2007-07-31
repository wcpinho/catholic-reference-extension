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
        inner_text = p.inner_text
        if inner_text =~ /^ *(\d+)/m
            if para
                write_para( para )
            end
            para = $1.to_i
            text = inner.clean.sub( /#{para}/, '' )
            $paragraphs[ para ] = text
        elsif(
            ( not inner_text.strip.empty? ) and
            ( inner !~ /<hr/m ) and
            ( inner_text !~ /^(article|section|chapter|part (one|two|three|four)|[lxvi]+\. )/im ) and
            ( inner_text !~ /^IN BRIEF$/m )
        )
            if para
                #puts "<<#{inner}>>\n<t#{inner_text}t>"
                $paragraphs[ para ] << "\t" + inner.clean
            end
        else
            write_para( para ) if para
            para = nil
        end
    end
    counter += 1
    #break if counter >= 20
end

if para
    write_para( para )
end
