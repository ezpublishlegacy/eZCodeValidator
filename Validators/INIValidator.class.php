<?php

//a lot of regular expressions
class INIValidator extends Validator {

    public function check($filePath) {
        $valid = true;
        $fileLines = @file($filePath);

        if( $fileLines === FALSE ) {
            $this->log->error("Error while reading the file.");
            return false;
        }

        $numberOfLines = count($fileLines);

        if($numberOfLines == 0) {
            $this->log->warning("The file is empty.");
            return false;
        }

        foreach( $fileLines as $index => $line ) {
            $line = trim($line);

            if( empty($line) ) {//empty line
                $debugOutput = 'Empty Line -> ';
            } else if( $index == 0 && preg_match('/^( *)<\?.*$/', $line) !== 0 ) {//php opening tag
                $debugOutput = 'PHP OTag   -> ';
            } else if( $index == $numberOfLines - 1 && preg_match('/^( *)\?>( *)$/', $line) !== 0 ) {//php closing tag
                $debugOutput = 'PHP CTag   -> ';
            } else if( $index < 2 && preg_match('/^( *)\/\*.*$/', $line) !== 0 ) {//php comment opening tag
                $debugOutput = 'PHP COTag  -> ';
            } else if( $index > $numberOfLines - 3 && preg_match('/^( *)\*\/(.*\?>)?( *)$/', $line) !== 0 ) {//php comment closing tag
                $debugOutput = 'PHP CCTag  -> ';
            } else if( preg_match('/^#/', $line) !== 0 ) {//comment
                $debugOutput = 'Comment    -> ';
            } else if( preg_match('/^\[.*\]$/i', $line) !== 0 ) {//section
                $debugOutput = 'Section    -> ';
            } else if( preg_match('/^[a-z0-9_-]+\[\]$/i', $line) !== 0 ) {//array
                $debugOutput = 'Array      -> ';
            } else if( preg_match('/^[a-z0-9_-]+(\[.*\])?=.*$/i', $line) !== 0 ) {//value
                $debugOutput = 'Value      -> ';
            } else {
                $debugOutput = 'ERROR      -> ';
                $this->log->error(' - Line ' . $index . ' is invalid (' . $line . ').');
                $valid = false;
            }

            $this->log->debug($index . ': ' . $debugOutput . $line);
        }

        return $valid;
    }
}