@extends($layout)
@section('content')

<div class="clean-login-form" style="width: 100%; padding: 40px 0;">
    <style>
        .clean-login-form input[type="text"],
        .clean-login-form input[type="email"],
        .clean-login-form input[type="password"] {
            width: 100%;
            border-radius: 4px;
            padding: 12px 16px;
            height: 54px;
            font-size: 16px;
            border: 1px solid #ccc;
            background-color: #fff;
            box-shadow: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
            margin-top: 8px;
            color: #333;
        }

        .clean-login-form input[type="text"]:focus,
        .clean-login-form input[type="email"]:focus,
        .clean-login-form input[type="password"]:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 1px #000;
        }

        .clean-login-form input[type="submit"] {
            width: 100%;
            border-radius: 4px;
            padding: 14px 16px;
            height: 54px;
            font-size: 16px;
            background-color: #000;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
            font-weight: bold;
        }
        
        .clean-login-form input[type="submit"]:hover {
            background-color: #333;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            font-weight: bold;
            display: block;
            color: #111;
            font-size: 14px;
        }
    </style>

    {!! $tpl->displayInlineNotification() !!}

    @if ($noLoginForm === false)
        <form id="login" action="{{ BASE_URL }}/auth/login" method="post">
            @csrf
            <input type="hidden" name="redirectUrl" value="{{ $redirectUrl }}" />

            <div class="form-group">
                <label for="username" class="form-label">Email</label>
                <input type="email" name="username" id="username" placeholder="{{ __($inputPlaceholder) }}" value="" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" autocomplete="off" placeholder="Sua senha" value="" required>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; color: #444; font-size: 14px;">
                    <input type="checkbox" name="remember" id="remember" value="1" style="width: 18px; height: 18px; margin: 0; cursor: pointer;">
                    <span style="white-space: nowrap;">Permanecer Logado</span>
                </label>
                <a href="{{ BASE_URL }}/auth/resetPw" style="text-decoration: none; color: #444; white-space: nowrap; font-size: 14px;">Esqueceu a senha?</a>
            </div>

            <input type="submit" name="login" value="Entrar" />

        </form>
    @else
        {!! __('text.no_login_form') !!}<br /><br />
    @endif

</div>

@endsection
