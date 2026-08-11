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

<div class="row" style="min-height:100vh; max-width: 98vw; height: auto;">
    <div class="col-md-4 hidden-phone regLeft">

        <div class="logo">
            <a href="{!! BASE_URL !!}" target="_blank" style="display: flex; align-items: center; justify-content: center; text-decoration: none; color: inherit; margin-bottom: 20px;">
                <img src="{{ BASE_URL }}/dist/images/luz-negra-logo.png" alt="Editora Luz Negra" style="height: 50px; margin-right: 15px;" />
                <span style="font-weight: bold; font-size: 24px;">Editora Luz Negra</span>
            </a>
        </div>

        <div class="welcomeContent">
            @dispatchFilter('welcomeText', '<h1 class="mainWelcome">'.$language->__("headlines.welcome_back").'</h1>')
        </div>

        @dispatchFilter('belowWelcomeText', '')

    </div>
    <div class="col-md-8 col-sm-12 regRight">

        <div class="regpanel">
            <div class="regpanelinner">

                @if($logoPath != '')
                    <a href="{!! BASE_URL !!}" target="_blank">

                        @if(!str_ends_with($logoPath, "dist/images/logo.svg" ))
                            <img src="{{ $logoPath }}" class="tw-h-full "/>
                        @endif
                    </a>
                @endif

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
