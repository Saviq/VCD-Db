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

   require_once(dirname(__FILE__) . '/AjaxBase.php' );
   require_once(dirname(__FILE__) . '/AjaxResponse.php' );
   require_once(dirname(__FILE__) . '/AjaxException.php' );
   require_once(dirname(__FILE__) . '/JSON.php' );

   /**
    * Ajax server class
    *
    * The server handles an Ajax request and calls the appropriate function or
    * method, grabs the return value and responses with a JSON encoded
    * AjaxResponse object.
    *
    * @author Sven Jacobs <sven.jacobs@web.de>
   **/
   class AjaxServer extends AjaxBase
   {
      protected $request;

      /**
       * Constructor
       *
       * @param[in] options Array of Ajax options which has been set in Ajax main class
       * @param[in] functions Array of registered functions which have been set in Ajax main class
       * @param[in] methods Array of registered methods which have been set in Ajax main class
      **/
      public function __construct( $options, $functions, $methods )
      {
         parent::__construct( $options );
         $this->functions = $functions;
         $this->methods   = $methods;

         if ( $this->getOption( AjaxServer::MethodOption ) == AjaxServer::MethodGet )
         {
            $this->request =& $_GET;
         }
         else
         {
            $this->request =& $_POST;
         }
      }

      /**
       * Is Ajax request?
       *
       * @return Returns true if we are in the middle of an Ajax request, else false
      **/
      public function isRequest()
      {
         return ( isset( $this->request[ 'ajax' ] ) );
      }

      /**
       * Handle Ajax request
       *
       * Unpack JSON encoded Ajax request and call the appropriate function or
       * method.
       *
       * @return JSON encoded AjaxResponse object
      **/
      public function handleRequest()
      {
         $json     = new JSON();
         $response = new AjaxResponse();
         $request  = $json->decode( stripslashes( $this->request[ 'ajax' ] ) );

         // Call method
         if ( isset( $request->cls ) )
         {
            if ( $this->methodIsRegistered( $request->cls, $request->mtd ) )
            {
               try
               {
                  if ( $this->validClassAndMethod( $request->cls, $request->mtd ) )
                  {
                     $response->setResponse( call_user_func_array( array( $request->cls, $request->mtd ), $request->params ),
                                             $this->getOption( AjaxServer::EncodeOption ) );
                  }
                  else
                  {
                     $obj = new $request->cls();
                     $response->setResponse( call_user_func_array( array( $obj, $request->mtd ), $request->params ),
                                             $this->getOption( AjaxServer::EncodeOption ) );
                  }

                  $response->setErrorCode( 0 );
               }
               catch( AjaxException $e )
               {
                  $response->setErrorCode( 2 );
                  $response->setErrorMessage( $e->getMessage() );
               }
            }
            else
            {
               $response->setErrorCode( 1 );
               $response->setErrorMessage( 'Method ' . $request->cls . '::' . $request->mtd . ' has not been registered!' );
            }
         }
         // Call function
         else if ( isset( $request->func ) )
         {
            if ( $this->functionIsRegistered( $request->func ) )
            {
               $response->setResponse( call_user_func_array( $request->func, $request->params ),
                                       $this->getOption( AjaxServer::EncodeOption ) );

               $response->setErrorCode( 0 );
            }
            else
            {
               $response->setErrorCode( 1 );
               $response->setErrorMessage( 'Function ' . $request->func . ' has not been registered!' );
            }
         }
         // Unknown request :-(
         else
         {
            $response->setErrorCode( 3 );
            $response->setErrorMessage( 'Unknown AJAX request!' );
         }

         $response->setServerVersion( AjaxServer::version );

         header( 'Content-Type', 'application/json' );
         return $json->encode( $response );
      }

      /**
       * Is function registered?
       *
       * @param[in] function Name of function
       * @return Returns true if a function by that name has been registered previously
      **/
      protected function functionIsRegistered( $function )
      {
         return ( in_array( $function, $this->functions ) );
      }

      /**
       * Is method registered?
       *
       * @param[in] class Name of class
       * @param[in] method Name of method
       * @return Returns true if a method by that name has been registered previously
      **/
      protected function methodIsRegistered( $class, $method )
      {
         return ( array_key_exists( $class, $this->methods ) &&
            in_array( $method, $this->methods[ $class ] ) );
      }
   }

?>