<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */
	require_once cot_incfile('githubcheckupdate', 'plug');
	$t = new XTemplate(cot_tplfile('githubcheckupdate.admin', 'plug', true));	
		$githubcheckext = cot_get_list_ext();
		$i=0;
		foreach ($githubcheckext as $value) {
				$t->assign(cot_github_row_tags($value));
				$t->assign("GH_ODD", cot_build_oddeven($i));
				$t->parse('MAIN.ROW_PLUG');
				$i++;
		}	
	$t->parse('MAIN');
	$plugin_body = $t->text('MAIN');