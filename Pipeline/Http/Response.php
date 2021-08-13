<?php

namespace Pipeline\Http;

class Response
{
   private $sent = false;
   private $contentType;

   private function throwExceptionIfResponseSent(): void
   {
      if( $this->sent === true )
      {
         throw new \Exception('Response already sent, cannot further modify response');
      }
   }

   public function contentType( string $type ): void
   {
      $this->throwExceptionIfResponseSent();

      # allow some convenience shortcuts
      switch( $type )
      {
         case 'text': $type = 'text/plain'; break;
         case 'json': $type = 'application/json'; break;
         case 'html': $type = 'text/html'; break;
      }

      header('content-type: ' . $type);
      $this->contentType = $type;
   }

   public function send( $data ): void
   {
      $this->throwExceptionIfResponseSent();

      if( $this->contentType === 'application/json' )
      {
         exit( json_encode( $data ) );
      }

      exit( $data );
   }
}