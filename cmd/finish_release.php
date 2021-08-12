<?php

/**
 * This is the big one - finalise a release by merging it into main and develop.
 */

include dirname(__DIR__).'/config.php';

const DefaultTargetBranch = 'develop';

# required params: release branch name in full (e.g. hotfix/0.0.1 or release/0.2.0)
# optional param: second branch to merge into (e.g. develop or release/0.2.0 in the case of a hotfix during release cycle)
#   second branch defaults to `develop`

$release_branch = $argv[1] ?? false;
$target_branch = $argv[2] ?? DefaultTargetBranch;

# checkout release branch
`git checkout $release_branch`;
# get version number from /VERSION
$version = trim( file_get_contents(AppRoot . '/VERSION' ) );

# checkout main
`git checkout main`;
# merge into main (this should be easy as all code should come from main so not conflict)
`git merge --no-ff $release_branch -m "Merge $release_branch into main"`;
# tag merge with the version number
`git tag -a $version`;

# checkout develop
`git checkout $target_branch`;
# merge into develop (this may not be easy as develop could have diverged from this branch)
`git merge --no-ff $release_branch -m "Merge $release_branch into $target_branch"`;

# delete release branch once confirmed that all work is complete
#`git branch -d $release_branch';