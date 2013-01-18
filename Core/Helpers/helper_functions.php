<?php

function endsWith($subject, $ending) {
    return substr($subject, -strlen($ending)) === $ending;
}

function endsWithAnyOf($subject, $endings) {
    foreach($endings as $ending) {
        if(endsWith($subject, $ending)) {
            return true;
        }
    }

    return false;
}

function allFilesFromDir($dir) {
    $files = array();

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $f) {
        $files[] = $f;
    }

    return $files;
}