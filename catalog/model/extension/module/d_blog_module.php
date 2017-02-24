<?php
/*
 *	location: admin/model
 */

class ModelExtensionModuleDBlogModule extends Model {


	 public function in_array_multi($needle, $haystack, $strict = true) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_multi($needle, $item, $strict))) {
	            return true;
	        }
	    }

	    return false;
	}

	public function array_merge_r_d( array &$array1, array &$array2 ){
      $merged = $array1;    
      foreach ( $array2 as $key => &$value )
          {
            if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
            {
              $merged [$key] = $this->array_merge_r_d ( $merged [$key], $value );
            }
            else
            {
              $merged [$key] = $value;
            }
          }
        
      return $merged;
    }

	/*
	*	Format the link to work with ajax requests
	*/
	public function ajax($link){
		return str_replace('&amp;', '&', $link);
	}

	/*
	*	Return name of config file.
	*/
	public function getConfigFile($id, $sub_versions){

		$setting = $this->config->get($id.'_setting');

		if(isset($setting['config'])){
			return $setting['config'];
		}

		$full = DIR_SYSTEM . 'config/'. $id . '.php';
		if (file_exists($full)) {
			return $id;
		} 

		foreach ($sub_versions as $lite){
			if (file_exists(DIR_SYSTEM . 'config/'. $id . '_' . $lite . '.php')) {
				return $id . '_' . $lite;
			}
		}
		
		return false;
	}

	public function getConfigData($id, $config_key, $store_id = 0, $config_file = false){
		if(!$config_file){
			$config_file = $this->config_file;
		}

		if($config_file){
			$this->config->load($config_file);

		}

		$result = ($this->config->get($config_key)) ? $this->config->get($config_key) : array();

		if(!isset($this->request->post['config'])){
			$this->load->model('setting/setting');
			if ($this->model_setting_setting->getSetting($id, $store_id)) { 
				$setting = $this->model_setting_setting->getSetting($id, $store_id);

			}

			if(isset($setting[$config_key])){

				if(is_array($setting[$config_key])){
					$result = $this->array_merge_r_d($result, $setting[$config_key]);
				}else{
					$result = $setting[$config_key];
				}
			}
		}     
			
		return $result;
	}


	/*
	*	Return mbooth file.
	*/
	public function getMboothFile($id, $sub_versions){
		$full = DIR_SYSTEM . 'mbooth/xml/mbooth_'. $id .'.xml';
		if (file_exists($full)) {
			return 'mbooth_'. $id . '.xml';
		} else{
			foreach ($sub_versions as $lite){
				if (file_exists(DIR_SYSTEM . 'mbooth/xml/mbooth_'. $id . '_' . $lite . '.xml')) {
					$this->prefix = '_' . $lite;
					return 'mbooth_'. $id . '_' . $lite . '.xml';
				}
			}
		}
		return false;
	}

	/*
	*	Return mbooth file.
	*/
	public function getMboothInfo($mbooth_xml){
		if(file_exists(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml)){
			$xml = new SimpleXMLElement(file_get_contents(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml));
			return $xml;
		}else{
			return false;
		}
	}
	
	/*
	*	Check if another extension/module is installed.
	*/
	public function isInstalled($code) {
		$extension_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `code` = '" . $this->db->escape($code) . "'");
		if($query->row) {
			return true;
		}else{
			return false;
		}	
	}

}