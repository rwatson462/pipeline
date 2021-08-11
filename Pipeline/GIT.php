<?php

namespace Pipeline;

// This class provides all GIT-related functionality
class GIT
{
   public function cmd( $cmd )
   {
      return `git $cmd`;
   }
}