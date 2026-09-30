<?php

/**
 * @file plugins/generic/journalLens/JournalLensBlockPlugin.php
 *
 * Copyright (c) 2026 JournalLens
 * Distributed under the GNU GPL v3.
 *
 * @class JournalLensBlockPlugin
 *
 * @brief Sidebar block showing the JournalLens badge. Placed via Website > Appearance > Sidebar.
 */

namespace APP\plugins\generic\journalLens;

use PKP\plugins\BlockPlugin;

class JournalLensBlockPlugin extends BlockPlugin
{
    public function __construct(protected JournalLensPlugin $parentPlugin)
    {
        parent::__construct();
    }

    public function getEnabled($contextId = null)
    {
        return $this->parentPlugin->getEnabled($contextId);
    }

    public function getHideManagement()
    {
        return true;
    }

    public function getDisplayName()
    {
        return __('plugins.generic.journalLens.block.displayName');
    }

    public function getDescription()
    {
        return __('plugins.generic.journalLens.block.description');
    }

    public function getPluginPath()
    {
        return $this->parentPlugin->getPluginPath();
    }

    public function getContents($templateMgr, $request = null)
    {
        if (!$this->parentPlugin->assignBadge($templateMgr)) {
            return '';
        }
        return parent::getContents($templateMgr, $request);
    }
}
