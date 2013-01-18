<?php

class IndentationValidator extends Validator {
    protected $defaultOptions = array(
        'validate' => true,
        'fix' => false,
        'spaces_per_tab' => 4
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
        $spaces = str_repeat(' ', $this->options['spaces_per_tab']);

        $fileContents = preg_replace_callback(
            '@^([ 	]*)@m',
            function($selection) use($spaces) {
                return str_replace('	', $spaces, $selection[0]);
            },
            $fileContents
        );

        file_put_contents($filePath, $fileContents);
    }

    private function validate($filePath) {
        $fileContents = file_get_contents($filePath);
        $spacesPerTab = $this->options['spaces_per_tab'];

        $lines = preg_match_all("@^@m", $fileContents, $matches);
        $noIndentation = preg_match_all("@^[^ 	]@m", $fileContents, $matches);
        $tabs = preg_match_all("@^	+([^ ]|$)@m", $fileContents, $matches);
        $spaces = preg_match_all("@^ +([^	]|$)@m", $fileContents, $matches);

        if( $noIndentation == $lines ) {
            $this->log->debug("VALID (no indentation)");
            return true;
        }

        if( $tabs + $noIndentation == $lines ) {
            $this->log->error("INVALID (uses tabs)");
            return false;
        }

        if ( $spaces + $noIndentation == $lines ) {
            //file that uses only spaces - lets check if number of spaces is OK in every line
            foreach($matches[0] as $match) {
                $spacesCount = substr_count($match, ' ');

                if( $spacesCount%$spacesPerTab != 0 ) {
                    $this->log->error("INVALID (wrong number of spaces per tab, should be: $spacesPerTab)");
                    return false;
                }
            }

            $this->log->debug("VALID");
            return true;
        }

        $this->log->error("INVALID (mixed spaces and tabs)");
        return false;
    }
}