<!DOCTYPE html>
<html dir="{{ __('language.direction') }}" lang="{{ __('language.code') }}">
<head>
    @include('global::sections.header')
    @stack('styles')
    <style>
        body.loginpage {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            background: #fff;
            overflow-x: hidden;
        }
        .login-left {
            width: 50%;
            background-color: #000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .login-right {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: #fff;
        }
        .leantimeLogo {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }
        /* Mobile responsive */
        @media (max-width: 768px) {
            body.loginpage { flex-direction: column; height: auto; min-height: 100vh; }
            .login-left, .login-right { width: 100%; min-height: 50vh; }
            .login-right { padding: 40px 20px; box-sizing: border-box; }
        }
    </style>
</head>

<body class="loginpage" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>

    <div class="login-left">
        <a href="{!! BASE_URL !!}" target="_blank" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; color: white;">
            <img src="{{ BASE_URL }}/dist/images/luz-negra-logo.png" alt="Editora Luz Negra" style="height: 180px; margin-bottom: 20px;" />
            <span style="font-weight: bold; font-size: 40px; text-align: center; margin: 0; padding: 0;">Editora Luz Negra</span>
        </a>
    </div>

    <div class="login-right">
        <div style="width: 100%; max-width: 400px; z-index: 10;">
            @isset($action, $module)
                @include("$module::$action")
            @else
                @yield('content')
            @endisset
        </div>
        
        <div class="leantimeLogo">
            <img style="height: 25px;" src="{!! BASE_URL !!}/dist/images/logo-powered-by-leantime.png" alt="Powered by Leantime">
        </div>
    </div>

@include('global::sections.pageBottom')
@stack('scripts')
</body>
</html>
