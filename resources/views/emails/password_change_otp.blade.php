@component('mail::message')
# Password Change Verification

Hi {{ $name }},

Your One-Time Password (OTP) for changing your account password is:

@component('mail::panel')
**{{ $otp }}**
@endcomponent

This OTP is valid for **60 minutes**. If you did not request this, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent