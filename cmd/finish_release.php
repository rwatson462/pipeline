<?php

/**
 * This is the big one - finalise a release by merging it into main and develop.
 */

include dirname(__DIR__).'/config.php';
include AppRoot . '/Pipeline/Git.php';
use Pipeline\Git;

$repo = new Git( GitWorkingDirectory );

const DefaultTargetBranch = 'develop';

# required params: release branch name in full (e.g. hotfix/0.0.1 or release/0.2.0)
# optional param: second branch to merge into (e.g. develop or release/0.2.0 in the case of a hotfix during release cycle)
#   second branch defaults to `develop`

$release_branch = $argv[1] ?? false;
$target_branch = $argv[2] ?? DefaultTargetBranch;

# checkout release branch
$repo->checkout( $release_branch );
# get version number from /VERSION
$version = trim( file_get_contents(AppRoot . '/VERSION' ) );

# checkout main
$repo->checkout( 'main' );
# merge into main (this should be easy as all code should come from main so not conflict)
$output = [];
try {
   $repo->exec( "git merge --no-ff $release_branch -m 'Merge $release_branch into main'", $output );
}
catch( Exception $e )
{
   echo "> Error during merge into main:\n" . implode( "\n", $output ) . "\n";
   echo "\n> Working tree left in merge conflict state to resolve\n";
   echo "> Run this task again once all conflicts are resolved and merge complete to merge into develop\n";
   exit(1);
}

# tag merge with the version number
$repo->tag( $version, $release_branch );

# checkout develop
$repo->checkout( $target_branch );

# merge into develop (this may not be easy as develop could have diverged from this branch)
$output = [];
try {
   $repo->exec( "git merge --no-ff $release_branch -m 'Merge $release_branch into $target_branch'", $output );
} catch( Exception $e )
{
   echo "> Error during merge into $target_branch:\n" . implode( "\n", $output ) . "\n";
   echo "\n> Working tree left in merge conflict state to resolve\n";
   exit(1);
}

# delete release branch once confirmed that all work is complete
$repo->deleteBranch( $release_branch );
# I did fancy writing $repo->branch( $release_branch )->delete() but that's a bit OTT for this
