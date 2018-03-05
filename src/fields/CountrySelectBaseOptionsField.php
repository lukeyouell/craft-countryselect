<?php
/**
 * Country Select plugin for Craft CMS 3.x
 *
 * Country select field type.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\countryselect\fields;

use lukeyouell\countryselect\CountrySelect;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\fields\data\OptionData;
use craft\fields\data\MultiOptionsFieldData;
use craft\fields\data\SingleOptionFieldData;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    Luke Youell
 * @package   CountrySelect
 * @since     1.0.0
 */
class CountrySelectBaseOptionsField extends Field
{
    // Properties
    // =========================================================================

    /**
     * @var array|null The available options
     */
    public $options;

    /**
     * @var bool Whether the field should support multiple selections
     */
    protected $multi = false;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->options = $this->translatedOptions();
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        if ($this->multi) {
            // See how much data we could possibly be saving if everything was selected.
            $length = 0;

            if ($this->options) {
                foreach ($this->options as $option) {
                    if (!empty($option['value'])) {
                        // +3 because it will be json encoded. Includes the surrounding quotes and comma.
                        $length += strlen($option['value']) + 3;
                    }
                }
            }

            // Add +2 for the outer brackets and -1 for the last comma.
            return Db::getTextualColumnTypeByContentLength($length + 1);
        }

        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if ($value instanceof MultiOptionsFieldData || $value instanceof SingleOptionFieldData) {
            return $value;
        }

        if (is_string($value)) {
            $value = Json::decodeIfJson($value);
        }

        // Normalize to an array
        $selectedValues = (array) $value;

        if ($this->multi) {
            // Convert the value to a MultiOptionsFieldData object
            $options = [];
            foreach ($selectedValues as $val) {
                $label = $this->optionLabel($val);
                $options[] = new OptionData($label, $val, true);
            }
            $value = new MultiOptionsFieldData($options);
        } else {
            // Convert the value to a SingleOptionFieldData object
            $value = reset($selectedValues) ?: null;
            $label = $this->optionLabel($value);
            $value = new SingleOptionFieldData($label, $value, true);
        }

        $options = [];

        if ($this->options) {
            foreach ($this->options as $option) {
                $selected = in_array($option['value'], $selectedValues, true);
                $options[] = new OptionData($option['label'], $option['value'], $selected);
            }
        }

        $value->setOptions($options);

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }

    // Protected Methods
    // =========================================================================

    /**
     * Returns an option's label by its value.
     *
     * @param string|null $value
     * @return string|null
     */
    protected function optionLabel(string $value = null)
    {
        if ($this->options) {
            foreach ($this->options as $option) {
                if ($option['value'] == $value) {
                    return $option['label'];
                }
            }
        }

        return $value;
    }

    /**
     * @return array
     */
     protected function translatedOptions()
     {
         $countries = [
             ['value' => 'AD', 'label' => Craft::t('country-select', 'Andorra')],
             ['value' => 'AE', 'label' => Craft::t('country-select', 'United Arab Emirates')],
             ['value' => 'AF', 'label' => Craft::t('country-select', 'Afghanistan')],
             ['value' => 'AG', 'label' => Craft::t('country-select', 'Antigua and Barbuda')],
             ['value' => 'AI', 'label' => Craft::t('country-select', 'Anguilla')],
             ['value' => 'AL', 'label' => Craft::t('country-select', 'Albania')],
             ['value' => 'AM', 'label' => Craft::t('country-select', 'Armenia')],
             ['value' => 'AO', 'label' => Craft::t('country-select', 'Angola')],
             ['value' => 'AP', 'label' => Craft::t('country-select', 'Asia/Pacific Region')],
             ['value' => 'AQ', 'label' => Craft::t('country-select', 'Antarctica')],
             ['value' => 'AR', 'label' => Craft::t('country-select', 'Argentina')],
             ['value' => 'AS', 'label' => Craft::t('country-select', 'American Samoa')],
             ['value' => 'AT', 'label' => Craft::t('country-select', 'Austria')],
             ['value' => 'AU', 'label' => Craft::t('country-select', 'Australia')],
             ['value' => 'AW', 'label' => Craft::t('country-select', 'Aruba')],
             ['value' => 'AX', 'label' => Craft::t('country-select', 'Aland Islands')],
             ['value' => 'AZ', 'label' => Craft::t('country-select', 'Azerbaijan')],
             ['value' => 'BA', 'label' => Craft::t('country-select', 'Bosnia and Herzegovina')],
             ['value' => 'BB', 'label' => Craft::t('country-select', 'Barbados')],
             ['value' => 'BD', 'label' => Craft::t('country-select', 'Bangladesh')],
             ['value' => 'BE', 'label' => Craft::t('country-select', 'Belgium')],
             ['value' => 'BF', 'label' => Craft::t('country-select', 'Burkina Faso')],
             ['value' => 'BG', 'label' => Craft::t('country-select', 'Bulgaria')],
             ['value' => 'BH', 'label' => Craft::t('country-select', 'Bahrain')],
             ['value' => 'BI', 'label' => Craft::t('country-select', 'Burundi')],
             ['value' => 'BJ', 'label' => Craft::t('country-select', 'Benin')],
             ['value' => 'BL', 'label' => Craft::t('country-select', 'Saint Bartelemey')],
             ['value' => 'BM', 'label' => Craft::t('country-select', 'Bermuda')],
             ['value' => 'BN', 'label' => Craft::t('country-select', 'Brunei Darussalam')],
             ['value' => 'BO', 'label' => Craft::t('country-select', 'Bolivia')],
             ['value' => 'BQ', 'label' => Craft::t('country-select', 'Bonaire, Saint Eustatius and Saba')],
             ['value' => 'BR', 'label' => Craft::t('country-select', 'Brazil')],
             ['value' => 'BS', 'label' => Craft::t('country-select', 'Bahamas')],
             ['value' => 'BT', 'label' => Craft::t('country-select', 'Bhutan')],
             ['value' => 'BV', 'label' => Craft::t('country-select', 'Bouvet Island')],
             ['value' => 'BW', 'label' => Craft::t('country-select', 'Botswana')],
             ['value' => 'BY', 'label' => Craft::t('country-select', 'Belarus')],
             ['value' => 'BZ', 'label' => Craft::t('country-select', 'Belize')],
             ['value' => 'CA', 'label' => Craft::t('country-select', 'Canada')],
             ['value' => 'CC', 'label' => Craft::t('country-select', 'Cocos (Keeling) Islands')],
             ['value' => 'CD', 'label' => Craft::t('country-select', 'Congo, The Democratic Republic of the')],
             ['value' => 'CF', 'label' => Craft::t('country-select', 'Central African Republic')],
             ['value' => 'CG', 'label' => Craft::t('country-select', 'Congo')],
             ['value' => 'CH', 'label' => Craft::t('country-select', 'Switzerland')],
             ['value' => 'CI', 'label' => Craft::t('country-select', 'Cote d\'Ivoire')],
             ['value' => 'CK', 'label' => Craft::t('country-select', 'Cook Islands')],
             ['value' => 'CL', 'label' => Craft::t('country-select', 'Chile')],
             ['value' => 'CM', 'label' => Craft::t('country-select', 'Cameroon')],
             ['value' => 'CN', 'label' => Craft::t('country-select', 'China')],
             ['value' => 'CO', 'label' => Craft::t('country-select', 'Colombia')],
             ['value' => 'CR', 'label' => Craft::t('country-select', 'Costa Rica')],
             ['value' => 'CU', 'label' => Craft::t('country-select', 'Cuba')],
             ['value' => 'CV', 'label' => Craft::t('country-select', 'Cape Verde')],
             ['value' => 'CW', 'label' => Craft::t('country-select', 'Curacao')],
             ['value' => 'CX', 'label' => Craft::t('country-select', 'Christmas Island')],
             ['value' => 'CY', 'label' => Craft::t('country-select', 'Cyprus')],
             ['value' => 'CZ', 'label' => Craft::t('country-select', 'Czech Republic')],
             ['value' => 'DE', 'label' => Craft::t('country-select', 'Germany')],
             ['value' => 'DJ', 'label' => Craft::t('country-select', 'Djibouti')],
             ['value' => 'DK', 'label' => Craft::t('country-select', 'Denmark')],
             ['value' => 'DM', 'label' => Craft::t('country-select', 'Dominica')],
             ['value' => 'DO', 'label' => Craft::t('country-select', 'Dominican Republic')],
             ['value' => 'DZ', 'label' => Craft::t('country-select', 'Algeria')],
             ['value' => 'EC', 'label' => Craft::t('country-select', 'Ecuador')],
             ['value' => 'EE', 'label' => Craft::t('country-select', 'Estonia')],
             ['value' => 'EG', 'label' => Craft::t('country-select', 'Egypt')],
             ['value' => 'EH', 'label' => Craft::t('country-select', 'Western Sahara')],
             ['value' => 'ER', 'label' => Craft::t('country-select', 'Eritrea')],
             ['value' => 'ES', 'label' => Craft::t('country-select', 'Spain')],
             ['value' => 'ET', 'label' => Craft::t('country-select', 'Ethiopia')],
             ['value' => 'EU', 'label' => Craft::t('country-select', 'Europe')],
             ['value' => 'FI', 'label' => Craft::t('country-select', 'Finland')],
             ['value' => 'FJ', 'label' => Craft::t('country-select', 'Fiji')],
             ['value' => 'FK', 'label' => Craft::t('country-select', 'Falkland Islands (Malvinas)')],
             ['value' => 'FM', 'label' => Craft::t('country-select', 'Micronesia, Federated States of')],
             ['value' => 'FO', 'label' => Craft::t('country-select', 'Faroe Islands')],
             ['value' => 'FR', 'label' => Craft::t('country-select', 'France')],
             ['value' => 'GA', 'label' => Craft::t('country-select', 'Gabon')],
             ['value' => 'GB', 'label' => Craft::t('country-select', 'United Kingdom')],
             ['value' => 'GD', 'label' => Craft::t('country-select', 'Grenada')],
             ['value' => 'GE', 'label' => Craft::t('country-select', 'Georgia')],
             ['value' => 'GF', 'label' => Craft::t('country-select', 'French Guiana')],
             ['value' => 'GG', 'label' => Craft::t('country-select', 'Guernsey')],
             ['value' => 'GH', 'label' => Craft::t('country-select', 'Ghana')],
             ['value' => 'GI', 'label' => Craft::t('country-select', 'Gibraltar')],
             ['value' => 'GL', 'label' => Craft::t('country-select', 'Greenland')],
             ['value' => 'GM', 'label' => Craft::t('country-select', 'Gambia')],
             ['value' => 'GN', 'label' => Craft::t('country-select', 'Guinea')],
             ['value' => 'GP', 'label' => Craft::t('country-select', 'Guadeloupe')],
             ['value' => 'GQ', 'label' => Craft::t('country-select', 'Equatorial Guinea')],
             ['value' => 'GR', 'label' => Craft::t('country-select', 'Greece')],
             ['value' => 'GS', 'label' => Craft::t('country-select', 'South Georgia and the South Sandwich Islands')],
             ['value' => 'GT', 'label' => Craft::t('country-select', 'Guatemala')],
             ['value' => 'GU', 'label' => Craft::t('country-select', 'Guam')],
             ['value' => 'GW', 'label' => Craft::t('country-select', 'Guinea-Bissau')],
             ['value' => 'GY', 'label' => Craft::t('country-select', 'Guyana')],
             ['value' => 'HK', 'label' => Craft::t('country-select', 'Hong Kong')],
             ['value' => 'HM', 'label' => Craft::t('country-select', 'Heard Island and McDonald Islands')],
             ['value' => 'HN', 'label' => Craft::t('country-select', 'Honduras')],
             ['value' => 'HR', 'label' => Craft::t('country-select', 'Croatia')],
             ['value' => 'HT', 'label' => Craft::t('country-select', 'Haiti')],
             ['value' => 'HU', 'label' => Craft::t('country-select', 'Hungary')],
             ['value' => 'ID', 'label' => Craft::t('country-select', 'Indonesia')],
             ['value' => 'IE', 'label' => Craft::t('country-select', 'Ireland')],
             ['value' => 'IL', 'label' => Craft::t('country-select', 'Israel')],
             ['value' => 'IM', 'label' => Craft::t('country-select', 'Isle of Man')],
             ['value' => 'IN', 'label' => Craft::t('country-select', 'India')],
             ['value' => 'IO', 'label' => Craft::t('country-select', 'British Indian Ocean Territory')],
             ['value' => 'IQ', 'label' => Craft::t('country-select', 'Iraq')],
             ['value' => 'IR', 'label' => Craft::t('country-select', 'Iran, Islamic Republic of')],
             ['value' => 'IS', 'label' => Craft::t('country-select', 'Iceland')],
             ['value' => 'IT', 'label' => Craft::t('country-select', 'Italy')],
             ['value' => 'JE', 'label' => Craft::t('country-select', 'Jersey')],
             ['value' => 'JM', 'label' => Craft::t('country-select', 'Jamaica')],
             ['value' => 'JO', 'label' => Craft::t('country-select', 'Jordan')],
             ['value' => 'JP', 'label' => Craft::t('country-select', 'Japan')],
             ['value' => 'KE', 'label' => Craft::t('country-select', 'Kenya')],
             ['value' => 'KG', 'label' => Craft::t('country-select', 'Kyrgyzstan')],
             ['value' => 'KH', 'label' => Craft::t('country-select', 'Cambodia')],
             ['value' => 'KI', 'label' => Craft::t('country-select', 'Kiribati')],
             ['value' => 'KM', 'label' => Craft::t('country-select', 'Comoros')],
             ['value' => 'KN', 'label' => Craft::t('country-select', 'Saint Kitts and Nevis')],
             ['value' => 'KP', 'label' => Craft::t('country-select', 'Korea, Democratic People\'s Republic of')],
             ['value' => 'KR', 'label' => Craft::t('country-select', 'Korea, Republic of')],
             ['value' => 'KW', 'label' => Craft::t('country-select', 'Kuwait')],
             ['value' => 'KY', 'label' => Craft::t('country-select', 'Cayman Islands')],
             ['value' => 'KZ', 'label' => Craft::t('country-select', 'Kazakhstan')],
             ['value' => 'LA', 'label' => Craft::t('country-select', 'Lao People\'s Democratic Republic')],
             ['value' => 'LB', 'label' => Craft::t('country-select', 'Lebanon')],
             ['value' => 'LC', 'label' => Craft::t('country-select', 'Saint Lucia')],
             ['value' => 'LI', 'label' => Craft::t('country-select', 'Liechtenstein')],
             ['value' => 'LK', 'label' => Craft::t('country-select', 'Sri Lanka')],
             ['value' => 'LR', 'label' => Craft::t('country-select', 'Liberia')],
             ['value' => 'LS', 'label' => Craft::t('country-select', 'Lesotho')],
             ['value' => 'LT', 'label' => Craft::t('country-select', 'Lithuania')],
             ['value' => 'LU', 'label' => Craft::t('country-select', 'Luxembourg')],
             ['value' => 'LV', 'label' => Craft::t('country-select', 'Latvia')],
             ['value' => 'LY', 'label' => Craft::t('country-select', 'Libyan Arab Jamahiriya')],
             ['value' => 'MA', 'label' => Craft::t('country-select', 'Morocco')],
             ['value' => 'MC', 'label' => Craft::t('country-select', 'Monaco')],
             ['value' => 'MD', 'label' => Craft::t('country-select', 'Moldova, Republic of')],
             ['value' => 'ME', 'label' => Craft::t('country-select', 'Montenegro')],
             ['value' => 'MF', 'label' => Craft::t('country-select', 'Saint Martin')],
             ['value' => 'MG', 'label' => Craft::t('country-select', 'Madagascar')],
             ['value' => 'MH', 'label' => Craft::t('country-select', 'Marshall Islands')],
             ['value' => 'MK', 'label' => Craft::t('country-select', 'Macedonia')],
             ['value' => 'ML', 'label' => Craft::t('country-select', 'Mali')],
             ['value' => 'MM', 'label' => Craft::t('country-select', 'Myanmar')],
             ['value' => 'MN', 'label' => Craft::t('country-select', 'Mongolia')],
             ['value' => 'MO', 'label' => Craft::t('country-select', 'Macao')],
             ['value' => 'MP', 'label' => Craft::t('country-select', 'Northern Mariana Islands')],
             ['value' => 'MQ', 'label' => Craft::t('country-select', 'Martinique')],
             ['value' => 'MR', 'label' => Craft::t('country-select', 'Mauritania')],
             ['value' => 'MS', 'label' => Craft::t('country-select', 'Montserrat')],
             ['value' => 'MT', 'label' => Craft::t('country-select', 'Malta')],
             ['value' => 'MU', 'label' => Craft::t('country-select', 'Mauritius')],
             ['value' => 'MV', 'label' => Craft::t('country-select', 'Maldives')],
             ['value' => 'MW', 'label' => Craft::t('country-select', 'Malawi')],
             ['value' => 'MX', 'label' => Craft::t('country-select', 'Mexico')],
             ['value' => 'MY', 'label' => Craft::t('country-select', 'Malaysia')],
             ['value' => 'MZ', 'label' => Craft::t('country-select', 'Mozambique')],
             ['value' => 'NA', 'label' => Craft::t('country-select', 'Namibia')],
             ['value' => 'NC', 'label' => Craft::t('country-select', 'New Caledonia')],
             ['value' => 'NE', 'label' => Craft::t('country-select', 'Niger')],
             ['value' => 'NF', 'label' => Craft::t('country-select', 'Norfolk Island')],
             ['value' => 'NG', 'label' => Craft::t('country-select', 'Nigeria')],
             ['value' => 'NI', 'label' => Craft::t('country-select', 'Nicaragua')],
             ['value' => 'NL', 'label' => Craft::t('country-select', 'Netherlands')],
             ['value' => 'NO', 'label' => Craft::t('country-select', 'Norway')],
             ['value' => 'NP', 'label' => Craft::t('country-select', 'Nepal')],
             ['value' => 'NR', 'label' => Craft::t('country-select', 'Nauru')],
             ['value' => 'NU', 'label' => Craft::t('country-select', 'Niue')],
             ['value' => 'NZ', 'label' => Craft::t('country-select', 'New Zealand')],
             ['value' => 'OM', 'label' => Craft::t('country-select', 'Oman')],
             ['value' => 'PA', 'label' => Craft::t('country-select', 'Panama')],
             ['value' => 'PE', 'label' => Craft::t('country-select', 'Peru')],
             ['value' => 'PF', 'label' => Craft::t('country-select', 'French Polynesia')],
             ['value' => 'PG', 'label' => Craft::t('country-select', 'Papua New Guinea')],
             ['value' => 'PH', 'label' => Craft::t('country-select', 'Philippines')],
             ['value' => 'PK', 'label' => Craft::t('country-select', 'Pakistan')],
             ['value' => 'PL', 'label' => Craft::t('country-select', 'Poland')],
             ['value' => 'PM', 'label' => Craft::t('country-select', 'Saint Pierre and Miquelon')],
             ['value' => 'PN', 'label' => Craft::t('country-select', 'Pitcairn')],
             ['value' => 'PR', 'label' => Craft::t('country-select', 'Puerto Rico')],
             ['value' => 'PS', 'label' => Craft::t('country-select', 'Palestinian Territory')],
             ['value' => 'PT', 'label' => Craft::t('country-select', 'Portugal')],
             ['value' => 'PW', 'label' => Craft::t('country-select', 'Palau')],
             ['value' => 'PY', 'label' => Craft::t('country-select', 'Paraguay')],
             ['value' => 'QA', 'label' => Craft::t('country-select', 'Qatar')],
             ['value' => 'RE', 'label' => Craft::t('country-select', 'Reunion')],
             ['value' => 'RO', 'label' => Craft::t('country-select', 'Romania')],
             ['value' => 'RS', 'label' => Craft::t('country-select', 'Serbia')],
             ['value' => 'RU', 'label' => Craft::t('country-select', 'Russian Federation')],
             ['value' => 'RW', 'label' => Craft::t('country-select', 'Rwanda')],
             ['value' => 'SA', 'label' => Craft::t('country-select', 'Saudi Arabia')],
             ['value' => 'SB', 'label' => Craft::t('country-select', 'Solomon Islands')],
             ['value' => 'SC', 'label' => Craft::t('country-select', 'Seychelles')],
             ['value' => 'SD', 'label' => Craft::t('country-select', 'Sudan')],
             ['value' => 'SE', 'label' => Craft::t('country-select', 'Sweden')],
             ['value' => 'SG', 'label' => Craft::t('country-select', 'Singapore')],
             ['value' => 'SH', 'label' => Craft::t('country-select', 'Saint Helena')],
             ['value' => 'SI', 'label' => Craft::t('country-select', 'Slovenia')],
             ['value' => 'SJ', 'label' => Craft::t('country-select', 'Svalbard and Jan Mayen')],
             ['value' => 'SK', 'label' => Craft::t('country-select', 'Slovakia')],
             ['value' => 'SL', 'label' => Craft::t('country-select', 'Sierra Leone')],
             ['value' => 'SM', 'label' => Craft::t('country-select', 'San Marino')],
             ['value' => 'SN', 'label' => Craft::t('country-select', 'Senegal')],
             ['value' => 'SO', 'label' => Craft::t('country-select', 'Somalia')],
             ['value' => 'SR', 'label' => Craft::t('country-select', 'Suriname')],
             ['value' => 'SS', 'label' => Craft::t('country-select', 'South Sudan')],
             ['value' => 'ST', 'label' => Craft::t('country-select', 'Sao Tome and Principe')],
             ['value' => 'SV', 'label' => Craft::t('country-select', 'El Salvador')],
             ['value' => 'SX', 'label' => Craft::t('country-select', 'Sint Maarten')],
             ['value' => 'SY', 'label' => Craft::t('country-select', 'Syrian Arab Republic')],
             ['value' => 'SZ', 'label' => Craft::t('country-select', 'Swaziland')],
             ['value' => 'TC', 'label' => Craft::t('country-select', 'Turks and Caicos Islands')],
             ['value' => 'TD', 'label' => Craft::t('country-select', 'Chad')],
             ['value' => 'TF', 'label' => Craft::t('country-select', 'French Southern Territories')],
             ['value' => 'TG', 'label' => Craft::t('country-select', 'Togo')],
             ['value' => 'TH', 'label' => Craft::t('country-select', 'Thailand')],
             ['value' => 'TJ', 'label' => Craft::t('country-select', 'Tajikistan')],
             ['value' => 'TK', 'label' => Craft::t('country-select', 'Tokelau')],
             ['value' => 'TL', 'label' => Craft::t('country-select', 'Timor-Leste')],
             ['value' => 'TM', 'label' => Craft::t('country-select', 'Turkmenistan')],
             ['value' => 'TN', 'label' => Craft::t('country-select', 'Tunisia')],
             ['value' => 'TO', 'label' => Craft::t('country-select', 'Tonga')],
             ['value' => 'TR', 'label' => Craft::t('country-select', 'Turkey')],
             ['value' => 'TT', 'label' => Craft::t('country-select', 'Trinidad and Tobago')],
             ['value' => 'TV', 'label' => Craft::t('country-select', 'Tuvalu')],
             ['value' => 'TW', 'label' => Craft::t('country-select', 'Taiwan')],
             ['value' => 'TZ', 'label' => Craft::t('country-select', 'Tanzania, United Republic of')],
             ['value' => 'UA', 'label' => Craft::t('country-select', 'Ukraine')],
             ['value' => 'UG', 'label' => Craft::t('country-select', 'Uganda')],
             ['value' => 'UM', 'label' => Craft::t('country-select', 'United States Minor Outlying Islands')],
             ['value' => 'US', 'label' => Craft::t('country-select', 'United States')],
             ['value' => 'UY', 'label' => Craft::t('country-select', 'Uruguay')],
             ['value' => 'UZ', 'label' => Craft::t('country-select', 'Uzbekistan')],
             ['value' => 'VA', 'label' => Craft::t('country-select', 'Holy See (Vatican City State)')],
             ['value' => 'VC', 'label' => Craft::t('country-select', 'Saint Vincent and the Grenadines')],
             ['value' => 'VE', 'label' => Craft::t('country-select', 'Venezuela')],
             ['value' => 'VG', 'label' => Craft::t('country-select', 'Virgin Islands, British')],
             ['value' => 'VI', 'label' => Craft::t('country-select', 'Virgin Islands, U.S.')],
             ['value' => 'VN', 'label' => Craft::t('country-select', 'Vietnam')],
             ['value' => 'VU', 'label' => Craft::t('country-select', 'Vanuatu')],
             ['value' => 'WF', 'label' => Craft::t('country-select', 'Wallis and Futuna')],
             ['value' => 'WS', 'label' => Craft::t('country-select', 'Samoa')],
             ['value' => 'YE', 'label' => Craft::t('country-select', 'Yemen')],
             ['value' => 'YT', 'label' => Craft::t('country-select', 'Mayotte')],
             ['value' => 'ZA', 'label' => Craft::t('country-select', 'South Africa')],
             ['value' => 'ZM', 'label' => Craft::t('country-select', 'Zambia')],
             ['value' => 'ZW', 'label' => Craft::t('country-select', 'Zimbabwe')],
         ];

         // Sort countries by label
         usort($countries, function($a, $b) {
             return strcasecmp($a['label'], $b['label']);
         });

         return $countries;
     }
}
