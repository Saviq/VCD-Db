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

   require_once(dirname(__FILE__) . '/AjaxException.php' );

   /**
    * Ajax base class
    *
    * The base class contains methods and properties which are shared by all
    * Ajax classes.
    *
    * @author Sven Jacobs <sven.jacobs@web.de>
   **/
   abstract class AjaxBase
   {
      const version = '0.9';

      const MethodOption        = 'method';
      const HandlerOption       = 'handler';
      const DebugOption         = 'debug';
      const DisplayErrorsOption = 'errors';
      const PrependOption       = 'prepend';
      const EncodeOption        = 'encode';

      const MethodPost = 'post';
      const MethodGet  = 'get';

      protected $options   = array();
      protected $functions = array();
      protected $methods   = array();

      /**
       * Constructor
       *
       * @param[in] options An array of Ajax options
      **/
      public function __construct( $options = null )
      {
         if ( is_array( $options ) )
         {
            $this->options = $options;
         }
         else
         {
            // Use default values
            $this->options[ AjaxBase::MethodOption ]        = AjaxBase::MethodPost;
            $this->options[ AjaxBase::HandlerOption ]       = '';
            $this->options[ AjaxBase::DebugOption ]         = false;
            $this->options[ AjaxBase::DisplayErrorsOption ] = true;
            $this->options[ AjaxBase::PrependOption ]       = 'x_';
            $this->options[ AjaxBase::EncodeOption ]        = false;
         }
      }

      /**
       * Return an option value
       *
       * @param[in] option The AjaxBase option constant
       * @return Option value
      **/
      public function getOption( $option )
      {
         return $this->options[ $option ];
      }

      /**
       * Is valid class and method?
       *
       * Check whether the class and method are valid. That means whether the
       * class can be instantiated (is public and not abstract) and the method
       * can be reached (is public; if not static constructor needs to be
       * callable too).
       *
       * @throws AjaxException
       * @return Returns true if class and method are valid
      **/
      protected function validClassAndMethod( $class, $method )
      {
         try
         {
            $reflectionClass  = new ReflectionClass( $class );
            $reflectionMethod = $reflectionClass->getMethod( $method );

            if ( !$reflectionMethod->isStatic() && ( !$reflectionClass->isInstantiable() || $reflectionClass->isAbstract() ) )
            {
               throw new AjaxException( "Class $class is not instantiable" );
            }

            if ( !$reflectionMethod->isPublic() )
            {
               throw new AjaxException( "Method $class::$method is not public" );
            }

            return $reflectionMethod->isStatic();

         }
         catch( ReflectionException $e )
         {
            throw new AjaxException( $e->getMessage() );
         }
      }

      /**
       * Is valid function?
       *
       * @throws AjaxException
       * @return Returns true if function exists
      **/
      protected function validFunction( $function )
      {
         if ( !function_exists( $function ) )
         {
            throw new AjaxException( "Function $function does not exist" );
         }
      }
   }

?>