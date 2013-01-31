#!/bin/bash

# Run script from the eZ Publish root folder
# Parameter 1 is optional path to git folder


#- find the path to eZCodeValidator
FULL_NAME=`which "$0"`
VALIDATOR_PATH=`dirname "$FULL_NAME"`

#- default git folder is './git/'
GIT_FOLDER=git
if [[ ! $1 = '' ]]; then
	GIT_FOLDER=$1
fi

#- get list of files modified or about to be commited
#- filter list of files to be commited
#- replace line ends with spaces
FILES=`cd $GIT_FOLDER && git status -s --porcelain | grep -G "^[A-Z] " | cut -c 4- | replace "
" " "`

php $VALIDATOR_PATH/run.php --files $FILES
