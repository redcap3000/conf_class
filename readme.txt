 conf class v1 (PHP)
 
  @author		Ronaldo Barbachano http://www.redcapmedia.com
  @copyright  (c) April 2011
  @license		http://www.fsf.org/licensing/licenses/agpl-3.0.html
  @link		http://www.myparse.org


Part of myparse framework technology. Simple configuration class that eliminates the need
for simple global $config variables by using a simple class with a single static array
that can be accessed without creating another object. Optionally, they can extend existing
classes if you would like to access variables in a slightly more secure fashion.

Also included is a cool web editor that can allow developers to quickly edit existing configuration
classes. This interface only manages basic values, and will not work for recursive arrays. Another
design pattern is to use a character (such as a space or comma) to use to explode when reading the
static class.

Usage
Step 1) Load the class (I like require pared with a (class_exists) if statement
Step 2) Access the class array by using the parameter anywhere else after in the code

config::$_['key']

-- Config referring to the name of your configuration file
-- Use the same name in the php class as the name of the class itself
-- I used $_ because it is short and easy to remember. 
-- I used an associative array to make it easier to access all of a configurations parameters in foreach loops
-- and to use them in conjunction with the many php array functions



Configuration Usage

	$config = new config_class('absolute configuration path in file system);
	echo $config->html;
	
	makes and reads a file in this format 
	
	avoid adding comments because they will be removed after the config file is re-written

-- file libraries/config.php --
	
	class config{
		public static $_ = array('parameter'=>'value',
								'parameter2'=>'value2',
								'parameter3'=>'value3');
	}
	
	Class creates an HTML parameter in object that returns a form that allows a user to edit
	Please use this object in a secure area, the config file needs permission 777 (755 works in rare cases)
	If a form is submitted if a post object is not configured.
	
	Once required (use a class_exists('config_name') to avoid multiple requires and the use of the 
	extremely slow 'require_once') the array can be accessed as a static
	class parameter, and the class does not even need to be created to access the classes inside and outside of objects
	
	config::$_['config parameter']
