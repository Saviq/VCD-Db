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
    * @mainpage
    * AJASON is a PHP 5 library and JavaScript client for the upcoming
    * Web technology called AJAX. AJAX permits data to be fetched
    * asynchronously without the need for reloading the Web page and
    * thus allows the development of interactive GUI-like Web applications.
    * JSON is a lightweight data interchange format which is used by AJASON
    * to exchange data between server and client.
   **/

   require_once(dirname(__FILE__) . '/AjaxBase.php' );
   require_once(dirname(__FILE__) . '/AjaxClient.php' );
   require_once(dirname(__FILE__) . '/AjaxServer.php' );

   /**
    * Ajax main class
    *
    * This is the main class for Ajax which is used to set options or to
    * register functions and methods. It's the only Ajax class which should be
    * directly instantiated by a developer.
    *
    * @author Sven Jacobs <sven.jacobs@web.de>
   **/
   class Ajax extends AjaxBase
   {
      /**
       * Constructor
       *
       * @param[in] options An array of Ajax options
      **/
      public function __construct( $options = null )
      {
         parent::__construct( $options );
      }

      /**
       * Get server object
       *
       * Returns an instance of the AjaxServer class.
       *
       * @return AjaxServer object
       * @see AjaxServer
      **/
      public function getServer()
      {
         return new AjaxServer( $this->options, $this->functions, $this->methods );
      }

      /**
       * Get client object
       *
       * Returns an instance of the AjaxClient class.
       *
       * @return AjaxClient object
       * @see AjaxClient
      **/
      public function getClient()
      {
         return new AjaxClient( $this->options, $this->functions, $this->methods );
      }

      /**
       * Set an option
       *
       * See constants of AjaxBase class for valid options.
       *
       * @param[in] option One of AjaxBase option constants
       * @param[in] value One of AjaxBase option constants
       * @see AjaxBase
      **/
      public function setOption( $option, $value )
      {
         $this->options[ $option ] = $value;
      }

      /**
       * Register a function
       *
       * Registering a function means making it callable by the Ajax JavaScript
       * client.
       *
       * @param[in] function Name of function
      **/
      public function registerFunction( $function )
      {
         $this->validFunction( $function );

         if ( !in_array( $function, $this->functions ) )
         {
            $this->functions[] = $function;
         }
      }

      /**
       * Register a method
       *
       * Registering a method means making it callable by the Ajax JavaScript
       * client. Note that the method must be public. If the method is not static
       * then the constructor of the class must not take any mandatory parameters!
       *
       * @param[in] class Name of class, NOT an object
       * @param[in] method Name of method
      **/
      public function registerMethod( $class, $method )
      {
         $this->validClassAndMethod( $class, $method );

         if ( !array_key_exists( $class, $this->methods ) )
         {
            $this->methods[ $class ] = array();
         }

         if ( !in_array( $method, $this->methods[ $class ] ) )
         {
            $this->methods[ $class ][] = $method;
         }
      }
   }

?>