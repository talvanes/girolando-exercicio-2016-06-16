<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    {{--Dashboard (área restrita)--}}
    <h1>Bem-vindo, <span style="text-decoration: solid;">{{ $Usuario->nomeUsuario }}</span>!</h1>
    <span style="float: right;"><a href="{!! route('sair.index') !!}">[Sair]</a></span>
</body>
</html>
