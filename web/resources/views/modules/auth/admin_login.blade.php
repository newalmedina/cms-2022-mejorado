@extends('admin.layouts.simple')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('/assets/admin/vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('home') }}">
                <div>{{ config('app.name') }}</div>
            </a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('auth/lang.sign_in') }}</p>

                @include('admin.includes.errors')
                @include('admin.includes.success')

                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <div class="input-group mb-3 @error('login') has-error @enderror">
                        <input type="text" class="form-control" placeholder="{{ __("auth/lang.username_or_email") }}"
                            id="login" name="login" value="{{ old('login') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    @error('username')
                    <div class="text-danger mt-0 mb-4">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                    <div class="input-group mb-3 @error('password') has-error @enderror">
                        <input type="password" class="form-control" placeholder="{{ __("auth/lang.password") }}"
                            id="password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                    <div class="text-danger mt-0 mb-4">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">
                                    {{ __("auth/lang.recordarme") }}
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit"
                                class="btn btn-primary btn-block">{{ __('auth/lang.login_access') }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>



                @if (Route::has('admin.password.request'))
                <p class="mb-1">
                    <a href="{{ route('admin.password.request') }}">{{ __('auth/lang.recordar_contrasena') }}</a>
                </p>
                @endif
                @if (Route::has('admin.register'))
                <p class="mb-0">
                    <a href="{{ route('admin.register') }}"
                        class="text-center">{{ __('auth/lang.registrarse_en_la_web') }}</a>
                </p>
                @endif
            </div>

            <!-- /.login-card-body -->
        </div>

        <div class="login-footer">
            <made-with-love version="{{ config('general.app_version') }}"></made-with-love>
        </div>

    </div>
    <!-- /.login-box -->



@stop

@section('foot_page')

    <script src="{{ asset('/assets/admin/vendor/vue/vue.global.prod.js') }}"></script>
    <script src="{{ asset('/assets/admin/vendor/moment/moment.min.js') }}"></script>
    <script>
        // Create a Vue application
        const app = Vue.createApp({});

        // Define a new global component called button-counter
        app.component('made-with-love', {
            props: ['version'],
            data() {
                return {
                    classColor: '',
                    activeLove: false
                }
            },
            methods: {
                changeColor: function() {
                    var v = this;

                    v.activeLove = !v.activeLove;

                    setTimeout(function recursiveColor() {
                        if (v.classColor == '') {
                            v.classColor = 'alternate';
                        } else {
                            v.classColor = '';
                        }
                        if (v.activeLove) {
                            setTimeout(recursiveColor, 1000);
                        }

                    }, 1000);
                }
            },
            computed: {
                mounted() {
                    console.log('Mede with Love Mounted');
                }
            },
            template: `
                <div>
                    <a href="#" @click="changeColor"><b>Versión: </b> @{{ version }}</a>
                    <p v-show="activeLove" class="love">Made with <i class="far fa-heart" :class="classColor" aria-hidden="true"></i>
                    by Aduxia</p>
                </div>

            `
        });

        app.mount('#app');
    </script>
@stop
