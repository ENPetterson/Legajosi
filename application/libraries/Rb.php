<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rb  {
	
	function __construct() {
		// Get Redbean
                include(APPPATH.'/third_party/rb/rb.php');
		
		// Database data
                $CI =& get_instance();
                $CI->load->database();
                $host = $CI->db->hostname;
                $user = $CI->db->username;
                $pass = $CI->db->password;
                $db = $CI->db->database;
                
                
                
                
		
		// Setup DB connection
                
                RedBean_OODBBean::setFlagBeautifulColumnNames(false);
                R::setup("mysql:host=$host;dbname=$db", $user, $pass);
                
                
	} //end __contruct()
} //end Rb