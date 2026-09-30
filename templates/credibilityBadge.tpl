{**
 * templates/credibilityBadge.tpl
 *
 * Copyright (c) 2026 JournalLens
 * Distributed under the GNU GPL v3.
 *
 * JournalLens credibility badge. The image is served by JournalLens; referrerpolicy lets it confirm the site.
 *}
<div class="journallens-badge">
	<a class="journallens-badge__link" href="{$journalLensVerifyUrl|escape}" target="_blank" rel="noopener" referrerpolicy="strict-origin" title="{translate key="plugins.generic.journalLens.badge.linkTitle"}">
		<img class="journallens-badge__img" src="{$journalLensBadgeUrl|escape}" alt="{translate key="plugins.generic.journalLens.badge.alt"}" height="28" loading="lazy" referrerpolicy="strict-origin">
	</a>
</div>
