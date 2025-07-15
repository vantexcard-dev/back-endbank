@include('auth.app')


<div class="login-6">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 col-lg-7 col-md-12 bg-img">
                <div class="info">
                    <div class="waviy">
                        <span style="--i:1">W</span>
                        <span style="--i:2">e</span>
                        <span style="--i:3">l</span>
                        <span style="--i:4">c</span>
                        <span style="--i:5">o</span>
                        <span style="--i:6">m</span>
                        <span style="--i:7">e</span><br>
                        <span class="color-yellow" style="--i:8">t</span>
                        <span class="color-yellow" style="--i:9">o</span>
                        <br>
                        <span style="--i:10">v</span>
                        <span style="--i:11">a</span>
                        <span style="--i:12">n</span>
                        <span style="--i:13">t</span>
                        <span style="--i:13">e</span>
                        <span style="--i:13">x</span>
                    </div>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type</p>
                </div>
                <div class="bg-photo">
                    <img src="assets/img/img-6.png" alt="bg" class="img-fluid">
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-12 bg-color-6">
                <div class="form-section">
                    <div class="logo">

                    </div>
                    <h3>Sign Into Your Account</h3>
                    <div class="login-inner-form">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group clearfix">
                                <label for="first_field" class="form-label">{{ __('Email') }}</label>
                                <div class="form-box">
                                    <input name="email" type="email" class="form-control" id="email"
                                        placeholder="Email Address" aria-label="Email Address">
                                    <i class="flaticon-mail-2"></i>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="second_field" class="form-label">{{ __('Password') }}</label>
                                <div class="form-box">
                                    <input name="password" type="password" class="form-control" autocomplete="off"
                                        id="password"  name="password" placeholder="Password" aria-label="Password">
                                    <i class="flaticon-password"></i>
                                </div>
                            </div>
                            <div class="checkbox form-group clearfix">
                                <div class="form-check float-start">
                                    <input class="form-check-input" name="remember" type="checkbox" id="remember_me">
                                    <label class="form-check-label" for="rememberme">
                                        {{ __('Remember me') }}
                                    </label>
                                </div>
                                <a href="forgot-password-6.html"
                                    class="link-light float-end forgot-password">{{__('web.forgot_password')}}</a>
                            </div>
                            <div class="form-group clearfix mb-0">
                                <button type="submit"
                                    class="btn btn-primary btn-lg btn-theme">{{__('web.login')}}</button>
                            </div>
                        </form>

                    </div>
                    <p class="text-center">Don't have an account?<a href="register-6.html"> Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('auth.footer')