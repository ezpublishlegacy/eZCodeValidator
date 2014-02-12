#!/bin/bash

# Run script from the eZ Publish root folder
# Parameters:
# --git specify optional path to git folder (default is 'git/')
# --config specify optional configuration file (default is 'config.default.json')

#- find the path to eZCodeValidator
FULL_NAME=`which "$0"`
VALIDATOR_PATH=`dirname "$FULL_NAME"`

GIT_FOLDER=git
CONFIG_FILE=$VALIDATOR_PATH/config.default.json

#- parse input parameters
while getopts "g:c:" OPTION
do
     case $OPTION in
         g)
             GIT_FOLDER=$OPTARG
             ;;
         c)
             CONFIG_FILE=$OPTARG
             ;;
     esac
done

if [ ! -d $GIT_FOLDER ]; then
	echo "Git repository folder '$GIT_FOLDER' not found."
	exit 1
fi

if [ ! -f $CONFIG_FILE ]; then
	echo "eZCodeValidator config file '$CONFIG_FILE' not found."
	exit 1
fi

if [ ! -d 'kernel' ]; then
	echo "Not in eZ Publish root directory."
	exit 1
fi

#- get list of files modified or about to be commited
#- filter list of files to be commited
#- replace line ends with spaces
FILES=`cd $GIT_FOLDER && git status -s --porcelain | cut -c 4- | sed "s/.*/$GIT_FOLDER\/&/" | replace "
" " "`

php $VALIDATOR_PATH/run.php --config $CONFIG_FILE  --files $FILES
