<?php

/**
 * Get snippet content from local file
 *
 * @param string $filename
 *
 * @return string
 */
function getSnippetContent($filename)
{
    $file = trim(file_get_contents($filename));
    preg_match('/<\?php(.*)/is', $file, $data);

    return rtrim(rtrim(trim($data[1]), '?>'));
}

/**
 * Recursive directory remove
 *
 * @param string $dir
 */
function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir, SCANDIR_SORT_NONE);

        foreach ($objects as $object) {
            if ($object !== '.' && $object !== '..') {
                if (filetype($dir . '/' . $object) === 'dir') {
                    rrmdir($dir . '/' . $object);
                } else {
                    unlink($dir . '/' . $object);
                }
            }
        }

        reset($objects);
        rmdir($dir);
    }
}
