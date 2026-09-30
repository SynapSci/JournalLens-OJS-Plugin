{**
 * templates/settings.tpl
 *
 * Copyright (c) 2026 JournalLens
 * Distributed under the GNU GPL v3.
 *
 * Settings form for the JournalLens plugin.
 *}
<script>
	$(function() {ldelim}
		$('#journalLensSettings').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

<form
	class="pkp_form"
	id="journalLensSettings"
	method="POST"
	action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}"
>
	{csrf}
	{include file="common/formErrors.tpl"}

	{fbvFormArea id="journalLensSettingsArea"}
		{fbvFormSection list=true label="plugins.generic.journalLens.settings.placement" description="plugins.generic.journalLens.settings.placementDescription"}
			{fbvElement type="checkbox" id="showInFooter" value="1" checked=$showInFooter label="plugins.generic.journalLens.settings.showInFooter"}
		{/fbvFormSection}
		{fbvFormSection label="plugins.generic.journalLens.settings.baseUrl" description="plugins.generic.journalLens.settings.baseUrlDescription"}
			{fbvElement type="text" id="baseUrl" value=$baseUrl}
		{/fbvFormSection}
	{/fbvFormArea}

	{fbvFormButtons submitText="common.save"}
</form>
