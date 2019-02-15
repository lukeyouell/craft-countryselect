<?php

namespace lukeyouell\countryselect\fields;

use Craft;
use craft\base\ElementInterface;

use lukeyouell\countryselect\fields\CountrySelectBaseOptions;

class CountrySelectMultiSelect extends CountrySelectBaseOptions
{
    // Static Methods
    // =========================================================================

    public static function displayName(): string
    {
        return Craft::t('country-select', 'Country Multi-select');
    }

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();
        
        $this->multi = true;
    }

    public function getInputHtml($value, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate('_includes/forms/multiselect', [
            'name' => $this->handle,
            'value' => $value,
            'options' => $this->translatedOptions(),
        ]);
    }

    // Protected Methods
    // =========================================================================

    protected function optionsSettingLabel(): string
    {
        return Craft::t('country-select', 'Country Muti-select Options');
    }
}
