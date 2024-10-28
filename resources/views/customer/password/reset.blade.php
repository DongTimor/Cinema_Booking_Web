@component('mail::message')
<h2>Xin chào!</h2>
<p>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.<br>
    Hãy chọn <b>Đặt lại mật khẩu</b> để tiếp tục.</p>
<div class="container">
    <button class="button-success"><a href="{{ url('/customers/reset-password', $token) }}">Đặt lại mật khẩu</a></button>
</div>
<p>Liên kết này sẽ hết hạn sau 60 phút.<br>Nếu bạn không yêu cầu đặt lại mật khẩu thì có thể bỏ qua thông báo này.<br>Xin cảm ơn,<br>{{ config('app.name') }}</p>
@endcomponent
