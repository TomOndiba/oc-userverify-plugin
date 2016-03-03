# Verify with Call
Prevents page access for unverified users and prompts to verify with a missed phone call by using Cognalys API.

## Why prefer Cognalys and not SMS based services?
Sending SMS for verification is more expensive than a basic phone call. This is a good service for being simple usage both users and site owners.

## What should you do to configure the plugin?
- Go to [Cognalys Webpage](http://cognalys.com) and register
- Create new **OTP Applications** under Dashboard
- Click **Configuration** button of Application, copy **App ID** and **Access Token**
- Go to settings page on your website. Paste **App ID** and **Access Token** to fields
- Switch on **Activate User Verification**

> Take a look at the documentation page for installation of verification process on pages

## Need Help?
Please open issue on [Plugin Issues](https://github.com/uxmsdevs/oc-userverify-plugin/issues) page if you have any issues or need help.

## Dependencies
* [RainLab.User](https://octobercms.com/plugin/rainlab-user)
* [RainLab.UserPlus](https://octobercms.com/plugin/rainlab-userplus)
