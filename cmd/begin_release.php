<?php

require dirname(__DIR__).'/config.php';
include AppRoot . '/Pipeline/GIT.php';
use Pipeline\GIT;

$repo = new GIT();


/**
 * This script starts the release journey.
 * A release branch is created based on the current develop branch,
 * the version number is set to the given value and the version file is
 * immediately committed to the new release branch.
 */

$new_version = $argv[1] ?? false;
$release_type = $argv[2] ?? false;

if( !$new_version || $new_version === '--help' )
{
   echo "[begin_release.php]\n"
         ."> Starts the release journey by creating a new release branch\n"
         ."> Using the version number supplied\n"
         ."> Automatically sets the version number and commits the VERSION file\n"
         ."> Example usage: php begin_release.php 0.1.2\n";
   exit;
}

# we might have been asked to make a different type of release, in which case
# we'll prefix the new branch with that name.  This should mostly be used for 
# hotfixes
$release_type = $release_type ?: 'release';

$repo->checkout( 'develop', "$release_type/$new_version" );

passthru( 'php ' . AppRoot . '/cmd/set_version.php ' . $new_version );

$repo->add('VERSION');
$repo->commit( "Update version number to $new_version" );
