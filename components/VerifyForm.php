<?php namespace Uxms\Userverify\Components;

use App;
use Lang;
use Auth;
use Session;
use Redirect;
use Carbon\Carbon;
use Cms\Classes\Page as CmsPages;
use Cms\Classes\ComponentBase;
use Uxms\Userverify\Models\Configs;

class VerifyForm extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'          => 'uxms.userverify::lang.verifycomponent.title',
            'description'   => 'uxms.userverify::lang.verifycomponent.desc'
        ];
    }

    public function defineProperties()
    {
        return [
            'unauthorized' => [
                'title'       => 'uxms.userverify::lang.verifycomponent.unauthorized.title',
                'description' => 'uxms.userverify::lang.verifycomponent.unauthorized.desc',
                'type'        => 'dropdown'
            ]
        ];
    }

    /**
     * [getUnauthorizedOptions description]
     * 
     * @return [type] [description]
     */
    public function getUnauthorizedOptions()
    {
        $allCmsPages = CmsPages::all();

        $webpageOpts[0] = '-- '.Lang::get('uxms.userverify::lang.verifycomponent.select').'--';
        foreach ($allCmsPages as $value) {
            $webpageOpts[$value['url']] = $value['url'];
        }

        return $webpageOpts;
    }

    /**
     * Starter method of the component.
     *
     * @return string
     */
    public function onRun()
    {
        $this->addJs('/plugins/uxms/userverify/assets/javascript/steps.js');

        $redirectUnauthorizedTo = addslashes($this->property('unauthorized'));

        // Abort if plugin active and user not logged in
        if (Configs::get('activated') && !Auth::getUser()) {
            if ($redirectUnauthorizedTo)
                return Redirect::to($redirectUnauthorizedTo);

            App::abort(401, 'Unauthorized');
        }

        // If Cognalys API settings not configured, abort..
        if (!Configs::get('app_id') || !Configs::get('access_token')) {
            if ($redirectUnauthorizedTo)
                return Redirect::to($redirectUnauthorizedTo);

            App::abort(401, 'Unauthorized');
        }
    }

    /**
     * [firstStep description]
     * 
     * @return [type] [description]
     */
    public function firstStep($translates)
    {
        return $this->renderPartial('@first_step.htm', ['translate' => $translates]);
    }

    /**
     * [secondStep description]
     * 
     * @return [type] [description]
     */
    public function secondStep($mobile = 0, $translates = [])
    {
        return $this->renderPartial('@second_step.htm', ['mobile' => $mobile, 'translate' => $translates]);
    }

    /**
     * Renders first step of form for component
     * 
     * @return [type] [description]
     */
    public function showForm()
    {
        if (Configs::get('activated') && Auth::getUser()->userverify_dateverified <= 0) {
            $translates = [
                'entermobile'   => Lang::get('uxms.userverify::lang.verifycomponent.first_step.entermobile'),
                'donotanswer'   => Lang::get('uxms.userverify::lang.verifycomponent.first_step.donotanswer'),
                'placeholder'   => Lang::get('uxms.userverify::lang.verifycomponent.first_step.placeholder'),
                'next'          => Lang::get('uxms.userverify::lang.verifycomponent.first_step.next'),
            ];

            return $this->firstStep($translates);
        }
    }

    /**
     * Handles user's phone number and renders second step of form for component
     * 
     * @return [type] [description]
     */
    public function onSubmitPhone()
    {
        $app_id = Configs::get('app_id');
        $access_token = Configs::get('access_token');

        $phoneNumber = intval(post('user-phone'));
        $translates = [
            'willcall'      => Lang::get('uxms.userverify::lang.verifycomponent.second_step.willcall'),
            'donotanswer'   => Lang::get('uxms.userverify::lang.verifycomponent.second_step.donotanswer'),
            'lastfive'      => Lang::get('uxms.userverify::lang.verifycomponent.second_step.lastfive', ['count' => '-5-']),
            'placeholder'   => Lang::get('uxms.userverify::lang.verifycomponent.second_step.placeholder'),
            'verify'        => Lang::get('uxms.userverify::lang.verifycomponent.second_step.verify'),
        ];
        $secondStep = $this->secondStep($phoneNumber, $translates);

        $params = [
            'app_id'        => $app_id,
            'access_token'  => $access_token,
            'mobile'        => $phoneNumber
        ];

        $apiUrl = 'https://www.cognalys.com/api/v1/otp/?'.http_build_query($params);

        $json = file_get_contents($apiUrl);
        $firstResponse = json_decode($json, true);
        Session::put('firstResponse', $firstResponse);

        return [
            'phonenumber'       => $phoneNumber,
            'secondstep'        => $secondStep,
            'first_response'    => $firstResponse
        ];
    }

    /**
     * Queries API and if verified redirects back to ref. page, else prompr re-enter last 5 digit
     * 
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function onSubmitCaller()
    {
        $app_id = Configs::get('app_id');
        $access_token = Configs::get('access_token');
        $firstResponse = Session::get('firstResponse');

        $lastFive = intval(post('lastfive'));
        $otp = $firstResponse['otp_start'].$lastFive;

        $params = [
            'app_id'        => $app_id,
            'access_token'  => $access_token,
            'keymatch'      => $firstResponse['keymatch'],
            'otp'           => $otp
        ];
        $builded = str_ireplace('%2B', '+', http_build_query($params));

        // Query Cognalys API
        $apiConfirmUrl = 'https://www.cognalys.com/api/v1/otp/confirm/?'.$builded;

        $json = file_get_contents($apiConfirmUrl);
        $secondResponse = json_decode($json, true);

        // User verified, redirect back to referer page
        if ($secondResponse['status'] == 'success') {
            $user = Auth::getUser();
            $user->userverify_dateverified = Carbon::now();
            $user->mobile = $secondResponse['mobile'];
            $user->userverify_callerphone = $otp;

            $user->save();

            Session::put('verify_status', $secondResponse['status']);
            return Redirect::to(Session::get('return_page'));
        } else {
            // User not verified, give feedback
            return [
                'response'  => $secondResponse
            ];
        }
    }

}
