#!/usr/bin/env ruby

# Takes the downloaded Douay-Rheims bible text and splits it, one file per book.
# ./convert-drb.rb <bible text file> [output directory]

require 'ostruct'

def write_book( book )
    File.open( book.filename, 'w' ) do |f|
        book.chapters.each do |chapter|
            chapter.verses.each do |verse|
                f.puts "#{chapter.number}\t#{verse.number}\t#{verse.text}"
            end
        end
    end
end

def new_book( name, num_chapters )
    $book_number += 1
    book = OpenStruct.new
    book.name = name
    book.number = $book_number
    book.filename = "#{$output_dir}/#{book.number}.book"
    book.chapters = (0...num_chapters).map { |i|
        chapter = OpenStruct.new
        chapter.number = i + 1
        chapter.verses = []
        chapter
    }
    puts "#{book.number} #{book.name}"
    book
end

filename = ARGV[ 0 ] or exit
$output_dir = ARGV[ 1 ] || '.'
book = nil
$book_number = 0

IO.foreach( filename ) do |line|
    case line
        when /\* (\d+) "(.+?)"/
            num_chapters = $1.to_i
            book_name = $2
            
            if book
                write_book( book )
            end
            book = new_book( book_name, num_chapters )
        when /^ *\d+ *(\d+) *(\d+) *(.+)$/
            chapter_number = $1.to_i
            verse = OpenStruct.new
            verse.number = $2.to_i
            verse.text = $3
            book.chapters[ chapter_number - 1 ].verses << verse
        else
            $stderr.puts "Unparsed line:\n#{line}"
    end
end

if book
    write_book book
end
