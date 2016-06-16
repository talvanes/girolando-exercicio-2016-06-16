<?php

namespace Segundo\Http\Controllers\Usuario;

use Illuminate\Http\Request;

use Segundo\Http\Requests;
use Segundo\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Este método exibe a dashboard, com os dados do usuário
     * 
     * @param Request $request Dados de requisição
     */
    public function index(Request $request)
    {
        // Recuperando o usuário logado
        $usuario = session('Usuario');

        // Sem credenciais de usuário, não há como acessar a área restrita
        if (!$usuario) return redirect()->route('index');

        // Exibir a dashboard (área restrita)
        return view('usuario.dashboard')
            ->with('Usuario', $usuario);
    }
}
