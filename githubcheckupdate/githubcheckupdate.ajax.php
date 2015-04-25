<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=ajax
[END_COT_EXT]
==================== */	
if(COT_AJAX){
	require_once cot_incfile('githubcheckupdate', 'plug');
	global $db, $L, $sys, $cache, $cfg;	
	$extupd = cot_import('extupd', 'G', 'TXT');
	if (empty($extupd)){
		 echo cot_rc('github_error', array('text' => $L['Error']));
		}else{							
				require_once cot_incfile('extensions');			
				$data = $cache->db->get('github_check');
				$tmp_download_file = "datas/githubcheck/update_".$extupd.".zip";
				@file_put_contents($tmp_download_file, file_get_contents($data[$extupd]['githubdownlloadurl']['download']));
					
				if(@file_exists($tmp_download_file)){
					$update_zip = new ZipArchive(); 					 
					//Окрываем архив update_$extupd.zip
					if ($update_zip->open($tmp_download_file) == TRUE){						
						$path = $data[$extupd]['plug'] ? $cfg['plugins_dir'] . "/".$extupd. "/" : $cfg['modules_dir'] . "/".$extupd. "/";
						if(@substr(decoct(fileperms($path)),2) >= '750'){							
							$tmp_download_dir = "datas/githubcheck/update_".$extupd."/";
							$update_zip->extractTo($tmp_download_dir);
							$update_zip->close();
							@unlink($tmp_download_file);
							$folder = (!empty($data[$extupd]["githubfolder"])) ? $data[$extupd]["githubfolder"]. "/" : '';
							$full_tmp_patch = $tmp_download_dir.$data[$extupd]['githubreponame']."-master/".$folder;
							
								if(@is_dir($full_tmp_patch)){
										$backup_name_zip = "datas/githubcheck/".$data[$extupd]['code'].$data[$extupd]['version'].cot_date("_Y_m_d_H_i_s", $sys['now']).".zip";
										cot_github_backup($path,$backup_name_zip);	//backup plugin	
										cot_github_clear_dir($path); //cleat tmp plugin dir
									if (@rename($full_tmp_patch, $path)) {										
										 $result = cot_extension_install($extupd,($data[$extupd]['plug']) ? false : true ,true,true);
										 cot_github_clear_dir($tmp_download_dir); //cleat tmp plugin dir
										 @rmdir($tmp_download_dir);
										 cot_clear_messages(); // видаляем всі повідомлення, щоб не дублювались після оновлення
										 echo ($result) ? cot_rc('github_success', array('text' => $data[$extupd]['title'].": ".$L['gh_upd_succ']))  : cot_rc('github_error', array('text' =>  $data[$extupd]['title'].": ".$L['gh_upd_err'])) ;								   
									}else{										
										 cot_github_clear_dir($tmp_download_dir);
										 @rmdir($tmp_download_dir);
										 echo cot_rc('github_error', array('text' => $L['gh_cp_err'].":<br>".$full_tmp_patch." ><br>".$path)); 
										 exit;
									}									
								}else{
									echo cot_rc('github_error', array('text' => $L['gh_path_err'].$full_tmp_patch)); 
									exit;
								}							
						}else{															
							echo cot_rc('github_error', array('text' => $L['gh_write_err'].$path)); 
							exit;
						}
					}else{		
						@unlink($tmp_download_file);				
						echo cot_rc('github_error', array('text' => $L['gh_exists_file_err'].$path));  
						exit;
					}				
				}else{
					echo cot_rc('github_error', array('text' => $L['gh_downl_file_err'].$path)); 
					exit;
				}
		}	
}