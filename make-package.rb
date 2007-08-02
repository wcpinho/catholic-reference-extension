#!/usr/bin/env ruby

# Makes packages for the CRE.

def run( command )
    puts command
    puts `#{command}`
end

work_dir = ARGV[ 1 ] || ( ENV[ 'HOME' ] + "/tmp" )
Dir.chdir( work_dir ) do
    if ARGV[ 0 ]
        version = ARGV[ 0 ]
        svn_dir = "tags/#{version}"
    else
        svn_dir = version = "trunk"
    end
    
    run "rm -rf catholic-reference"
    run "rm -ir catholic-reference*"
    run "svn export http://rome.purepistos.net/svn/catholic-reference/#{svn_dir}/plugin catholic-reference"
    run "zip -r -9 catholic-reference-#{version}.zip catholic-reference/"
    run "tar cjvf catholic-reference-#{version}.tar.bz2 catholic-reference/"
    run "svn export http://rome.purepistos.net/svn/catholic-reference/#{svn_dir}/texts catholic-reference/texts"
    run "zip -r -9 catholic-reference-#{version}-full.zip catholic-reference/ "
    run "tar cjvf catholic-reference-#{version}-full.tar.bz2 catholic-reference/"
end