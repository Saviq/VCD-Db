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

   /**
    * Ajax client class
    *
    * The client creates the JavaScript code which needs to be rendered into
    * the HTML document where you are going to use AJASON functionality.
    *
    * @author Sven Jacobs <sven.jacobs@web.de>
   **/
   class AjaxClient extends AjaxBase
   {
      /**
       * Constructor
       *
       * @param[in] options Array of Ajax options which has been set in Ajax main class.
       * @param[in] functions Array of registered functions which have been set in Ajax main class.
       * @param[in] methods Array of registered methods which have been set in Ajax main class.
      **/
      public function __construct( $options, $functions, $methods )
      {
         parent::__construct( $options );
         $this->functions = $functions;
         $this->methods   = $methods;
      }

      /**
       * Return JavaScript code
       *
       * Return JavaScript which contains code to call all registered
       * functions and methods. Note that you need to include the files ajax.js
       * and json.js into your document *before* you include the output of this
       * method!
       *
       * @return AJASON JavaScript code
      **/
      public function getJavaScript()
      {
         $js  = $this->getInitJavaScript();
         $js .= $this->getFunctionJavaScript();
         $js .= $this->getMethodJavaScript();

         return $js;
      }

      /**
       * Create JavaScript code to instantiate the JavaScript Ajax class
       *
       * @return JavaScript code
      **/
      protected function getInitJavaScript()
      {
         // Method
         $this->getOption( AjaxClient::MethodOption ) == AjaxClient::MethodGet ? $method = 'GET' : $method = 'POST';

         // Debug
         $this->getOption( AjaxClient::DebugOption ) ? $debug = 'true' : $debug = 'false';

         // Display errors
         $this->getOption( AjaxClient::DisplayErrorsOption ) ? $displayErrors = 'true' : $displayErrors = 'false';

         // Handler
         $handler = $this->getOption( AjaxClient::HandlerOption );

         if ( empty( $handler ) )
         {
            $handler = $_SERVER[ 'PHP_SELF' ];
         }

         return "var ajax = new Ajax( \"$method\", \"$handler\", $debug, $displayErrors );\n";
      }

      /**
       * Create JavaScript code for registered functions
       *
       * @return JavaScript code
      **/
      protected function getFunctionJavaScript()
      {
         $js = '';

         foreach( $this->functions as &$function )
         {
            $name  = $this->getOption( AjaxClient::PrependOption ) . $function;
            $js   .= "function $name() { ajax.callFunction( '$function', $name.arguments ) }\n";
         }

         return $js;
      }

      /**
       * Create JavaScript code for registered methods
       *
       * @return JavaScript code
      **/
      protected function getMethodJavaScript()
      {
         $js = '';

         foreach( $this->methods as $class => &$methods )
         {
            $name  = $this->getOption( AjaxClient::PrependOption ) . $class;
            $js   .= "function _$name() {\n";

            foreach( $methods as &$method )
            {
               $js .= "  this.$method = function() { ajax.callMethod( '$class', '$method', this.$method.arguments ) }\n";
            }

            $js .= "}\nvar $name = new _$name();\n";
         }

         return $js;
      }
   }

?>