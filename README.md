# locallang_formats — Alternative translation file formats for TYPO3

Author your TYPO3 translation labels in YAML, JSON, or PHP arrays instead of XLF.

## Requirements

- TYPO3 13.x

## Installation

```bash
composer require amdeu/locallang-formats
```

No further configuration needed in consuming extensions. Once installed, TYPO3
will pick up `.yaml`, `.json`, and `.php` files in any extension's
`Resources/Private/Language/` folder.

## File format priority

The default priority order is `xlf → yaml → json → php`. XLF is always checked
first so existing extensions are completely unaffected.

To change the priority globally (e.g. to prefer YAML over XLF in your project),
change the `priority` setting in `TYPO3_CONF_VARS`:

```php
$GLOBALS['TYPO3_CONF_VARS']['SYS']['lang']['format']['priority'] = 'yaml,xlf,json,php';
```

---

## YAML

```yaml
# locallang.yaml
header_comment: The default Header Comment.

login:
  title: Please log in
  submit: Submit
  errors:
    invalid: Invalid credentials
```

Translation file (`de.locallang.yaml`):

```yaml
header_comment: Der Standard-Header-Kommentar.

login:
  title: Bitte einloggen
  submit: Absenden
  errors:
    invalid: Ungültige Anmeldedaten
```

---

## JSON

```json
{
    "header_comment": "The default Header Comment.",
    "login": {
        "title": "Please log in",
        "submit": "Submit",
        "errors": {
            "invalid": "Invalid credentials"
        }
    }
}
```

Translation file (`de.locallang.json`):

```json
{
    "header_comment": "Der Standard-Header-Kommentar.",
    "login": {
        "title": "Bitte einloggen",
        "submit": "Absenden"
    }
}
```

---

## PHP arrays

```php
<?php
// locallang.php
return [
    'header_comment' => 'The default Header Comment.',
    'login' => [
        'title' => 'Please log in',
        'submit' => 'Submit',
        'errors' => [
            'invalid' => 'Invalid credentials',
        ],
    ],
];
```

Translation file (`de.locallang.php`):

```php
<?php
// de.locallang.php
return [
    'header_comment' => 'Der Standard-Header-Kommentar.',
    'login' => [
        'title' => 'Bitte einloggen',
        'submit' => 'Absenden',
    ],
];
```

---

## Multi-language files

All formats follow the same naming convention as XLF:

```
Resources/Private/Language/
    locallang.yaml          ← default language (English)
    de.locallang.yaml       ← German
    fr.locallang.yaml       ← French
    locallang_be.yaml       ← additional files work the same way
```

---

## Usage in templates and PHP

Identical to XLF — just use the file's actual extension in the path:

```html
<f:translate key="LLL:EXT:my_ext/Resources/Private/Language/locallang.yaml:login.title" />
```

```php
LocalizationUtility::translate(
    'LLL:EXT:my_ext/Resources/Private/Language/locallang.yaml:login.title',
    'MyExt'
);
```
