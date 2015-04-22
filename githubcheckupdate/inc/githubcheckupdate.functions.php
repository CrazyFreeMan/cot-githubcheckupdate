<?php defined('COT_CODE') or die('Wrong URL');
/**
 * [cot_github_get_remote_setup_file_link Return link to ext setup file]
 * @param  [array] $parameters 
 * @return [string]             
 */
function cot_github_get_remote_setup_file_link($parameters){
	 return 'https://raw.githubusercontent.com/'.$parameters["author"].'/'.$parameters["reponame"].'/master/'.$parameters["folder"].'/'.$parameters["code"].'.setup.php';
}
/**
 * [cot_github_get_download_link  Return link for download master.zip ]
 * @param  [array] $parameters
 * @return [string]            
 */
function cot_github_get_download_link($parameters){

	 $link['download'] = 'https://github.com/'.$parameters["author"].'/'.$parameters["reponame"].'/archive/master.zip';
	 $link['repository'] = 'https://github.com/'.$parameters["author"].'/'.$parameters["reponame"];
	return $link;
}

/**
 * [cot_github_get_ext_for_check Get list of all ext for chech update]
 * @return [array] 
 */
function cot_github_get_ext_for_check(){
	global $db, $cfg, $db_core;
	 $result = $db->query("SELECT * FROM $db_core WHERE ct_state=1 AND ct_lock=0")->fetchAll();
	 $gitgub_list = cot_github_parse_list_ext();
	 foreach ($result as $value) {
	 			$res[$value['ct_code']]['code'] = $value['ct_code'];	 	   
				$res[$value['ct_code']]['title'] = $value['ct_title'];
				$res[$value['ct_code']]['version'] = $value['ct_version'];
				$res[$value['ct_code']]['plug'] = $value['ct_plug'];
			if($value['ct_plug']){
					if(isset($cfg['plugin'][$value['ct_code']]['githubcheck'])){
						$res[$value['ct_code']]['githubcheckurl'] = cot_github_get_remote_setup_file_link(cot_github_parse_config_row($cfg['plugin'][$value['ct_code']]['githubcheck']));
						$res[$value['ct_code']]['githubdownlloadurl'] = cot_github_get_download_link(cot_github_parse_config_row($cfg['plugin'][$value['ct_code']]['githubcheck']));
					}	 	   		
	 	    }else{
		 	    	if(isset($cfg[$value['ct_code']]['githubcheck'])){
		 	    		$res[$value['ct_code']]['githubcheckurl']= cot_github_get_remote_setup_file_link(cot_github_parse_config_row($cfg[$value['ct_code']]['githubcheck']));
		 	    		$res[$value['ct_code']]['githubdownlloadurl'] = cot_github_get_download_link(cot_github_parse_config_row($cfg[$value['ct_code']]['githubcheck']));
		 	    	}	
	 	    }
	 	    if(isset($gitgub_list[$value['ct_code']])){ 	   		
	 	   		$res[$value['ct_code']]['githubcheckurl']= $gitgub_list[$value['ct_code']]['githubcheckurl'];
	 	    	$res[$value['ct_code']]['githubdownlloadurl']= $gitgub_list[$value['ct_code']]['githubdownlloadurl'];
	 	
	 	    }else if(empty($res[$value['ct_code']]['githubcheckurl'])){
	 	    	unset($res[$value['ct_code']]);
	 	    } 	 	    
		 }
	return $res;
}
/**
 * [cot_github_parse_config_row Parse row]
 * @param  [string] $row
 * @return [array]     
 */
function cot_github_parse_config_row($row){
			list($rtn['code'],$rtn['author'],$rtn['reponame'],$rtn['folder']) = explode('|', $row);			
	return $rtn;
}
/**
 * [cot_github_parse_list_ext Load from config github check]
 * @return [array] 
 */
function cot_github_parse_list_ext(){
	global $cfg;
	if(!empty($cfg['plugin']['githubcheckupdate']['github_list_ext'])){	
		$listext = str_replace("\r\n", "\n", $cfg['plugin']['githubcheckupdate']['github_list_ext']);
		$listext = explode("\n", $listext);		
		foreach ($listext as $key => $value)
		{		
			$val = cot_github_parse_config_row($value);
			if (count($val) > 1)
			{
				$set_array[$val['code']] = array(
					'githubcheckurl' =>cot_github_get_remote_setup_file_link($val),
					'githubdownlloadurl' =>cot_github_get_download_link($val)
				);
			}
		}
		return $set_array;
	}
	return false;
}
/**
 * [cot_get_list_ext Load info from github]
 * @return [array]
 */
function cot_get_list_ext(){
	$data = cot_github_get_ext_for_check();
	foreach ($data as $key => $value) {
		$tmp_info = cot_infoget($value['githubcheckurl']);		
		$data[$value['code']]['version_gh'] = (empty($tmp_info['Version'])) ? '-' : $tmp_info['Version'] ;
		$data[$value['code']]['date_upd'] = $tmp_info['Date'];
	}
	return $data;
}
/**
 * [cot_github_row_tags Generate tags for each rowa]
 * @param  [array] $param 
 * @return [array]       
 */
function cot_github_row_tags($param){
	global $L;
	$type = ($param['plug']) ? 'pl' : 'mod' ;
	$urldown = "<a href='".$param['githubdownlloadurl']['download']."' >".$L['Download']."</a>";
	return array(
					'GH_ROW_PLUG_CODE' => '<a href='.cot_url('admin', 'm=extensions&a=details&'.$type.'='.$param['code']).' target="_blank">'.$param['title'].'</a>',
					'GH_ROW_PLUG_REPO' => '<a href='.$param['githubdownlloadurl']['repository'].' target="_blank">'.$L['Open'].'</a>',
					'GH_ROW_PLUG_VERSION' => $param['version'],
					'GH_ROW_PLUG_VERSION_REMOTE' =>  ($param['version_gh'] != '-' && $param['version_gh'] != $param['version']) ? "<span class='label label-warning'>".$param['version_gh']."<span>" : $param['version_gh'],
					'GH_ROW_PLUG_DOWNLOAD' => $urldown
				);
}