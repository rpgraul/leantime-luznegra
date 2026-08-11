<!DOCTYPE html>
<html dir="{{ __('language.direction') }}" lang="{{ __('language.code') }}">
<head>
    @include('global::sections.header')
    <style>
        .leantimeLogo { position: fixed; bottom: 10px; right: 10px; }
    </style>
    @stack('styles')
</head>

<body class="loginpage" style="height:100%;" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>

<div class="header hidden-gt-sm tw-p-[10px]" style="background:var(--header-gradient)">
    <a href="{!! BASE_URL !!}" target="_blank" style="display: flex; align-items: center; text-decoration: none; color: white;">
        <img src="{{ BASE_URL }}/dist/images/luz-negra-logo.png" class="tw-h-full" alt="Editora Luz Negra" style="margin-right: 10px;" />
        <span style="font-weight: bold;">Editora Luz Negra</span>
    </a>
</div>

<div class="row" style="min-height:100vh; max-width: 100vw; height: auto; margin: 0;">
    <div class="col-md-6 hidden-phone regLeft" style="background-color: #000; display: flex; flex-direction: column; align-items: center; justify-content: center;">

        <div class="logo">
            <a href="{!! BASE_URL !!}" target="_blank" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; color: white; margin-bottom: 20px;">
                <img src="{{ BASE_URL }}/dist/images/luz-negra-logo.png" alt="Editora Luz Negra" style="height: 80px; margin-bottom: 15px;" />
                <span style="font-weight: bold; font-size: 28px;">Editora Luz Negra</span>
            </a>
        </div>

    </div>
    <div class="col-md-6 col-sm-12 regRight" style="display: flex; align-items: center; justify-content: center;">

        <div class="regpanel" style="width: 100%; max-width: 400px; padding: 20px;">
            <div class="regpanelinner">
                @isset($action, $module)
                    @include("$module::$action")
                @else
                    @yield('content')
                @endisset
            </div>
        </div>

    </div>
    <div class="leantimeLogo">
        <img style="height: 25px;" src="{!! BASE_URL !!}/dist/images/logo-powered-by-leantime.png">
    </div>
</div>

@include('global::sections.pageBottom')
@stack('scripts')
</body>

</html>
