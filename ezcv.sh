#!/bin/bash

FILES=`git status -s --porcelain | grep -G "^[A-Z] " | cut -c 4- | replace "
" " "`

php ~/Programs/ProjectValidator/run.php --files $FILES

