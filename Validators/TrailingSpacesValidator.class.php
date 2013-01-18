<?php

class TrailingSpacesValidator extends Validator {
    protected $defaultOptions = array(
        'validate' => true,
        'fix' => false
    );

    public function check($filePath) {
        $valid = true;

        if($this->options['validate']) {
            $valid = $this->validate($filePath);
        }

        if($this->options['fix']) {
            $this->fix($filePath);
        }

        return $valid;
    }

    //replaces tabs with spaces, does not try to insert proper number of spaces
    private function fix($filePath) {
        $fileContents = file_get_contents($filePath);

        $fileContents = preg_replace(
            '@[ 	]+$@m',
            '',
            $fileContents
        );

        file_put_contents($filePath, $fileContents);
    }

    private function validate($filePath) {
        $fileContents = file_get_contents($filePath);

        $trailingSpaces = preg_match_all("@[ 	]+$@m", $fileContents, $matches);

        if($trailingSpaces) {
            $this->log->error("INVALID (trailing spaces/tabs found)");
            return false;
        }

        $this->log->debug("VALID");
        return true;
    }
}