# JournalLens Credibility Badge - OJS Plugin (OJS 3.4 and 3.5)

Shows your journal's JournalLens Credibility Score on your OJS site, in the page footer and/or as a
sidebar block. For OJS 3.3 use the `stable-3_3_0` branch of this repository.

## Requirements

- OJS 3.5 (LTS) or 3.4. Tested on 3.5.0-5 and 3.4.0-11.
- An online or print ISSN set in Settings > Journal > Masthead.

## Installation

1. Copy this folder to `plugins/generic/journalLens/` in your OJS installation.
2. Go to Settings > Website > Plugins > Generic Plugins and enable "JournalLens Credibility Badge".
3. Optional: to show the badge in the sidebar, go to Settings > Website > Appearance > Setup >
   Sidebar Management and add "JournalLens Credibility Badge (sidebar)".

## How It Works

- The plugin reads the journal's online ISSN (then print ISSN) and places a badge image from
  `https://journallens.info/api/badges/issn/{ISSN}/badge.svg` in the footer and/or the sidebar block.
- The image is loaded by the reader's browser, which tells JournalLens which website it is shown on.
  The score is shown only when that website is one of the journal's websites registered on JournalLens:

  | Where the badge is shown | What readers see |
  |--------------------------|------------------|
  | The journal's registered website | `Credibility Score 75/100 High` (green, amber or red by score) |
  | Any other website (for example a copy of the journal's site) | `Credibility Score - Verify` (neutral, no score) |
  | ISSN not in JournalLens | `Credibility Score - Not yet rated` |

- Clicking the badge opens a public JournalLens page that tells the reader whether the site they came
  from is a registered website of the journal. No JournalLens account is needed.
- Your OJS server never calls JournalLens and nothing is stored. If the badge image cannot be loaded,
  the badge and its sidebar block are hidden, so readers never see an empty box.
- The badge sets no cookies.

### Badge shows "Verify" on your own site?

JournalLens does not have your current website address yet. Register it at
https://journallens.info/journal/register or contact support@journallens.info.

## Settings

| Setting | Default | Notes |
|---------|---------|-------|
| Show the badge in the page footer | On | Turn off if you only want the sidebar block |
| JournalLens address | `https://journallens.info` | Must start with `https://`. Only change if JournalLens support asks you to |

## Themes

Works with any theme that calls the standard OJS hooks `Templates::Common::Footer::PageFooter` (footer)
and `Templates::Common::Sidebar` (sidebar), including the Default, Health Sciences, Pragma, Classic and
Immersion themes.

## Languages

English, Spanish, Brazilian Portuguese, Indonesian and Swedish (settings, sidebar title and image
description). Corrections and new translations are welcome.

## File Structure

```
journalLens/
  JournalLensPlugin.php        - Plugin class (hooks, badge markup)
  JournalLensBlockPlugin.php   - Sidebar block
  JournalLensSettingsForm.php  - Settings form
  version.xml                  - Version metadata
  LICENSE                      - GNU GPL v3
  css/journallens.css          - Badge layout
  js/journallens.js            - Hides the badge if the image cannot be loaded
  locale/*/locale.po           - Translations
  templates/credibilityBadge.tpl
  templates/block.tpl
  templates/settings.tpl
```

## Source

https://github.com/SynapSci/JournalLens-OJS-Plugin

## License

GNU General Public License v3.0, see [LICENSE](LICENSE).

## Contact

- Website: https://journallens.info
- Support: support@journallens.info
