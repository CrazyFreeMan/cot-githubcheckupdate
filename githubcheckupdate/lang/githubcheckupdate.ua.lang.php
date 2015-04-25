<?php defined('COT_CODE') or die('Wrong URL');
/*
 * Ukrainian langfile for GitHub Check
 */

/*
 * Meta & configuration
 */
$L['info_name'] = 'GitHub Check';
$L['info_desc'] = 'Перевірка оновлень з GitHub для розширенять Cotonti';

$L['cfg_github_list_ext'] = array('Список розширень і репозиторіїв','В одному рядку код_розширення|власник_репозиторія|імя_репозиторія|тека <br>Приклад:<br>githubcheckupdate|CrazyFreeMan|cot-githubcheckupdate|githubcheckupdate');
/*
 * Main strings
 */
$L['gh_plugin'] = 'Розширення';
$L['gh_repo'] = 'Пепозиторій';
$L['gh_ver'] = 'Версія';
$L['gh_ver_remote'] = 'Версія GitHub';
$L['gh_issue'] = 'GitHub';
$L['gh_new_issue'] = 'Створити завдання';
$L['gh_list_issue'] = 'Перегляд завдань';
$L['gh_check'] = 'Дія';
$L['gh_chech_time'] = 'Перевірка виконана за ';

/*
 *  messege 
 */
$L['gh_cp_err'] = 'Не вдалось скопіювати файли розширення';
$L['gh_path_err'] = 'Некоректний шлях оновлюваного розширення ';
$L['gh_write_err'] = 'Відсутній доступ на запис ';
$L['gh_exists_file_err'] = 'Файл пошкоджений або відсутній';
$L['gh_downl_file_err'] = 'Файл не завантажено';
$L['gh_upd_succ'] = "Розширення оновлено <script>
		$(function(){
			setTimeout(function () {
				location.href='admin.php?m=other&p=githubcheckupdate';
			}, 3000);
		});
	</script>";
$L['gh_upd_err'] = 'Не вдалось оновити розширення, спробуйте вручну на сторінці розширення';