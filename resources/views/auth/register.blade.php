@extends('auth._layouts.index')

@push('cssScript')
@include('auth._layouts._css')
@endpush

@push('title')
Register
@endpush

@section('content')
<div>
    <div><a class="logo text-center" href="index.html"><img class="img-fluid for-light" src="../assets/images/logo/logo-t.png" alt="logo image"></a></div>
    <div class="login-main">
        <form class="theme-form" method="POST" action="{{ route('register') }}">
            @csrf
            <h2 class="text-center">Create your account</h2>
            <p class="text-center">Enter your personal details to create account</p>
            <div class="form-group">
                <label class="col-form-label pt-0">Your Name</label>
                <div class="row g-2">
                    <div class="col-12">
                        <input class="form-control" type="text" required="" placeholder="Name" name="name">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Email Address</label>
                <input class="form-control" type="email" name="email" required="" placeholder="Test@gmail.com">
            </div>
            <div class="form-group">
                <label class="col-form-label">Password</label>
                <div class="form-input position-relative">
                    <input class="form-control" type="password" name="password" required="" placeholder="*********">
                    <div class="show-hide"><span class="show"></span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Confirm Password</label>
                <div class="form-input position-relative">
                    <input class="form-control" type="password" name="password_confirmation" required="" placeholder="*********">
                    <div class="show-hide"><span class="show"></span></div>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox p-0">
                    <input id="checkbox1" type="checkbox">
                    <label class="text-muted" for="checkbox1">Agree with<a class="ms-2" href="javascript:void(0)">Privacy Policy</a></label>
                </div>
                <button class="btn btn-primary btn-block w-100 mt-3" type="submit">Create Account</button>
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
            <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2" href="{{route('login')}}">Sign in</a></p>
        </form>
    </div>
</div>
@endsection

@push('jsScript')
@include('auth._layouts._js')
@endpush