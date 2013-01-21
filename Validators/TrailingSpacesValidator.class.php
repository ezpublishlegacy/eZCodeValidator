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
        $valid = true;
        $fileContents = explode(PHP_EOL, file_get_contents($filePath));
        $lineNumber = 1;

        foreach($fileContents as $lineContents) {
            if(preg_match("@[ 	]+$@", $lineContents)) {
                $this->log->error("line $lineNumber - trailing spaces/tabs found");
                $valid = false;
            }

            $lineNumber++;
        }

        return $valid;
    }
}