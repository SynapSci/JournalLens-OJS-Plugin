{**
 * templates/block.tpl
 *
 * Copyright (c) 2026 JournalLens
 * Distributed under the GNU GPL v3.
 *
 * Sidebar block wrapping the JournalLens badge.
 *}
<div class="pkp_block block_journallens">
	<h2 class="title">{translate key="plugins.generic.journalLens.block.title"}</h2>
	<div class="content">
		{include file=$journalLensBadgeTemplate}
	</div>
</div>
