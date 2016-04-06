<?php

return [
    'app' => [
        'name'          => 'Verify with Call',
        'desc'          => 'Prevents front-end page access for unverified users and prompts to verify with a missed phone call by using Cognalys API.',
        'setting_desc'  => 'Configure Cognalys.com API Credentials',
        'menu_label'    => 'Verify with Call'
    ],

    'activated' => [
        'title' => 'Activate User Verification',
        'desc'  => 'Users will be prompted to verify with mobile phone number if "CheckIfVerified" component added to page'
    ],
    'app_id' => [
        'title' => 'App ID',
        'desc'  => 'Please take a look at the README'
    ],
    'access_token' => [
        'title' => 'Access Token',
        'desc'  => 'Please take a look at the README'
    ],
    'checkcomponent' => [
        'title'     => 'User Verify Check',
        'desc'      => 'Add this component to a page, partial or layout for checking if user verified mobile number for accessing the page',
        'select'    => 'Select Page',

        'redirect'  => [
            'title' => 'Redirect to',
            'desc'  => 'Select page for redirecting if user not verified yet'
        ],
        'redirectlogin'  => [
            'title' => 'Redirect to Login',
            'desc'  => 'Select page for redirecting if there is not logged in user yet'
        ]
    ],
    'verifycomponent' => [
        'title'     => 'Verify Form',
        'desc'      => 'Form for verifying user\'s mobile number. Add this component to a page which you want to show the form',
        'select'    => 'Select Page',

        'unauthorized' => [
            'title' => 'Unauthorized page',
            'desc'  => 'Select page for redirecting if someone try to access this page without verified or logged in'
        ],

        'first_step' => [
            'entermobile'   => 'Please enter your mobile phone number to verify your account.',
            'donotanswer'   => 'You will get a call from our operators. This will not a real phone call, please do not answer it.',
            'placeholder'   => 'Your phone number (eg: +918xxx903xxx)',
            'next'          => 'Next Step'
        ],
        'second_step' => [
            'willcall'      => 'You should get a phone call to your number shortly:',
            'donotanswer'   => 'Please do not answer the call, it will be missed in 2 sec.',
            'lastfive'      => 'Write the last :count digits of the caller phone number.',
            'placeholder'   => 'Last 5 digits of the caller',
            'verify'        => 'Verify'
        ]
    ]
];
