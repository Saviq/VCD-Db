<?php
   /* AJASON
    * Bringing AJAX and JSON together
    *
    * This file is part of AJASON.
    *
    * AJASON is a PHP 5 library and JavaScript client for the upcoming
    * Web technology called AJAX. AJAX permits data to be fetched
    * asynchronously without the need for reloading the Web page and
    * thus allows the development of interactive GUI-like Web applications.
    * JSON is a lightweight data interchange format which is used by AJASON
    * to exchange data between server and client.
    *
    * AJASON is free software; you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation; either version 2 of the License, or
    * (at your option) any later version.
    *
    * AJASON is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    * GNU General Public License for more details.
    *
    * You should have received a copy of the GNU General Public License
    * along with Foobar; if not, write to the Free Software
    * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
    *
    * AJASON has been developed by Sven Jacobs <sven.jacobs@web.de>.
    * For more details visit sven-jacobs.de/wiki/AJASON
    *
    * Some parts of this project are contributed by other projects, namely
    * JSON (json.org) and JSON-PHP (mike.teczno.com/json.html) which are
    * copyrighted by their respective authors.
    */

   /**
    * Ajax response class
    *
    * A JSON encoded object of this class is returned by the AjaxServer class
    * on each Ajax request.
    *
    * @author Sven Jacobs <sven.jacobs@web.de>
   **/
   class AjaxResponse
   {
      public function setErrorCode( $errorCode )
      {
         $this->errorCode = $errorCode;
      }

      public function getErrorCode()
      {
         return $this->errorCode;
      }

      public function setErrorMessage( $errorMessage )
      {
         $this->errorMessage = $errorMessage;
      }

      public function getErrorMessage()
      {
         return $this->errorMessage;
      }

      public function setServerVersion( $serverVersion )
      {
         $this->serverVersion = $serverVersion;
      }

      public function getServerVersion()
      {
         return $this->serverVersion;
      }

      public function setResponse( $response, $encode = false )
      {
         if ( $encode )
         {
            $this->encodeResponse( $response );
         }

         $this->response = $response;
      }

      public function getResponse()
      {
         return $this->response;
      }

      /* These class members are public so that they can be encoded
         by the JSON library. Do not alter them directly, instead use
         the getter and setter methods above!! */

      /**
       * Contains an error code or 0 if no error occured
      **/
      public $errorCode = 0;

      /**
       * Contains a descriptive error message if errorCode != 0
      **/
      public $errorMessage;

      /**
       * Contains version of the server side AJASON library
      **/
      public $serverVersion;

      /**
       * Contains the return value from the function or method which has
       * been called by the AjaxServer class on request
      **/
      public $response;

      private function encodeResponse( &$response )
      {
         if ( is_string( $response ) )
         {
            $response = utf8_encode( $response );
         }
         else if ( is_array( $response ) )
         {
            // Encode all items of the array
            foreach ( $response as &$item )
            {
               $this->encodeResponse( $item );
            }
         }
         else if ( is_object( $response ) )
         {
            /* We can only encode public members of an object
               but there is no need to convert more, because only
               these public members will be encoded by JSON. */

            foreach ( get_object_vars( $response ) as $key => $item )
            {
               $this->encodeResponse( $item );
               eval( "\$response->$key = \$item;" );
            }
         }
      }
   }
?>