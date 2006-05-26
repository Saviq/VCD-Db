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
    * Ajax exception class
    *
    * This class is thrown by all Ajax classes when errors occur.
    *
    * @author Sven Jacobs <sven.jacobs@web.de>
   **/
   class AjaxException extends Exception {};

?>