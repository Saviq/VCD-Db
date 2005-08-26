<?php
//
//    Class:     SiteCookie
//    Version:   1.7
//    Author:    Brian Moon <brian@phorum.org>
//    Purpose:   Easily handle multiple cookies for a site in one browser cookie.
//               Whatever you enter with put() is added to the single cookie stored
//               when you call set().    The cookie name the browser receives is the
//               string specified when creating the object.    The constructor reads
//               the browser cookie and creates the variables for use in your code.
//
//    License: This software is covered by the Phorum License.    For a copy of that
//                     license, goto http://phorum.org/license.txt.
//
//    Usage:
//
//        To create a new cookie:
//
//            $cookie=new SiteCookie("mycookie");
//
//            $cookie->put("var1","var1val");    // the difference between using put() and
//            $cookie->put("var2","var2val");    // the setting the val array is that the
//
//            $cookie->set();
//
//
//        To use the cookie in another page simply call the extract method:
//
//            SiteCookie::extract("mycookie");
//
//            echo $_COOKIE["var1"];
//            echo $_COOKIE["var2"];
//
//
//     Private Member Variables:
//
//             $_name:      the name that the cookie is stored with in the browser.
//             $_val:       an internal array to hold the cookie information.
//             $_expires:   the expiration date of the cookie.    default ~6 months.
//             $_dir:       the http dir that cookie is set to.    default is /.
//             $_site:      the domain for the cookie.    default is none.    The browser
//                          will use the current domain.
//
//     Changes:
//
//             1.7    The set method will now send an empty string for the cookie value if
//                    there is nothing in $this->_val, thus deleting the cookie.
//
//             1.6    Separated cookie extraction into a method for easier use.  Fixed issue
//                    where the cookie has a value but was not an array.  Added some checks
//                    for the cookie size.  Cleaned up some code to handle older PHP versions.
//                    If empty values are passed to put(), that member is now unset.
//                    Added check for slashes and stip them if needed.  Thanks to
//                    Christopher Hilling for helping with the code.  - blm 05-2003
//
//             1.5    Removed an echo in the set method.  Prepended member vars names with _
//                    to indicate they should not be used outside the class.  Updated to use
//                    $_COOKIE available in PHP 4.1 and higher. - blm 08-2002
//
//             1.4.1  Fixed code so that it checks for the cookie before going through foreach
//
//             1.4    Changed code to store serialized data so that complex variables
//                    could be stored.
//
//                    Added site parameter to the Constructor.
//
//             1.3.1  Added license section to comments.    There was some question.
//
//             1.3    Added clear() method. - blm 10-2000
//
//             1.2    Some values were not getting stored right. - blm 10-2000
//
//             1.1    Fixed some PHP4 problems and added some more docs. - blm 10-2000
//
////////////////////////////////////////////////////////////////////////////////

class SiteCookie
{
    var $_name="";
    var $_val=array();
    var $_expires;
    var $_dir='/';    // all dirs
    var $_site='';

    function SiteCookie($cname, $cexpires="", $cdir="/", $csite="")
    {
        
        $this->_name=$cname;

        if($cexpires){
            $this->_expires=$cexpires;
        }
        else{
            $this->_expires=time()+86400*30*6; // ~6 months
        }

        $this->_dir=$cdir;
        $this->_site=$csite;

        $this->_val=array();

        $this->extract();
    }

    function extract($cname="")
    {
        // handle older PHP versions
        if(!isset($_COOKIE)){
            global $_COOKIE;
            $_COOKIE=$GLOBALS["HTTP_COOKIE_VARS"];
        }

        if(empty($cname) && isset($this)){
            $cname=$this->_name;
        }
        
        if(!empty($_COOKIE[$cname])){

            // pull the variables out of the cookie
            if(get_magic_quotes_gpc()){
                $_COOKIE[$cname]=stripslashes($_COOKIE[$cname]);
            }
            $arr=unserialize($_COOKIE[$cname]);

            // if it equals false, it was not able to be unserialized
            // if it is not an array, we can't do much with it.
            if($arr!==false && is_array($arr)){
            
                // create the variables in the global space
                foreach($arr as $var => $val){

                    // add it to the standard cookie array
                    $_COOKIE[$var]=$val;

                    // check for a known registered global
                    // if it is there, put this in the global space as well. 
                    if(isset($GLOBALS["PHP_SELF"])){
                        $GLOBALS[$var]=$val;
                    }
                }
            }

            if(isset($this)) $this->_val=$arr;

        }

        // remove the site cookie from the global scope.
        unset($_COOKIE[$cname]);
        unset($GLOBALS[$cname]);
        
    }

    function put($var, $value)
    {
        $_COOKIE[$var]=$value;
        $this->_val["$var"]=$value;

        if(isset($GLOBALS["PHP_SELF"])){
            $GLOBALS[$var]=$value;
        }

        if(empty($value)){
            unset($this->_val[$var]);
        }

    }

    function clear()
    {
        $this->_val=array();
    }

    function set()
    {
        if(empty($this->_val)){
            $cookie_val="";
        } else {
            $cookie_val=serialize($this->_val);
        }
        
        if(strlen($cookie_val)>4*1024){
            trigger_error("The cookie $this->_name exceeds the specification for the maximum cookie size.  Some data may be lost", E_USER_WARNING);
        }
        setcookie("$this->_name", $cookie_val, $this->_expires, $this->_dir, $this->_site);
    }

}

?>