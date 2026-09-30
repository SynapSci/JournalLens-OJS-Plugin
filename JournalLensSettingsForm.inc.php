<?php

/**
 * @file plugins/generic/journalLens/JournalLensSettingsForm.inc.php
 *
 * Copyright (c) 2026 JournalLens
 * Distributed under the GNU GPL v3.
 *
 * @class JournalLensSettingsForm
 * @ingroup plugins_generic_journalLens
 *
 * @brief Settings form for the JournalLens plugin (OJS 3.3).
 */

import('lib.pkp.classes.form.Form');

class JournalLensSettingsForm extends Form {

	/** @var JournalLensPlugin */
	private $_plugin;

	/** @var int */
	private $_contextId;

	public function __construct($plugin, $contextId) {
		$this->_plugin = $plugin;
		$this->_contextId = $contextId;
		parent::__construct($plugin->getTemplateResource('settings.tpl'));

		$this->addCheck(new FormValidatorCustom(
			$this,
			'baseUrl',
			'optional',
			'plugins.generic.journalLens.settings.baseUrlInvalid',
			function ($url) {
				return (bool) preg_match('#^https://[^\s/?\#]+(/[^\s?\#]*)?$#i', (string) $url);
			}
		));
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	public function initData() {
		$this->setData('baseUrl', $this->_plugin->baseUrl($this->_contextId));
		$this->setData('showInFooter', $this->_plugin->showInFooter($this->_contextId));
		parent::initData();
	}

	public function readInputData() {
		$this->readUserVars(array('baseUrl', 'showInFooter'));
		$this->setData('baseUrl', trim((string) $this->getData('baseUrl')));
		parent::readInputData();
	}

	public function fetch($request, $template = null, $display = false) {
		TemplateManager::getManager($request)->assign('pluginName', $this->_plugin->getName());
		return parent::fetch($request, $template, $display);
	}

	public function execute(...$functionArgs) {
		$baseUrl = $this->getData('baseUrl');
		$baseUrl = rtrim($baseUrl ? $baseUrl : JournalLensPlugin::DEFAULT_BASE_URL, '/');
		$this->_plugin->updateSetting($this->_contextId, 'baseUrl', $baseUrl, 'string');
		$this->_plugin->updateSetting($this->_contextId, 'showInFooter', (bool) $this->getData('showInFooter'), 'bool');
		return parent::execute(...$functionArgs);
	}
}
