@extends('auth._layouts.index')

@push('cssScript')
@include('auth._layouts._css')
@endpush

@push('title')
Login
@endpush

@section('content')
<div>
    <div><a class="logo" href="index.html"><img class="img-fluid for-light" style="height:100px; width:100p" src="{{ url('assets/logo/logo-t.png') }}" alt="logo image"></a></div>
    <div class="login-main">
        <form class="theme-form" method="POST" action="{{ route('login') }}">
            @csrf
            <h2 class="text-center">Sign in to account</h2>
            <p class="text-center">Enter your email & password to login</p>
            <div class="form-group">
                <label class="col-form-label">Email Address</label>
                <input class="form-control" type="email" required="" name="email">
            </div>
            <div class="form-group">
                <label class="col-form-label">Password</label>
                <div class="form-input position-relative">
                    <input class="form-control" type="password" name="password" required="" placeholder="*********">
                    <div class="show-hide"><span class="show"> </span></div>
                </div>
            </div>
            <div class="form-group mb-0">
                <div class="checkbox p-0">
                    <input id="checkbox1" type="checkbox">
                    <label class="text-muted" for="checkbox1">Remember password</label>
                    <!-- </div><a class="link" href="forget-password.html">Forgot password?</a> -->
                    <div class="text-end mt-3">
                        <button class="btn btn-primary btn-block w-100" type="submit">Sign in </button>
                    </div>
                </div>
                <!-- <div class="login-social-title">
                <h3>Or Sign in with </h3>
            </div>
            <div class="form-group">
                <ul class="login-social">
                    <li><a href="https://www.facebook.com" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="https://www.linkedin.com" target="_blank"><i class="fa fa-linkedin"> </i></a></li>
                    <li><a href="https://www.twitter.com" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="https://www.instagram.com" target="_blank"><i class="fa fa-instagram"></i></a></li>
                </ul>
            </div> -->
                <!-- <p class="mt-4 mb-0 text-center">Don't have account?<a class="ms-2" href="{{route('register')}}">Create Account</a></p> -->
        </form>
    </div>
</div>

@endsection

@push('jsScript')
@include('auth._layouts._js')
<script>
    $(document).ready(function() {
        $(".show-hide").on('click', function() {
            var $pwd = $(".form-input input");
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
            } else {
                $pwd.attr('type', 'password');
            }
        });

        // Remember me
        if (localStorage.chkbx && localStorage.chkbx != '') {
            $('#checkbox1').attr('checked', 'checked');
            $('#email').val(localStorage.usrname);
            $('#password').val(localStorage.pass);
        } else {
            $('#checkbox1').removeAttr('checked');
            $('#email').val('');
            $('#password').val('');
        }

        $('#checkbox1').click(function() {

            if ($('#checkbox1').is(':checked')) {
                // save username and password
                localStorage.usrname = $('#email').val();
                localStorage.pass = $('#password').val();
                localStorage.chkbx = $('#checkbox1').val();
            } else {
                localStorage.usrname = '';
                localStorage.pass = '';
                localStorage.chkbx = '';
            }
        });
    });
</script>
@endpush