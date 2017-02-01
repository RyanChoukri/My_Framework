<?php

function my_autoload($raw_class) {
    $path =  str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']);
    $class_name = explode(DIRECTORY_SEPARATOR, $raw_class);
    $file_name = $class_name[count($class_name)  - 1] . '.class.php';
    $recursive_dir = new RecursiveDirectoryIterator($path);
    foreach (new RecursiveIteratorIterator($recursive_dir) as $filename => $file) {
        if(file_exists($file->getPath() . '/'. $file_name)){
            include $file->getPath() . '/' . $file_name;
            return;
        }
    }
}
spl_autoload_register('my_autoload');
?>