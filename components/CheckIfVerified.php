<?php namespace Uxms\Userverify\Components;

use URL;
use Lang;
use Auth;
use Session;
use Redirect;
use Cms\Classes\Page as CmsPages;
use Cms\Classes\ComponentBase;
use Uxms\Userverify\Models\Configs;

class CheckIfVerified extends ComponentBase
{
    private $user;
    private $redirectTo;
    private $redirectToLogin;

    public function componentDetails()
    {
        return [
            'name'          => 'uxms.userverify::lang.checkcomponent.title',
            'description'   => 'uxms.userverify::lang.checkcomponent.desc'
        ];
    }

    public function defineProperties()
    {
        return [
            'redirect' => [
                'title'       => 'uxms.userverify::lang.checkcomponent.redirect.title',
                'description' => 'uxms.userverify::lang.checkcomponent.redirect.desc',
                'type'        => 'dropdown'
            ],
            'redirectlogin' => [
                'title'       => 'uxms.userverify::lang.checkcomponent.redirectlogin.title',
                'description' => 'uxms.userverify::lang.checkcomponent.redirectlogin.desc',
                'type'        => 'dropdown'
            ]
        ];
    }

    public function getRedirectOptions()
    {
        $allCmsPages = CmsPages::all();

        $webpageOpts[0] = '-- '.Lang::get('uxms.userverify::lang.checkcomponent.select').'--';
        foreach ($allCmsPages as $value) {
            $webpageOpts[$value['url']] = $value['url'];
        }

        return $webpageOpts;
    }

    public function getRedirectloginOptions()
    {
        return $this->getRedirectOptions();
    }

    /**
     * Starter method of the component.
     *
     * @return string
     */
    public function onRun()
    {
        $this->user = Auth::getUser();
        $this->redirectTo = addslashes($this->property('redirect'));
        $this->redirectToLogin = addslashes($this->property('redirectlogin'));

        // TODO: Add response information
        if (Session::get('verify_status'))
            Session::forget('verify_status');

        if (!$this->user) {
            $this->redirectToLoginPage();
            $this->redirectToLoginPageWithJS();
        }

        if (Configs::get('activated') && $this->user)
            return $this->isUserVerified();
    }

    /**
     * [isUserVerified description]
     * 
     * @return boolean [description]
     */
    public function isUserVerified()
    {
        if ($this->user->userverify_dateverified <= 0) {
            $this->redirectToFormPage();
            $this->redirectToFormPageJS();
        }
    }

    /**
     * [redirectToFormPage description]
     * 
     * @return [type] [description]
     */
    public function redirectToFormPage()
    {
        Session::put('return_page', $this->page->url);
        return Redirect::to($this->redirectTo);
    }

    /**
     * [redirectToFormPageJS description]
     * 
     * @return [type] [description]
     */
    public function redirectToFormPageJS()
    {
        Session::put('return_page', $this->page->url);

        $formPageUrl = URL::to($this->redirectTo);
        echo '<script>window.location = "'.$formPageUrl.'";</script>';
    }

    /**
     * [redirectToLoginPage description]
     * 
     * @return [type] [description]
     */
    public function redirectToLoginPage()
    {
        Session::put('return_page', $this->page->url);
        return Redirect::to($this->redirectToLogin);
    }

    /**
     * [redirectToLoginPageWithJS description]
     * 
     * @return [type] [description]
     */
    public function redirectToLoginPageWithJS()
    {
        Session::put('return_page', $this->page->url);

        $loginPageUrl = URL::to($this->redirectToLogin);
        echo '<script>window.location = "'.$loginPageUrl.'";</script>';
    }

}
