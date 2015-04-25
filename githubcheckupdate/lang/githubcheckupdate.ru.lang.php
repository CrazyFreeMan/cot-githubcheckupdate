<?php defined('COT_CODE') or die('Wrong URL');
/*
 * Russian langfile for GitHub Check
 */

/*
 * Meta & configuration
 */
$L['info_name'] = 'GitHub Check';
$L['info_desc'] = 'Проверка обновлений на GitHub для плагинов Cotonti';

$L['cfg_github_list_ext'] = array('Список расширений и репозиториев','В одной строке код_расширения|владелец_репозитория|имя_репозитория|папка <br>Пример:<br>githubcheckupdate|CrazyFreeMan|cot-githubcheckupdate|githubcheckupdate');
/*
 * Main strings
 */
$L['gh_plugin'] = 'Плагин';
$L['gh_repo'] = 'Репозиторий';
$L['gh_ver'] = 'Версия';
$L['gh_ver_remote'] = 'Версия GitHub';
$L['gh_issue'] = 'GitHub';
$L['gh_new_issue'] = 'Создать задачу';
$L['gh_list_issue'] = 'Посмотреть задачи';
$L['gh_check'] = 'Действия';
$L['gh_chech_time'] = 'Проверка выполненна за ';

/*
 *  messege 
 */
$L['gh_cp_err'] = 'Не удается скопировать файлы расширения';
$L['gh_path_err'] = 'Неверный путь обновляемого плагина ';
$L['gh_write_err'] = 'Нет доступа на запись ';
$L['gh_exists_file_err'] = 'Файл поврежден или отсутствует';
$L['gh_downl_file_err'] = 'Файл не скачан';
$L['gh_upd_succ'] = "Расширение обновленно <script>
		$(function(){
			setTimeout(function () {
				location.href='admin.php?m=other&p=githubcheckupdate';
			}, 3000);
		});
	</script>";
$L['gh_upd_err'] = 'Не удается обновить расширения';