<?php
if(!defined('IN_MYPARSE'))exit;

class conf_class{
	function __construct($config_path){
		// extract the config name with some string magic get string between / and .php
		// check if only file name was provided
		$start = (!strpos($config_path,'/')?'':'/');
		$this->config_name = get_string_between($config_path,$start,'.php');
		$this->e_file = $config_path;
		$this->html = self::get_config();
		// we're using a post variable. Obviously if you didn't want to use this class you can write your own config files
		$this->html .=($_POST && $_POST['Update']?self::writeConfig():NULL);
		// I refresh it to avoid invalid form updates conflicts inside the object
		if($_POST && $_POST['Update']) self::reload_form(5);
	}
	private function reload_form($refresh=0,$url=NULL){	
		unset($_POST);die(header("Refresh: $refresh"));
	}
	
	// doesn't show the updated config properly a
	private function write_config(){
	// unsetting $_POST to make the $_POST processing go more smoothly
	unset($_POST['Update']);
		foreach($_POST as $key=>$value)
		// get each key and add to result array, checking datatypes to avoid storing 'false' instead of false or '1' instead of 1.
			$result []= "'$key'=>" . ($value == 'true' || $value == 'false' || is_numeric($value)?$value:"'$value'");
		if (is_writable($this->e_file)) {
		    if (!$handle = fopen($this->e_file, 'w')) 
		      die("Cannot open file ($this->e_file)");
		    if (fwrite($handle, '<?php
				class '.$this->config_name.'{ 
				public static $_ = array('."\n\t" .implode(",\n\t\t",$result) . ");
				}") === FALSE) 
		       return ("Cannot write to file ($this->e_file)");
		    return self::reload_form(0)."Success, wrote to file ($this->e_file)";
		 fclose($handle);
		} else return "The file $this->e_file is not writeable, please change file permissions to 755 or better";
			
	}

	public function get_string_between($string, $start, $end){
		if (strpos(" ".$string,$start) == 0) return '';
		$string = " ".$string;
		$ini = strpos($string,$start) + strlen($start);
		return substr($string,$ini,strpos($string,$end,$ini) - $ini);
	}
	
	private function edit_config(){
		$fd = fopen ($this->e_file, "r");
		// pick out the public static $_array .. could trim, but was causing problems
		$result=self::get_string_between(fread ($fd,filesize ($this->e_file)),'public static $_ = array(',');');
		fclose ($fd);
		$result = explode(',',$result);
		foreach($result as $a=>$b)
			$result[$a] = explode('=>',str_replace("'",'',trim($b)));
		foreach($result as $a=>$b){
			unset($key_name);
			foreach($b as $c=>$d)
			{
				if($c==0) $key_name = trim($d); 
				elseif($c==1 && $key_name) $result[$key_name] = trim($d);	
			}
			unset($result[$a]);
		}
		// simple returned form with basic radio selectors for true/false values
		$return = '<h1>Web Configuration Editor</h1>
			<form id="'.$this->class_name.'" method="post">';	
		foreach($result as $name=>$value){
			$return .= '<fieldset>
							<legend>'.$name . '</legend>' 
							.($value=='false' || $value=='true'?
								'<label>On</label><input type="radio" name="'.$name.'" value="true"' .  ($value=='true'? 'CHECKED':'') .'>
															<label>Off</label><input type="radio" name="'.$name.'" value="false"' .  ($value=='false'? 'CHECKED':'') .'><br>':
															'<input type="'.($name=='db_pass'?'password':'text').'" name="'. $name . '" value='."'". $value. "'" .'>').'</fieldset>';
		}
		return $return . '<input type="submit" name="Update" value="Update"></form>';
	}	
}

