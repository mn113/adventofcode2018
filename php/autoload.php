<?php

spl_autoload_register(function($class) {
    # namespace prefix that we will use for autoloading the appropriate classes and ignoring others
    $prefix = 'AOC\\php\\';
    # base directory where our project's files reside
    $base_dir = __DIR__;
    /**
     * Does the class being called use our specific namespace prefix?
     *
     *  - Compare the first {$len} characters of the class name against our prefix
     *  - If no match, move to the next registered autoloader in the system (if any)
     */
    # character length of our prefix
    $len = strlen( $prefix );
    # if the first {$len} characters don't match
    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        return;
    }
    # get the name of the class after our prefix has been peeled off
    $class_name = str_replace( $prefix, '', $class );

    $rawfile = $base_dir . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class_name ) . '.php';
    $classfile = $base_dir . DIRECTORY_SEPARATOR . 'class' . str_replace('\\', '/', $class_name ) . '.php';
    $interfacefile = $base_dir . DIRECTORY_SEPARATOR . 'interface' . str_replace('\\', '/', $class_name ) . '.php';

    # require the file if it exists
    if (file_exists($rawfile)) {
        require $rawfile;
    }
//    else if (file_exists($classfile)) {
//        require $classfile;
//    }
//    else if (file_exists($interfacefile)) {
//        require $interfacefile;
//    }
});