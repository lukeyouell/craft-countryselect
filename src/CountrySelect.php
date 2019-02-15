<?php

namespace lukeyouell\countryselect;

use Craft;
use craft\base\Plugin;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use lukeyouell\countryselect\fields\CountrySelectCheckboxes;
use lukeyouell\countryselect\fields\CountrySelectDropdown;
use lukeyouell\countryselect\fields\CountrySelectMultiSelect;
use lukeyouell\countryselect\fields\CountrySelectRadioButtons;

use yii\base\Event;

class CountrySelect extends Plugin
{
    // Static Properties
    // =========================================================================

    public static $plugin;

    // Public Properties
    // =========================================================================

    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = CountrySelectCheckboxes::class;
                $event->types[] = CountrySelectDropdown::class;
                $event->types[] = CountrySelectMultiSelect::class;
                $event->types[] = CountrySelectRadioButtons::class;
            }
        );
    }
}
