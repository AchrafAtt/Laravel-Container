@component('mail::message')
# Reset Your Password

Hi {{ $first_name }},

You recently requested to reset your password. Click the button below to reset it:

@component('mail::button', ['url' => $resetUrl])
Reset Password
@endcomponent

This password reset link will expire in 60 minutes.

If you did not request a password reset, please ignore this email or contact support if you have concerns.

Thanks,<br>
{{ config('app.name') }}

<small>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: {{ $resetUrl }}</small>
@endcomponent