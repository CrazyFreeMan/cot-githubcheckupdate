<!-- BEGIN: MAIN -->
<div class="container">
<style>
	div.row.even { background: rgba(0,0,0,0.04); }
</style>
	<div class="row">
		<div class="span12">
			<div class="row">
				<div class="span10">
				{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
				<!-- IF {GH_CHECKTIME} --> <div class="alert alert-info">{PHP.L.gh_chech_time}{GH_CHECKTIME}</div><!-- ENDIF --></div>
				<div class="span2"><a href="{PHP|cot_url('admin','m=other&p=githubcheckupdate&update=1')}" class="btn btn-info">{PHP.L.Update}</a></div>
			</div>
			<div class="row">
				<div class="span3">{PHP.L.gh_plugin}</div>
				<div class="span2">{PHP.L.gh_repo}</div>
				<div class="span2">{PHP.L.gh_issue}</div>
				<div class="span1">{PHP.L.gh_ver}</div>
				<div class="span2">{PHP.L.gh_ver_remote}</div>
				<div class="span2">{PHP.L.gh_check}</div>
			</div>
		<!-- BEGIN: ROW_PLUG -->
			<div class="row {GH_ODD}">
				<div class="span3">{GH_ROW_PLUG_CODE}</div>
				<div class="span2"><a href='{GH_ROW_PLUG_REPO}' target="_blank">{PHP.L.Open}</a></div>
				<div class="span2">
				<a href="{GH_ROW_PLUG_REPO}/issues" target="_blank" class="btn btn-default" title="{PHP.L.gh_list_issue}">?</a>
				<a href="{GH_ROW_PLUG_REPO}/issues/new" target="_blank" class="btn btn-default" title="{PHP.L.gh_new_issue}">+</a>
				</div>
				<div class="span1">{GH_ROW_PLUG_VERSION}</div>
				<div class="span2">{GH_ROW_PLUG_VERSION_REMOTE}</div>
				<div class="span2">{GH_ROW_PLUG_DOWNLOAD}</div>
			</div>
		<!-- END: ROW_PLUG -->
		</div>
	</div>
</div>
<!-- END: MAIN -->