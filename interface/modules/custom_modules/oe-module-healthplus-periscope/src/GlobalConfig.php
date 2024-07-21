<?php

/**
 * Bootstrap custom module skeleton.  This file is an example custom module that can be used
 * to create modules that can be utilized inside the OpenEMR system.  It is NOT intended for
 * production and is intended to serve as the barebone requirements you need to get started
 * writing modules that can be installed and used in OpenEMR.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 *
 * @author    Stephen Nielson <stephen@nielson.org>
 * @copyright Copyright (c) 2021 Stephen Nielson <stephen@nielson.org>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\Modules\HealthplusPeriscope;

use OpenEMR\Common\Crypto\CryptoGen;
use OpenEMR\Services\Globals\GlobalSetting;

class GlobalConfig
{
    const PERISCOPE_CLIENT_ID = 'oe_healthplus_periscope_periscope_client_id';
    const PERISCOPE_CLIENT_SECRET = 'oe_healthplus_periscope_periscope_client_secret';
    const BASE_URL = 'oe_healthplus_periscope_base_url';
    const LOGIN_ENDPOINT = 'oe_healthplus_periscope_login_endpoint';
    const PREDICTION_ENDPOINT = 'oe_healthplus_periscope_prediction_endpoint';
    const CONFIG_OVERRIDE_TEMPLATES = 'oe_healthplus_periscope_config_override_templates';
    const CONFIG_ENABLE_MENU = 'oe_healthplus_periscope_config_enable_menu';
    const CONFIG_ENABLE_FHIR_API = 'oe_healthplus_periscope_config_enable_fhir_api';

    

    private $globalsArray;

    /**
     * @var CryptoGen
     */
    private $cryptoGen;

    public function __construct(array $globalsArray)
    {
        $this->globalsArray = $globalsArray;
        $this->cryptoGen = new CryptoGen();
    }

    /**
     * Returns true if all of the settings have been configured.  Otherwise it returns false.
     * @return bool
     */
    public function isConfigured()
    {
        $keys = [self::PERISCOPE_CLIENT_ID, self::PERISCOPE_CLIENT_SECRET, self::BASE_URL];
        foreach ($keys as $key) {
            $value = $this->getGlobalSetting($key);
            if (empty($value)) {
                return false;
            }
        }
        return true;
    }

    public function getClientId()
    {
        return $this->getGlobalSetting(self::PERISCOPE_CLIENT_ID);
    }

    /**
     * Returns our decrypted value if we have one, or false if the value could not be decrypted or is empty.
     * @return bool|string
     */
    public function getEncryptedOption()
    {
        $encryptedValue = $this->getGlobalSetting(self::CONFIG_OPTION_ENCRYPTED);
        return $this->cryptoGen->decryptStandard($encryptedValue);
    }

    public function getGlobalSetting($settingKey)
    {
        return $this->globalsArray[$settingKey] ?? null;
    }

    public function getGlobalSettingSectionConfiguration()
    {
        $settings = [
            self::PERISCOPE_CLIENT_ID => [
                'title' => 'Client ID'
                ,'description' => 'Client ID of PERISCOPE EHR service account'
                ,'type' => GlobalSetting::DATA_TYPE_TEXT
                ,'default' => ''
            ]            
            ,self::PERISCOPE_CLIENT_SECRET => [
                'title' => 'Client secret'
                ,'description' => 'Client secret for PERISCOPE EHR service account'
                ,'type' => GlobalSetting::DATA_TYPE_ENCRYPTED
                ,'default' => ''
            ]
            ,self::BASE_URL => [
                'title' => 'Base URL'
                ,'description' => 'Base URL to get predictions from'
                ,'type' => GlobalSetting::DATA_TYPE_TEXT
                ,'default' => 'https://staging-healthplus.saasnow.com/'
            ]               
            ,self::LOGIN_ENDPOINT => [
                'title' => 'Login URL'
                ,'description' => 'URL to get access token from'
                ,'type' => GlobalSetting::DATA_TYPE_TEXT
                ,'default' => 'SASLogon/oauth/token'
            ]
            ,self::PREDICTION_ENDPOINT => [
                'title' => 'Login URL'
                ,'description' => 'URL to get access token from'
                ,'type' => GlobalSetting::DATA_TYPE_TEXT
                ,'default' => '/SASJobExecution/?_file=/results/samd01/                               '
            ]            
            ,self::CONFIG_OVERRIDE_TEMPLATES => [
                'title' => 'Skeleton Module enable overriding twig files'
                ,'description' => 'Shows example of overriding a twig file'
                ,'type' => GlobalSetting::DATA_TYPE_BOOL
                ,'default' => ''
            ]
            ,self::CONFIG_ENABLE_MENU => [
                'title' => 'Skeleton Module add module menu item'
                ,'description' => 'Shows example of adding a menu item to the system (requires logging out and logging in again)'
                ,'type' => GlobalSetting::DATA_TYPE_BOOL
                ,'default' => ''
            ]
            ,self::CONFIG_ENABLE_FHIR_API => [
                'title' => 'Skeleton Module Enable FHIR API Extension example.'
                ,'description' => 'Shows example of extending the FHIR api with the skeleton module.'
                ,'type' => GlobalSetting::DATA_TYPE_BOOL
                ,'default' => ''
            ]
        ];
        return $settings;
    }
}
