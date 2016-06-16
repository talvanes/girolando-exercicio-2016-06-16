<?php

namespace Segundo\Http\Controllers\Usuario;

use Illuminate\Http\Request;

use Segundo\Http\Requests;
use Segundo\Http\Controllers\Controller;

class SairController extends Controller
{
    /**
     * Este método vai "desautenticar" o usuário da sessão, redirecionando-o ao formulário de login
     *
     * @param Request $request Dados da requisição, o que inclui sessão
     */
    public function index(Request $request)
    {

    }
}
