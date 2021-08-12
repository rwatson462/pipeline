<?php

require dirname(__DIR__).'/config.php';

/**
 * This script starts the release journey.
 * A release branch is created based on the current develop branch,
 * the version number is set to the given value and the version file is
 * immediately committed to the new release branch.
 */

$new_version = $argv[1] ?? false;

if( !$new_version || $new_version === '--help' )
{
   echo "[begin_release.php]\n"
         ."> Starts the release journey by creating a new release branch\n"
         ."> Using the version number supplied\n"
         ."> Automatically sets the version number and commits the VERSION file\n"
         ."> Example usage: php begin_release.php 0.1.2\n";
   exit;
}

`git checkout -b release/$new_version develop`;
passthru( 'php ' . AppRoot . '/cmd/set_version.php ' . $new_version );
`git add VERSION`;
`git commit -m "Update version number to $new_version"`;
