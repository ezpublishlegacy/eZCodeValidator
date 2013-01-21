<?php

function startsWith($subject, $prefix) {
    return !strncmp($subject, $prefix, strlen($prefix));
}

function endsWith($subject, $postfix) {
    return substr($subject, -strlen($postfix)) === $postfix;
}

function endsWithAnyOf($subject, $endings) {
    foreach($endings as $ending) {
        if(endsWith($subject, $ending)) {
            return true;
        }
    }

    return false;
}

function allFilesFromDir($dir, $recursive=true) {
    $files = array();
    $iterator = $recursive ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) : new DirectoryIterator($dir);

    foreach ($iterator as $f) {
        $files[] = (string)$f;
    }

    return $files;
}