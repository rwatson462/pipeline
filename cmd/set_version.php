<?php

require dirname(__DIR__).'/config.php';

/**
 * This script updates the version number stored in /VERSION to the given value
 */

$new_version = $argv[1] ?? false;

if( !$new_version || $new_version === '--help' )
{
   echo "[set_version.php]\n"
         ."> Sets the version of this application to the given value\n"
         ."> Example usage: php set_version.php 0.0.1\n";
   exit;
}

$version_file = AppRoot.'/VERSION';

$current_version = trim( file_get_contents( $version_file ) );

echo "Updating version $current_version to $new_version\n";

file_put_contents( $version_file, $new_version );
