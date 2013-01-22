<?php

/**
 * Checks if given string starts with given prefix.
 * @param  string $subject
 * @param  string $prefix
 * @return boolean
 */
function startsWith($subject, $prefix) {
    return !strncmp($subject, $prefix, strlen($prefix));
}

/**
 * Checks if given string ends with given postfix.
 * @param  string $subject
 * @param  string $postfix
 * @return boolean
 */
function endsWith($subject, $postfix) {
    return substr($subject, -strlen($postfix)) === $postfix;
}

/**
 * Checks if given string ends with any of given postfixes.
 * @param  string $subject
 * @param  array $endings array of postfixes
 * @return boolean
 */
function endsWithAnyOf($subject, $endings) {
    foreach($endings as $ending) {
        if(endsWith($subject, $ending)) {
            return true;
        }
    }

    return false;
}

/**
 * Retrieves list of files from given directory.
 * @param  string  $dir       path to directory
 * @param  boolean $recursive if directory should be scaned recursively
 * @return array             array of file paths
 */
function allFilesFromDir($dir, $recursive=true) {
    $files = array();
    $iterator = $recursive ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) : new DirectoryIterator($dir);

    foreach ($iterator as $f) {
        $files[] = (string)$f;
    }

    return $files;
}