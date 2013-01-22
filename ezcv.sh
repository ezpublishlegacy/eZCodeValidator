#!/bin/bash

#- get list of files modified or about to be commited
#- filter list of files to be commited
#- replace line ends with spaces
FILES=`git status -s --porcelain | grep -G "^[A-Z] " | cut -c 4- | replace "
" " "`

php ~/Programs/ProjectValidator/run.php --files $FILES

