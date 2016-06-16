<?php

namespace Segundo\Http\Controllers\Usuario;

use Illuminate\Http\Request;

use Segundo\Http\Requests;
use Segundo\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Exibe um formulário de login, com os campos Telefone e Senha
     */
    public function index()
    {
        // Exibir a tela de login na home (/)
        return view('usuario.login');
    }
}
