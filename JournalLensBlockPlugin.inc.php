<?php

/**
 * @file plugins/generic/journalLens/JournalLensBlockPlugin.inc.php
 *
 * Copyright (c) 2026 JournalLens
 * Distributed under the GNU GPL v3.
 *
 * @class JournalLensBlockPlugin
 * @ingroup plugins_generic_journalLens
 *
 * @brief Sidebar block showing the JournalLens badge. Placed via Website > Appearance > Sidebar.
 */

import('lib.pkp.classes.plugins.BlockPlugin');

class JournalLensBlockPlugin extends BlockPlugin {

	/** @var JournalLensPlugin */
	protected $_parentPlugin;

	public function __construct($parentPlugin) {
		parent::__construct();
		$this->_parentPlugin = $parentPlugin;
	}

	public function getEnabled($contextId = null) {
		return $this->_parentPlugin->getEnabled($contextId);
	}

	public function getHideManagement() {
		return true;
	}

	public function getDisplayName() {
		return __('plugins.generic.journalLens.block.displayName');
	}

	public function getDescription() {
		return __('plugins.generic.journalLens.block.description');
	}

	public function getPluginPath() {
		return $this->_parentPlugin->getPluginPath();
	}

	public function getContents($templateMgr, $request = null) {
		if (!$this->_parentPlugin->assignBadge($templateMgr)) {
			return '';
		}
		return parent::getContents($templateMgr, $request);
	}
}
