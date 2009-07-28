#!/usr/bin/env ruby

# Makes packages for the CRE.

def run( command )
  puts command
  puts `#{command}`
end

version = ARGV[ 0 ] || 'trunk'
work_dir = ARGV[ 1 ] || ( ENV[ 'HOME' ] + "/tmp" )

Dir.chdir( work_dir ) do
  run "rm -rf catholic-reference"
end

run "git archive --format=tar HEAD plugin | (cd #{work_dir} && tar xvf -)"

Dir.chdir( work_dir ) do
  run "mv plugin catholic-reference"
  run "zip -r -9 catholic-reference-#{version}.zip catholic-reference/"
  run "tar cjvf catholic-reference-#{version}.tar.bz2 catholic-reference/"
end

run "git archive --format=tar HEAD texts | (cd #{work_dir}/catholic-reference && tar xvf -)"

Dir.chdir( work_dir ) do
  run "zip -r -9 catholic-reference-#{version}-full.zip catholic-reference/ "
  run "tar cjvf catholic-reference-#{version}-full.tar.bz2 catholic-reference/"
end
