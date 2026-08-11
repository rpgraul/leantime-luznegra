@extends($layout)
@section('content')

<div class="regcontent">
    <style>
        .regcontent input[type="text"],
        .regcontent input[type="email"],
        .regcontent input[type="password"],
        .regcontent input[type="submit"],
        .regcontent button {
            border-radius: 4px !important;
            padding: 12px 16px !important;
            height: auto !important;
            min-height: 48px !important;
            font-size: 16px !important;
        }
    </style>
    @dispatchEvent('afterRegcontentOpen')
    {!! $tpl->displayInlineNotification() !!}

    @if ($noLoginForm === false)
        <form id="login" action="{{ BASE_URL }}/auth/login" method="post">
            @csrf
            @dispatchEvent('afterFormOpen')
        <input type="hidden" name="redirectUrl" value="{{ $redirectUrl }}" />

        <div class="tw-mb-4">
            <label for="username" style="font-weight: bold; margin-bottom: 5px; display: block;">Email</label>
            <x-global::forms.text-input name="username" id="username" placeholder="{{ __($inputPlaceholder) }}" value="" />
        </div>
        <div class="tw-mb-4">
            <label for="password" style="font-weight: bold; margin-bottom: 5px; display: block;">Senha</label>
            <x-global::forms.text-input type="password" name="password" id="password" autocomplete="off" placeholder="Sua senha" value="" />
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                <input type="checkbox" name="remember" id="remember" value="1">
                <span>Permanecer Logado</span>
            </label>
            <a href="{{ BASE_URL }}/auth/resetPw" class="forgotPw" style="text-decoration: none;">Esqueceu a senha?</a>
        </div>

        @dispatchEvent('beforeSubmitButton')
        <div class="">
            <x-global::forms.button tag="input" inputType="submit" name="login" contentRole="primary" labelText="Entrar" style="width: 100%;" />
        </div>
        @dispatchEvent('beforeFormClose')

    </form>
    @else
        {!! __('text.no_login_form') !!}<br /><br />
    @endif

    @dispatchEvent('beforeRegcontentClose')
</div>

@endsection
