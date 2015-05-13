<?php defined('COT_CODE') or die('Wrong URL');
require_once cot_incfile('githubcheckupdate', 'plug', 'resources');
/**
 * [cot_github_get_remote_setup_file_link Return link to ext setup file]
 * @param  [array] $parameters 
 * @return [string]             
 */
function cot_github_get_remote_setup_file_link($parameters){
	$folder = (!empty($parameters["folder"])) ? $parameters["folder"].'/' : '';
	 return 'https://raw.githubusercontent.com/'.$parameters["author"].'/'.$parameters["reponame"].'/master/'.$folder.$parameters["code"].'.setup.php';
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
						$tmp = cot_github_parse_config_row($cfg['plugin'][$value['ct_code']]['githubcheck']);
						$res[$value['ct_code']]['githubcheckurl'] = cot_github_get_remote_setup_file_link($tmp);
						$res[$value['ct_code']]['githubdownlloadurl'] = cot_github_get_download_link($tmp);
						$res[$value['ct_code']]['githubfolder']= $tmp['folder'];
						$res[$value['ct_code']]['githubreponame']= $tmp['reponame'];
					}	 	   		
	 	    }else{
		 	    	if(isset($cfg[$value['ct_code']]['githubcheck'])){
		 	    		$tmp = cot_github_parse_config_row($cfg[$value['ct_code']]['githubcheck']);
		 	    		$res[$value['ct_code']]['githubcheckurl']= cot_github_get_remote_setup_file_link($tmp);
		 	    		$res[$value['ct_code']]['githubdownlloadurl'] = cot_github_get_download_link($tmp);
		 	    		$res[$value['ct_code']]['githubfolder']= $tmp['folder'];
		 	    		$res[$value['ct_code']]['githubreponame']= $tmp['reponame'];
		 	    	}	
	 	    }
	 	    if(isset($gitgub_list[$value['ct_code']])){ 	   		
	 	   		$res[$value['ct_code']]['githubcheckurl']= $gitgub_list[$value['ct_code']]['githubcheckurl'];
	 	    	$res[$value['ct_code']]['githubdownlloadurl']= $gitgub_list[$value['ct_code']]['githubdownlloadurl'];
	 			$res[$value['ct_code']]['githubfolder']= $gitgub_list[$value['ct_code']]['githubfolder'];
	 			$res[$value['ct_code']]['githubreponame']= $gitgub_list[$value['ct_code']]['githubreponame'];
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
					'githubdownlloadurl' =>cot_github_get_download_link($val),
					'githubfolder' => $val['folder'],
					'githubreponame' => $val['reponame']
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
function cot_get_list_ext($update=0){	
	global $db, $Ls, $L, $cache, $sys;
	$data = ($cache) ? $cache->db->get('github_check') : '' ;
	if (is_null($data) || empty($data) || $update){	
		$start_time = cot_get_time();
		$data = cot_github_get_ext_for_check();
		foreach ($data as $key => $value) {				
			$tmp_info = cot_infoget($value['githubcheckurl']);
			$data[$value['code']]['version_gh'] = (empty($tmp_info['Version'])) ? '-' : $tmp_info['Version'] ;
			$data[$value['code']]['date_upd'] = $tmp_info['Date'];
		}
		$data['last_update'] = (int)$sys['now'];
		$msg = $L['gh_chech_time'].cot_build_friendlynumber(bcsub(cot_get_time(),$start_time,10),array('1' => $Ls['Seconds'],'0.001' => $Ls['Milliseconds']),3);			
		cot_message($msg);
		if($cache) {
			$cache->db->store('github_check', $data);
			cot_redirect(cot_url('admin', array('m' =>'other','p'=>'githubcheckupdate'),'', true));
		}
			 
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
	$urldown = "<a href='".$param['githubdownlloadurl']['download']."' class='btn btn-default' title='".$L['Download']."'><span class='icon-download-alt'></span></a>";
	$urlajaxupdate = "<a  href='".cot_url('index', 'r=githubcheckupdate&extupd='.$param[code])."' title=".$L['Update']." class='btn btn-default ajax' rel='get-infoShow' title='".$L['Update']."'><span class='icon-refresh'></span></a>"; //TODO
	return array(
					'GH_ROW_PLUG_CODE' => '<a href='.cot_url('admin', 'm=extensions&a=details&'.$type.'='.$param['code']).' target="_blank">'.$param['title'].'</a></span>',
					'GH_ROW_PLUG_REPO' => $param['githubdownlloadurl']['repository'],					
					'GH_ROW_PLUG_VERSION' => $param['version'],
					'GH_ROW_PLUG_VERSION_REMOTE' =>  ($param['version_gh'] != '-' && $param['version_gh'] != $param['version']) ? "<span class='label label-warning'>".$param['version_gh']."<span>" : $param['version_gh'],
					'GH_ROW_PLUG_DOWNLOAD' => $urldown,
					'GH_ROW_PLUG_UPDATE' =>  ($param['version_gh'] != '-' && $param['version_gh'] != $param['version']) ? $urlajaxupdate : ''
				);
}
/**
 * [cot_get_time Help funct]
 * @return [type]
 */
function cot_get_time(){
	$start_time = explode(' ',microtime()); 
	return $real_time = $start_time[1].substr($start_time[0],1); 
}
/**
 * [cot_github_scandir Help funct]
 * @return [type]
 */
function cot_github_scandir($dir)
{
    $list = scandir($dir);
    unset($list[0],$list[1]);
    return array_values($list);
}
/**
 * [cot_github_clear_dir Help funct]
 * @return [type]
 */
function cot_github_clear_dir($dir)
{
    $list = cot_github_scandir($dir);    
    foreach ($list as $file)
    {
        if (is_dir($dir.$file))
        {
            cot_github_clear_dir($dir.$file.'/');
            rmdir($dir.$file);
        }
        else
        {
            unlink($dir.$file);
        }
    }
}
/**
 * [cot_github_backup Backup plugin before update]
 * @param  [type] $source      [plugin dir]
 * @param  [type] $destination [backup to]
 * @return [type]              [description]
 */
function cot_github_backup($source, $destination) {
      if (extension_loaded('zip')) {
          if (file_exists($source)) {
              $zip = new ZipArchive();
              if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
                  $source = realpath($source);
                  if (is_dir($source)) {
                      $iterator = new RecursiveDirectoryIterator($source);
                      // skip dot files while iterating 
                      $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
                      $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
                      foreach ($files as $file) {
                          $file = realpath($file);
                          if (is_dir($file)) {
                              $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                          } else if (is_file($file)) {
                              $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                          }
                      }
                  } else if (is_file($source)) {
                      $zip->addFromString(basename($source), file_get_contents($source));
                  }
              }
              return $zip->close();
          }
      }
      return false;
  }