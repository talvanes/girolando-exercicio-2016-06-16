<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    {{--Página de login--}}
    <h1>Login</h1>

    {{--Mensagens de erro ou sucesso--}}
    @if(count($errors))
    <ul>
        @foreach($errors->all() as $error)
            <span style="display: block;"><span style="color: red;">Erro:</span> {{ $error }}</span> 
        @endforeach
    </ul>
    @endif
    <p>
        @if(Session::has('Erro'))
            <span style="color: red;">Erro:</span> {{ Session::get('Erro') }}
        @elseif(Session::has('Sucesso'))
            <span style="color: green;">Sucesso:</span> {{ Session::get('Sucesso') }}
        @endif
    </p>

    <form action="{!! route('autenticar.store') !!}" method="POST">
        {{ csrf_field() }}
        <p>
            <label for="telefoneUsuario">Telefone:</label>
            <input type="tel" name="telefoneUsuario" id="telefoneUsuario">
        </p>
        <p>
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password">
        </p>
        <p>
            <button type="submit">Login</button>
        </p>
    </form>

</body>
</html>
