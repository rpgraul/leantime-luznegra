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
            border: 1px solid #ccc !important;
            background-color: #fff !important;
            box-shadow: none !important;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .regcontent input[type="text"]:focus,
        .regcontent input[type="email"]:focus,
        .regcontent input[type="password"]:focus {
            outline: none !important;
            border-color: #000 !important;
            box-shadow: 0 0 0 1px #000 !important;
        }

        .regcontent input[type="submit"],
        .regcontent button {
            background-color: #000 !important;
            color: #fff !important;
            border: none !important;
            cursor: pointer;
        }
        
        .regcontent input[type="submit"]:hover,
        .regcontent button:hover {
            background-color: #333 !important;
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
            <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; white-space: nowrap;">
                <input type="checkbox" name="remember" id="remember" value="1" style="margin: 0;">
                <span style="white-space: nowrap;">Permanecer Logado</span>
            </label>
            <a href="{{ BASE_URL }}/auth/resetPw" class="forgotPw" style="text-decoration: none; white-space: nowrap; margin-left: 10px;">Esqueceu a senha?</a>
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
