<?php

namespace Pipeline;

/**
 * The purpose of this GIT class is to wrap the GIT shell commands by
 * returning bool(true) when commands are successful and throwing exceptions
 * when they are not.
 * This allows easier control during PHP script execution later.
 */
class GIT
{
   /**
    * Executes a GIT command
    * @param string $cmd the command to run
    * @param array $output populated with both stdout and stderr output
    * @return true on success
    * @throws \Exception on non-zero status code of command
    */
   private function exec( string $cmd, ?array &$output = null ): bool
   {
      $result = 0;
      // $cmd should be the basic git command like 'git checkout develop'
      // we'll redirect stderr to stdout so we can capture the output on failure
      $cmd .= ' 2>&1';
      exec( $cmd, $output, $result );

      // git commands return a non-zero status on failure
      if( $result === 0 ) return true;

      throw new \Exception( implode( "\n", $output ) );
   }

   public function checkout( string $branch, string $new_branch_name = '') : bool
   {
      if( $new_branch_name )
      {
         return $this->exec( "git checkout -b $new_branch_name $branch" );
      }

      return $this->exec( "git checkout $branch" );
   }

   public function add( string $filename ): bool
   {
      return $this->exec( "git add $filename" );
   }

   public function commit( string $message ): bool
   {
      return $this->exec( "git commit -m $message" );
   }
}
