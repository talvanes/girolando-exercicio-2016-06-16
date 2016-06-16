<?php

namespace Segundo\Http\Controllers\Usuario;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Segundo\Http\Requests;
use Segundo\Http\Controllers\Controller;
use Segundo\Models\Pessoa;

class AutenticarController extends Controller
{
    /**
     * Este método receberá os dados para a autenticação (telefone e senha)
     *
     * @param Request $request Dados para autenticação
     */
    public function store(Request $request)
    {
        # credenciais de usuário
        $credenciais = $request->all();
        //return bcrypt($credenciais['password']);
        # tratando as entradas (senha é informada plana no formulário, mas é armazenada criptigrafada no banco de dados)
        $credenciais['password'] = hash('whirlpool', $credenciais['password']);

        
        # se não foi preeenchido o login (telefoneUsuario) nem a senha (password),
        #   redirecionar à home (/)!
        if ( empty($credenciais['telefoneUsuario']) || empty($credenciais['password']) ){
            return redirect()->route('index')
                ->with('Erro', trans('usuario.autenticar.erro.informeDados'));
        }
        
        # se usuário não existir no banco de dados,
        #   redirecionar à home (/), com erro na session
        $usuario = Pessoa::where('telefoneUsuario', '=', $credenciais['telefoneUsuario'])
            ->first();
        if ( !$usuario ) {
            return redirect()->route('index')
                ->with('Erro', trans('usuario.autenticar.erro.usuarioNaoExiste'));
        }
        # conferir a senha: se for diferente do cadastro,
        #   redirecionar à home (/) com erro na session
        if ( $credenciais['password'] != $usuario->password ) {
            return redirect()->route('index')
                ->with('Erro', trans('usuario.autenticar.erro.senhaNaoConfere'));
        }

        # barrar também a autenticação de usuário inativo no sistema
        # o usuário está inativo no sistema? redirecionamento na home (/) com erro na session
        if ( !$usuario->statusUsuario ) {
            return redirect()->route('index')
                ->with('Erro', trans('usuario.autenticar.erro.usuarioInativo'));
        }


        # OK, já que tudo deu certo, redirecionar à dashboard (/dashboard), criando uma sessão de usuário
        return redirect()->route('dashboard.index')
            ->with('Usuario', $usuario);
    }
}
