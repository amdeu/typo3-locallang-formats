<?php

declare(strict_types=1);

namespace Amdeu\LocallangFormats\Parser;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Localization\Exception\FileNotFoundException;
use TYPO3\CMS\Core\Localization\Exception\InvalidXmlFileException;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Core\Localization\Parser\LocalizationParserInterface;
use TYPO3\CMS\Core\Localization\Parser\AbstractXmlParser;

/**
 * Shared base for alternative language file format parsers.
 *
 * Subclasses implement parseFile() to return a raw array from their format.
 * This class handles:
 *   - Nested array flattening to dot-notation keys
 *   - Building the LOCAL_LANG structure TYPO3 expects
 *   - Loading translation files via TYPO3's localized file path pattern
 */
abstract class AbstractFlatFileParser implements LocalizationParserInterface
{
    /**
     * Parse the raw file and return an associative array.
     * Both flat and nested structures are accepted.
     */
    abstract protected function parseFile(string $filePath): array;

	/**
	 * @var string
	 */
	protected $sourcePath;

	/**
	 * @var string
	 */
	protected $languageKey;

	/**
	 * taken from @see AbstractXmlParser
	 */
	public function parseExtensionResource(string $sourcePath, string $languageKey, string $fileNamePattern): array
	{
		$fileName = Environment::getLabelsPath() . sprintf($fileNamePattern, $languageKey);

		return $this->_getParsedData($sourcePath, $languageKey, $fileName);
	}

	/**
	 * aken from @see AbstractXmlParser
	 *
	 * @param string $sourcePath Source file path
	 * @param string $languageKey Language key
	 * @return array
	 * @throws \TYPO3\CMS\Core\Localization\Exception\FileNotFoundException
	 */
	public function getParsedData($sourcePath, $languageKey)
	{
		return $this->_getParsedData($sourcePath, $languageKey, null);
	}

	/**
	 * taken from @see AbstractXmlParser
	 *
	 * @param string $sourcePath Source file path
	 * @param string $languageKey Language key
	 * @return array
	 * @throws \TYPO3\CMS\Core\Localization\Exception\FileNotFoundException
	 */
	protected function _getParsedData($sourcePath, $languageKey, ?string $labelsPath)
	{
		$this->sourcePath = $sourcePath;
		$this->languageKey = $languageKey;
		if ($this->languageKey !== 'default') {
			$this->sourcePath = $labelsPath ?? $this->getLocalizedFileName($this->sourcePath, $this->languageKey);
			if (!@is_file($this->sourcePath)) {
				// Global localization is not available, try split localization file
				$this->sourcePath = $this->getLocalizedFileName($sourcePath, $languageKey, true);
			}
			if (!@is_file($this->sourcePath)) {
				throw new FileNotFoundException('Localization file does not exist', 1306332397);
			}
		}
		$LOCAL_LANG = [];
		$LOCAL_LANG[$languageKey] = $this->buildLabels($this->parseFile($this->sourcePath));
		return $LOCAL_LANG;
	}

	/**
	 * taken from @see AbstractXmlParser
	 *
	 * @param string $fileRef Absolute file reference to locallang file
	 * @param string $language Language key
	 * @param bool $sameLocation If TRUE, then locallang localization file name will be returned with same directory as $fileRef
	 * @return string Absolute path to the language file
	 */
	protected function getLocalizedFileName(string $fileRef, string $language, bool $sameLocation = false)
	{
		// If $fileRef is already prefixed with "[language key]" then we should return it as is
		$fileName = PathUtility::basename($fileRef);
		if (str_starts_with($fileName, $language . '.')) {
			return GeneralUtility::getFileAbsFileName($fileRef);
		}

		if ($sameLocation) {
			return GeneralUtility::getFileAbsFileName(str_replace($fileName, $language . '.' . $fileName, $fileRef));
		}

		// Analyze file reference
		if (str_starts_with($fileRef, Environment::getFrameworkBasePath() . '/')) {
			// Is system
			$validatedPrefix = Environment::getFrameworkBasePath() . '/';
		} elseif (str_starts_with($fileRef, Environment::getExtensionsPath() . '/')) {
			// Is local
			$validatedPrefix = Environment::getExtensionsPath() . '/';
		} else {
			$validatedPrefix = '';
		}
		if ($validatedPrefix) {
			// Divide file reference into extension key, directory (if any) and base name:
			[$extensionKey, $file_extPath] = explode('/', substr($fileRef, strlen($validatedPrefix)), 2);
			$temp = GeneralUtility::revExplode('/', $file_extPath, 2);
			if (count($temp) === 1) {
				array_unshift($temp, '');
			}
			// Add empty first-entry if not there.
			[$file_extPath, $file_fileName] = $temp;
			// The filename is prefixed with "[language key]." because it prevents the llxmltranslate tool from detecting it.
			return Environment::getLabelsPath() . '/' . $language . '/' . $extensionKey . '/' . ($file_extPath ? $file_extPath . '/' : '') . $language . '.' . $file_fileName;
		}
		return '';
	}


    /**
     * Flatten a nested array to dot-notation keys and wrap each value
     * in the array structure TYPO3's LOCAL_LANG expects:
     *   ['key' => [0 => ['source' => 'value']]]
     */
    private function buildLabels(array $data): array
    {
        $labels = [];
        foreach ($this->flattenKeys($data) as $key => $value) {
            $labels[$key] = [0 => ['source' => $value, 'target' => $value]];
        }
        return $labels;
    }

    /**
     * Recursively flatten a nested array to dot-notation keys.
     * Flat arrays pass through unchanged.
     *
     * ['login' => ['title' => 'Login']] -> ['login.title' => 'Login']
     */
    private function flattenKeys(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $fullKey = $prefix !== '' ? $prefix . '.' . $key : (string)$key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenKeys($value, $fullKey));
            } else {
                $result[$fullKey] = (string)$value;
            }
        }
        return $result;
    }
}
