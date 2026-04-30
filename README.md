# typo3-locallang-formats

Registers Symfony's built-in translation loaders for TYPO3 locallang files,
enabling **YAML, JSON, PHP, INI, CSV, and PO** as alternatives to XLF.

## Requirements

TYPO3 14.3 or later

## Installation

```
composer require amdeu/typo3-locallang-formats
```

## How it works

TYPO3 v14 uses the [Symfony Translation component](https://symfony.com/doc/current/translation.html)
internally. This extension registers Symfony's built-in file loaders for additional
formats via `$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']`. No custom parser code —
just wiring.

XLF is always checked first (TYPO3 core default), so existing extensions are unaffected.

## Usage

Identical to XLF — use the file's actual extension in the path:

```
<f:translate key="LLL:EXT:my_ext/Resources/Private/Language/locallang.yaml:my.key" />
```

Follow TYPO3's locallang file naming convention, 
translations prefixed with the locale:

```
Resources/Private/Language/
    locallang.yaml        ← default (English)
    de.locallang.yaml     ← German
    fr.locallang.yaml     ← French
```

Nested keys are flattened with dot notation by Symfony's loaders:

```yaml
# locallang.yaml
login:
  title: Please log in
  submit: Submit
```

is referenced as `login.title`, `login.submit`.

## Supported formats

| Extension     | Format                                                                                                    |
|---------------|-----------------------------------------------------------------------------------------------------------|
| `yaml`, `yml` | YAML                                                                                                      |
| `json`        | JSON                                                                                                      |
| `php`         | PHP file returning an array                                                                               |
| `ini`         | INI key=value                                                                                             |
| `csv`         | CSV (`key,translation`)                                                                                   |
| `po`          | [Gettext PO](https://www.gnu.org/software/gettext/manual/html_node/PO-Files.html) — uses `msgid`/`msgstr` |

## Changing format priority

By default the order is `xlf, yaml, yml, json, php, ini, csv, po`.
To override, set:

```php
$GLOBALS['TYPO3_CONF_VARS']['LANG']['format']['priority'] = 'yaml,xlf,json,php,ini,csv,po';
```