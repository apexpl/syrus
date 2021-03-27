<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_phone implements TagInterface
{

    // Country codes
    private array $country_codes = [
        'Canada' => 1, 
        'United States' => 1, 
        'Russia' => 7, 
        'Kazakhstan' => 7, 
        'Egypt' => 20, 
        'South Africa' => 27, 
        'Greece' => 30, 
        'Netherlands' => 31, 
        'Belgium' => 32, 
        'France' => 33, 
        'Spain' => 34, 
        'Hungary' => 36, 
        'Vatican City' => 39, 
        'Italy' => 39, 
        'Romania' => 40, 
        'Switzerland' => 41, 
        'Austria' => 43, 
        'Isle of Man' => 44, 
        'United Kingdom' => 44, 
        'Denmark' => 45, 
        'Sweden' => 46, 
        'Norway' => 47, 
        'Poland' => 48, 
        'Germany' => 49, 
        'Peru' => 51, 
        'Mexico' => 52, 
        'Cuba' => 53, 
        'Argentina' => 54, 
        'Brazil' => 55, 
        'Chile' => 56, 
        'Colombia' => 57, 
        'Venezuela' => 58, 
        'Malaysia' => 60, 
        'Christmas Island' => 61, 
        'Cocos (Keeling) Islands' => 61, 
        'Australia' => 61, 
        'Indonesia' => 62, 
        'Philippines' => 63, 
        'New Zealand' => 64, 
        'Singapore' => 65, 
        'Thailand' => 66, 
        'Japan' => 81, 
        'South Korea' => 82, 
        'Vietnam' => 84, 
        'China' => 86, 
        'Turkey' => 90, 
        'India' => 91, 
        'Pakistan' => 92, 
        'Afghanistan' => 93, 
        'Sri Lanka' => 94, 
        'Myanmar' => 95, 
        'Iran' => 98, 
        'Morocco' => 212, 
        'Algeria' => 213, 
        'Tunisia' => 216, 
        'Libya' => 218, 
        'Gambia' => 220, 
        'Senegal' => 221, 
        'Mauritania' => 222, 
        'Mali' => 223, 
        'Guinea' => 224, 
        'Ivory Coast' => 225, 
        'Burkina Faso' => 226, 
        'Niger' => 227, 
        'Togo' => 228, 
        'Benin' => 229, 
        'Mauritius' => 230, 
        'Liberia' => 231, 
        'Sierra Leone' => 232, 
        'Ghana' => 233, 
        'Nigeria' => 234, 
        'Chad' => 235, 
        'Central African Republic' => 236, 
        'Cameroon' => 237, 
        'Cape Verde' => 238, 
        'S?o Tom? and Pr?ncipe' => 239, 
        'Equatorial Guinea' => 240, 
        'Gabon' => 241, 
        'Republic of the Congo' => 242, 
        'Bahamas' => 242, 
        'DR Congo' => 243, 
        'Angola' => 244, 
        'Guinea-Bissau' => 245, 
        'Barbados' => 246, 
        'Seychelles' => 248, 
        'Sudan' => 249, 
        'Rwanda' => 250, 
        'Ethiopia' => 251, 
        'Somalia' => 252, 
        'Djibouti' => 253, 
        'Kenya' => 254, 
        'Tanzania' => 255, 
        'Uganda' => 256, 
        'Burundi' => 257, 
        'Mozambique' => 258, 
        'Zambia' => 260, 
        'Madagascar' => 261, 
        'Mayotte' => 262, 
        'Zimbabwe' => 263, 
        'Namibia' => 264, 
        'Anguilla' => 264, 
        'Malawi' => 265, 
        'Lesotho' => 266, 
        'Botswana' => 267, 
        'Antigua and Barbuda' => 268, 
        'Swaziland' => 268, 
        'Comoros' => 269, 
        'British Virgin Islands' => 284, 
        'Saint Helena, Ascension and Tristan da Cunha' => 290, 
        'Eritrea' => 291, 
        'Aruba' => 297, 
        'Faroe Islands' => 298, 
        'Greenland' => 299, 
        'United States Virgin Islands' => 340, 
        'Cayman Islands' => 345, 
        'Gibraltar' => 350, 
        'Portugal' => 351, 
        'Luxembourg' => 352, 
        'Ireland' => 353, 
        'Iceland' => 354, 
        'Albania' => 355, 
        'Malta' => 356, 
        'Cyprus' => 357, 
        'Finland' => 358, 
        'Bulgaria' => 359, 
        'Lithuania' => 370, 
        'Latvia' => 371, 
        'Estonia' => 372, 
        'Moldova' => 373, 
        'Armenia' => 374, 
        'Belarus' => 375, 
        'Andorra' => 376, 
        'Monaco' => 377, 
        'San Marino' => 378, 
        'Ukraine' => 380, 
        'Serbia' => 381, 
        'Kosovo' => 381, 
        'Montenegro' => 382, 
        'Croatia' => 385, 
        'Slovenia' => 386, 
        'Bosnia and Herzegovina' => 387, 
        'Macedonia' => 389, 
        'Czechia' => 420, 
        'Slovakia' => 421, 
        'Liechtenstein' => 423, 
        'Bermuda' => 441, 
        'Grenada' => 473, 
        'Falkland Islands' => 500, 
        'Belize' => 501, 
        'Guatemala' => 502, 
        'El Salvador' => 503, 
        'Honduras' => 504, 
        'Nicaragua' => 505, 
        'Costa Rica' => 506, 
        'Panama' => 507, 
        'Saint Pierre and Miquelon' => 508, 
        'Haiti' => 509, 
        'Saint Barth?lemy' => 590, 
        'Bolivia' => 591, 
        'Guyana' => 592, 
        'Ecuador' => 593, 
        'Paraguay' => 595, 
        'Suriname' => 597, 
        'Uruguay' => 598, 
        'Saint Martin' => 599, 
        'Turks and Caicos Islands' => 649, 
        'Montserrat' => 664, 
        'Timor-Leste' => 670, 
        'Northern Mariana Islands' => 670, 
        'Guam' => 671, 
        'Antarctica' => 672, 
        'Brunei' => 673, 
        'Nauru' => 674, 
        'Papua New Guinea' => 675, 
        'Tonga' => 676, 
        'Solomon Islands' => 677, 
        'Vanuatu' => 678, 
        'Fiji' => 679, 
        'Palau' => 680, 
        'Wallis and Futuna' => 681, 
        'Cook Islands' => 682, 
        'Niue' => 683, 
        'American Samoa' => 684, 
        'Samoa' => 685, 
        'Kiribati' => 686, 
        'New Caledonia' => 687, 
        'Tuvalu' => 688, 
        'French Polynesia' => 689, 
        'Tokelau' => 690, 
        'Micronesia' => 691, 
        'Marshall Islands' => 692, 
        'Saint Lucia' => 758, 
        'Dominica' => 767, 
        'Saint Vincent and the Grenadines' => 784, 
        'Dominican Republic' => 809, 
        'North Korea' => 850, 
        'Hong Kong' => 852, 
        'Macau' => 853, 
        'Cambodia' => 855, 
        'Laos' => 856, 
        'Trinidad and Tobago' => 868, 
        'Saint Kitts and Nevis' => 869, 
        'Pitcairn Islands' => 870, 
        'Jamaica' => 876, 
        'Bangladesh' => 880, 
        'Taiwan' => 886, 
        'Maldives' => 960, 
        'Lebanon' => 961, 
        'Jordan' => 962, 
        'Syria' => 963, 
        'Iraq' => 964, 
        'Kuwait' => 965, 
        'Saudi Arabia' => 966, 
        'Yemen' => 967, 
        'Oman' => 968, 
        'United Arab Emirates' => 971, 
        'Israel' => 972, 
        'Bahrain' => 973, 
        'Qatar' => 974, 
        'Bhutan' => 975, 
        'Mongolia' => 976, 
        'Nepal' => 977, 
        'Tajikistan' => 992, 
        'Turkmenistan' => 993, 
        'Azerbaijan' => 994, 
        'Georgia' => 995, 
        'Kyrgyzstan' => 996, 
        'Uzbekistan' => 998
    ];


    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        list($country, $phone) = [1, ''];
        $value = $e->getAttr('value') ?? '';
        $placeholder = $e->getAttr('placeholder') ?? '';

        // Check value
        if (preg_match("/\+(\d+?)\s(\d+)$/", $value, $match)) { 
            $country = $match[1];
            $phone = $match[2];
        }

        // Create country options
        $country_options = '';
        foreach (array_unique(array_values($this->country_codes)) as $code) { 
            $chk = $code == $country ? 'selected="selected"' : '';
            $country_options .= "<option value=\"$code\" $chk>+ $code</option>";
        }

        // Set replace vars
        $replace = [
            '~placeholder~' => $placeholder != '' ? 'placeholder="' . $placeholder . '"' : '', 
            '~country_code_options~' => $country_options, 
            '~phone~' => $phone
        ];

        // Return
        return strtr($html, $replace);
    }

}


