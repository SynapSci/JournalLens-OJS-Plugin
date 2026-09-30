<?php

/**
 * @file plugins/generic/journalLens/JournalLensPlugin.inc.php
 *
 * Copyright (c) 2026 JournalLens
 * Distributed under the GNU GPL v3.
 *
 * @class JournalLensPlugin
 * @ingroup plugins_generic_journalLens
 *
 * @brief Shows the journal's JournalLens Credibility Score badge in the page footer and/or a sidebar block.
 *        The badge is an image loaded by the reader's browser, so JournalLens can check that it is shown on
 *        one of the journal's registered websites. OJS 3.3 build.
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class JournalLensPlugin extends GenericPlugin {

	const DEFAULT_BASE_URL = 'https://journallens.info';

	public function register($category, $path, $mainContextId = null) {
		$success = parent::register($category, $path, $mainContextId);
		if ($success && $this->getEnabled($mainContextId)) {
			HookRegistry::register('TemplateManager::display', array($this, 'addStyles'));
			HookRegistry::register('Templates::Common::Footer::PageFooter', array($this, 'addFooterBadge'));
			$this->import('JournalLensBlockPlugin');
			PluginRegistry::register('blocks', new JournalLensBlockPlugin($this), $this->getPluginPath());
		}
		return $success;
	}

	public function getDisplayName() {
		return __('plugins.generic.journalLens.displayName');
	}

	public function getDescription() {
		return __('plugins.generic.journalLens.description');
	}

	public function getActions($request, $verb) {
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		return array_merge(
			$this->getEnabled() ? array(
				new LinkAction(
					'settings',
					new AjaxModal(
						$router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')),
						$this->getDisplayName()
					),
					__('manager.plugins.settings'),
					null
				),
			) : array(),
			parent::getActions($request, $verb)
		);
	}

	public function manage($args, $request) {
		if ($request->getUserVar('verb') === 'settings') {
			AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON, LOCALE_COMPONENT_PKP_MANAGER);
			$this->import('JournalLensSettingsForm');
			$form = new JournalLensSettingsForm($this, $request->getContext()->getId());
			if ($request->getUserVar('save')) {
				$form->readInputData();
				if ($form->validate()) {
					$form->execute();
					return new JSONMessage(true);
				}
			} else {
				$form->initData();
			}
			return new JSONMessage(true, $form->fetch($request));
		}
		return parent::manage($args, $request);
	}

	/**
	 * Register the badge stylesheet and script on reader-facing pages only.
	 */
	public function addStyles($hookName, $args) {
		$templateMgr = $args[0];
		$request = Application::get()->getRequest();
		if (!$request->getContext()) {
			return false;
		}
		$templateMgr->addStyleSheet(
			'journalLensBadge',
			// OJS only versions by app release; bust caches when the plugin's CSS changes
			$request->getBaseUrl() . '/' . $this->getPluginPath() . '/css/journallens.css?v='
				. @filemtime($this->getPluginPath() . '/css/journallens.css'),
			array('contexts' => 'frontend')
		);
		$templateMgr->addJavaScript(
			'journalLensBadge',
			$request->getBaseUrl() . '/' . $this->getPluginPath() . '/js/journallens.js?v='
				. @filemtime($this->getPluginPath() . '/js/journallens.js'),
			array('contexts' => 'frontend')
		);
		return false;
	}

	/**
	 * Footer hook. Skipped when the journal turned the footer badge off (e.g. it uses the sidebar block).
	 */
	public function addFooterBadge($hookName, $args) {
		$output =& $args[2];
		$request = Application::get()->getRequest();
		$context = $request->getContext();
		if (!$context || !$this->showInFooter($context->getId())) {
			return false;
		}
		$templateMgr = TemplateManager::getManager($request);
		if ($this->assignBadge($templateMgr)) {
			$output .= $templateMgr->fetch($this->getTemplateResource('credibilityBadge.tpl'));
		}
		return false;
	}

	public function showInFooter($contextId) {
		$value = $this->getSetting($contextId, 'showInFooter');
		return $value === null ? true : (bool) $value;
	}

	public function baseUrl($contextId) {
		$url = $this->getSetting($contextId, 'baseUrl');
		return rtrim($url ? $url : self::DEFAULT_BASE_URL, '/');
	}

	/**
	 * Assign badge variables for the current journal.
	 *
	 * @return bool false when the journal has no valid ISSN
	 */
	public function assignBadge($templateMgr) {
		$context = Application::get()->getRequest()->getContext();
		if (!$context) {
			return false;
		}
		$issn = null;
		foreach (array($context->getData('onlineIssn'), $context->getData('printIssn')) as $candidate) {
			$candidate = strtoupper(trim((string) $candidate));
			if (preg_match('/^\d{4}-\d{3}[\dX]$/', $candidate)) {
				$issn = $candidate;
				break;
			}
		}
		if (!$issn) {
			return false;
		}
		$base = $this->baseUrl($context->getId());
		$templateMgr->assign(array(
			'journalLensBadgeUrl' => $base . '/api/badges/issn/' . rawurlencode($issn) . '/badge.svg',
			'journalLensVerifyUrl' => $base . '/verify/' . rawurlencode($issn) . '?utm_source=ojs-plugin&utm_medium=badge',
			'journalLensBadgeTemplate' => $this->getTemplateResource('credibilityBadge.tpl'),
		));
		return true;
	}
}
