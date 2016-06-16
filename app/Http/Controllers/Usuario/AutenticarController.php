<?php

namespace Segundo\Http\Controllers\Usuario;

use Illuminate\Http\Request;

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
        $credenciaisUsuario = $request->all();
        
        # se não foi preeenchido o login (telefoneUsuario) nem a senha (password),
        #   redirecionar à home (/)!
        if ( !isset($credenciaisUsuario['telefoneUsuario']) || !isset($credenciaisUsuario['password']) ){
            return redirect()->route('index')
                ->with('Erro', "Usuário ou senha em branco");
            //return back();
        }
        
        # se usuário não existir no banco de dados,
        #   redirecionar à home (/), com erro na session
        $usuario = Pessoa::where('telefoneUsuario', '=', $credenciaisUsuario['telefoneUsuario'])->first();
        if ( !$usuario ) {
            return redirect()->route('index')
                ->with('Erro', "Este usuário não existe!");
            //Session::flash('Erro', "Este usuário não existe!");
            //return back();
        }
        # conferir a senha: se for diferente do cadastro,
        #   redirecionar à home (/) com erro na session
        if ( $credenciaisUsuario['password'] != $usuario->password ) {
            return redirect()->route('index')
                ->with('Erro', "A senha não confere!");
            //Session::flash('Erro', "A senha não confere!");
            //return back();
        }

        # barrar também a autenticação de usuário inativo no sistema
        # o usuário está inativo no sistema? redirecionamento na home (/) com erro na session
        if ( !$usuario->statusUsuario ) {
            return redirect()->route('index')
                ->with('Erro', "Usuário inativo não pode se autenticar!");
        }


        # TODO: OK, já que tudo deu certo, redirecionar à dashboard (/dashboard), criando uma sessão de usuário
        return redirect()->route('dashboard.index')
            ->with('Usuario', $usuario);
    }
}
