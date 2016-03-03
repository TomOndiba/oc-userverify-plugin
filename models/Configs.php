<?php namespace Uxms\Userverify\Models;

use October\Rain\Database\Model;

/**
 * Uxms Userverify Settings Model
 *
 * @package uxms\userverify
 * @author Uxms Devs
 */
class Configs extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'uxms_userverify_configs';
    public $settingsFields = 'fields.yaml';
}
