<?php

/**
 * @file plugins/generic/journalLens/JournalLensSettingsForm.php
 *
 * Copyright (c) 2026 JournalLens
 * Distributed under the GNU GPL v3.
 *
 * @class JournalLensSettingsForm
 *
 * @brief Settings form for the JournalLens plugin.
 */

namespace APP\plugins\generic\journalLens;

use APP\template\TemplateManager;
use PKP\form\Form;
use PKP\form\validation\FormValidatorCSRF;
use PKP\form\validation\FormValidatorCustom;
use PKP\form\validation\FormValidatorPost;

class JournalLensSettingsForm extends Form
{
    public function __construct(private JournalLensPlugin $plugin, private int $contextId)
    {
        parent::__construct($plugin->getTemplateResource('settings.tpl'));

        $this->addCheck(new FormValidatorCustom(
            $this,
            'baseUrl',
            'optional',
            'plugins.generic.journalLens.settings.baseUrlInvalid',
            fn ($url) => (bool) preg_match('#^https://[^\s/?\#]+(/[^\s?\#]*)?$#i', (string) $url)
        ));
        $this->addCheck(new FormValidatorPost($this));
        $this->addCheck(new FormValidatorCSRF($this));
    }

    public function initData(): void
    {
        $this->setData('baseUrl', $this->plugin->baseUrl($this->contextId));
        $this->setData('showInFooter', $this->plugin->showInFooter($this->contextId));
        parent::initData();
    }

    public function readInputData(): void
    {
        $this->readUserVars(['baseUrl', 'showInFooter']);
        $this->setData('baseUrl', trim((string) $this->getData('baseUrl')));
        parent::readInputData();
    }

    public function fetch($request, $template = null, $display = false)
    {
        TemplateManager::getManager($request)->assign('pluginName', $this->plugin->getName());
        return parent::fetch($request, $template, $display);
    }

    public function execute(...$functionArgs)
    {
        $baseUrl = rtrim($this->getData('baseUrl') ?: JournalLensPlugin::DEFAULT_BASE_URL, '/');
        $this->plugin->updateSetting($this->contextId, 'baseUrl', $baseUrl, 'string');
        $this->plugin->updateSetting($this->contextId, 'showInFooter', (bool) $this->getData('showInFooter'), 'bool');
        return parent::execute(...$functionArgs);
    }
}
