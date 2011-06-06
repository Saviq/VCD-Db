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
////////////////////////////////////////////////////////////////////////////////

class SiteCookie {
	private $_name="";
	private $_val=array();
	private $_expires;
	private $_dir='/';    // all dirs
	private $_site='';

    public function __construct($cname, $cexpires="", $cdir="/", $csite="") {
        
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

    public static function extract($cname="") {
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

    public function put($var, $value) {
        $_COOKIE[$var]=$value;
        $this->_val["$var"]=$value;

        if(isset($GLOBALS["PHP_SELF"])){
            $GLOBALS[$var]=$value;
        }

        if(empty($value)){
            unset($this->_val[$var]);
        }

    }

    public function clear() {
        $this->_val=array();
    }

    public function set() {
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