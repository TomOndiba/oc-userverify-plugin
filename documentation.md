## Using Components for Verifying
There are 2 components you should use:

- **CheckIfVerified** component is using for verification purposes. Add **CheckIfVerified** component to a page if you want to that page needs verication before access. Also change **redirect** property of component to a cms page for redirecting to the verify form page.
- **VerifyForm** component is using for showing the form to user which verification process takes place. Create a blank page or whichever you want and add component to that page, than put **{{ VerifyForm.showForm|raw }}** to where you want to show form on page. Also change **unauthorized** property to a cms page for redirecting if visitor not yet registered user. (Probably redirect to registration page is suitable)

That's all. Now, all logged-in users who is not verified mobile phone will be prompt to verify on the redirected page. When user verified, automatically returned back to source page where user left off.

## How Verification Process Takes Place for Users?
When user try to access a page which has "CheckIfVerified" component, if you activated verification and user is not verified, system redirects user automatically to your defined webpage which has "VerifyForm" component.

User should enter mobile number on that page and system will call the user. User should write **Last 5 digit** of caller number and system automatically combine the number to verify the user.

## Logs About Verified Users
System also keeps these datas for you in users table:

- User's mobile phone number (**mobile** in **users** table)
- Verified date (**userverify_dateverified** in **users** table)
- Caller phone number (operator number) (**userverify_callerphone** in **users** table)

## Working Logic
For more information:

[Cognalys API Page](https://cognalys.freshdesk.com/support/solutions/articles/5000048868-how-to-verify-using-otp-api-)

## Request URL Before Phone Call
https://www.cognalys.com/api/v1/otp/?app_id=**YOUR_OTP_APP_ID**&access_token=**YOUR_OTP_ACCESS_TOKEN**&mobile=**MOBILE**

    (MOBILE = + country code + mobile number)
    eg: +918xxx903xxx
    eg: International Code + Country Code + Area Code + Subscriber Number

### Confirming the Number Verification
https://www.cognalys.com/api/v1/otp/confirm/?app_id=**YOUR_OTP_APP_ID**&access_token=**YOUR_OTP_ACCESS_TOKEN**&keymatch=**KEYMATCH**&otp=**OTP**

    (KEYMATCH = the keymatch value you got from first api just before)
    (OTP = 10 digit missed call OTP number, which user should write)
