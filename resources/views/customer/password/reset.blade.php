@component('mail::message')
<h2>Xin chào!</h2>
<p>You received this email because we received your request to reset your account password.<br>
    Please select <b>Reset Password</b> to continue.</p>
<div class="container">
    <button class="btn btn-outline-success"><a href="{{ url('/customers/reset-password', $token) }}">Reset Password</a></button>
</div>
<p>This link will expire in 60 minutes.<br>If you do not request a password reset, you can ignore this message.<br>Thanks,<br>{{ config('app.name') }}</p>
@endcomponent
