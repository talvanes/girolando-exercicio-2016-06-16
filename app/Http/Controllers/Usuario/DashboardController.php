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
        $usuario = $request->session()->get('Usuario');

        return "Bem-vindo {$usuario->nomeUsuario}";
    }
}
