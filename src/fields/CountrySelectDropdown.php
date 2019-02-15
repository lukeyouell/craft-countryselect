<?php

namespace lukeyouell\countryselect\fields;

use Craft;
use craft\base\ElementInterface;

use lukeyouell\countryselect\fields\CountrySelectBaseOptions;

class CountrySelectDropdown extends CountrySelectBaseOptions
{
    // Static Methods
    // =========================================================================

    public static function displayName(): string
    {
        return Craft::t('country-select', 'Country Dropdown');
    }

    // Public Methods
    // =========================================================================

    public function getInputHtml($value, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'name' => $this->handle,
            'value' => $value,
            'options' => $this->translatedOptions(),
        ]);
    }

    // Protected Methods
    // =========================================================================

    protected function optionsSettingLabel(): string
    {
        return Craft::t('country-select', 'Country Dropdown Options');
    }
}
