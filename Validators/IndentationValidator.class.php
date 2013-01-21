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
        $valid = true;
        $fileContents = explode(PHP_EOL, file_get_contents($filePath));
        $spacesPerTab = $this->options['spaces_per_tab'];

        $lineNumber = 1;

        foreach($fileContents as $lineContents) {
            $noIndentation = preg_match("@^[^ 	]@", $lineContents);
            $tabs = preg_match("@^	+([^ ]|$)@", $lineContents);
            $spaces = preg_match("@^ +([^	]|$)@", $lineContents, $matches);

            if($tabs) {
                $this->log->error("line $lineNumber - tabs used for indentation");
                $valid = false;
            } else if($spaces) {
                //line that uses only spaces - lets check if number of spaces is OK in every line
                $spacesCount = substr_count($matches[0], ' ');

                if($spacesCount%$spacesPerTab != 0) {
                    $this->log->error("line $lineNumber - wrong number of spaces per tab, should be: $spacesPerTab");
                    $valid = false;
                }
            } else if ($noIndentation === false) {
                $this->log->error("line $lineNumber - mixed spaces and tabs used for indentation");
                $valid = false;
            }

            $lineNumber++;
        }

        return $valid;
    }
}