<?php

/**
 * @defgroup plugins_generic_journalLens JournalLens Plugin
 */

/**
 * @file plugins/generic/journalLens/index.php
 *
 * Copyright (c) 2026 JournalLens
 * Distributed under the GNU GPL v3.
 *
 * @ingroup plugins_generic_journalLens
 * @brief Wrapper for loading the JournalLens plugin (OJS 3.3).
 */

require_once('JournalLensPlugin.inc.php');

return new JournalLensPlugin();
