<?php namespace Uxms\Userverify;

use Event;
use Backend;
use Redirect;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\User;
use Uxms\Userverify\Models\Configs;

class Plugin extends PluginBase
{
    public $require = ['RainLab.User', 'RainLab.UserPlus'];

    public function pluginDetails()
    {
        return [
            'name'        => 'uxms.userverify::lang.app.name',
            'description' => 'uxms.userverify::lang.app.desc',
            'author'      => 'uXMs Devs',
            'icon'        => 'icon-check-square-o',
            'homepage'    => 'https://uxms.net'
        ];
    }

    public function registerPermissions()
    {
        return [
            'uxms.userverify.settings' => ['tab' => 'Access API Settings', 'label' => 'Verify with Call'],
        ];
    }

    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'uxms.userverify::lang.app.name',
                'description' => 'uxms.userverify::lang.app.setting_desc',
                'icon'        => 'icon-check-square-o',
                'class'       => 'Uxms\Userverify\Models\Configs',
                'order'       => 998,
                'category'    => SettingsManager::CATEGORY_USERS,
                'permissions' => ['uxms.userverify.settings']
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            'Uxms\Userverify\Components\CheckIfVerified'    => 'CheckIfVerified',
            'Uxms\Userverify\Components\VerifyForm'         => 'VerifyForm'
        ];
    }

    /**
     * The boot() method is called right before a request is routed
     */
    public function boot()
    {
        if (Configs::get('activated') == null) {
            Configs::set('activated', '0');
        }

        User::extend(function($model) {
            $model->dates = ['userverify_dateverified'];
        });

        Event::listen('backend.form.extendFields', function($widget) 
        {
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) return;
            if (!$widget->model instanceof \RainLab\User\Models\User) return;

            $widget->addFields([
                'userverify_mobile' => [
                    'label' => 'Mobile Phone',
                    'tab'   => 'Verification',
                    'type'  => 'text'
                ]
            ], 'primary');

            $widget->addFields([
                'userverify_dateverified' => [
                    'label' => 'Verified Date',
                    'tab'   => 'Verification',
                    'type'  => 'datepicker'
                ]
            ], 'primary');

            $widget->addFields([
                'userverify_callerphone' => [
                    'label' => 'Caller Phone (System)',
                    'tab'   => 'Verification',
                    'type'  => 'text'
                ]
            ], 'primary');
        });

    }

}
