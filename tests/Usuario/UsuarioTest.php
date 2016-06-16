<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsuarioTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * A home (/) exibe um formulário de login?
     *  [é onde a autenticação ainda será realizada]
     */
    public function testShouldAccessHomePage()
    {
        //TODO: dontSee(): nome do usuário na dashboard
        $this->get('/')
            ->see('Login')
            ->dontSee("Bem-vindo");
    }

    /**
     * FIXME:
     * Se eu preenchar um telefone em branco no login,
     * preciso ser devolvido è home (/).
     * Aqui, a senha não importa, pois, neste teste, o telefoneUsuario em branco vale para ambos.
     *
     * Neste caso, espera-se um erro.
     */
    public function testShouldFailOnBlankPhone()
    {
        # dados de usuário (campo de telefone está em branco)
        $dadosUsuario = [
            'telefoneUsuario' => null,
        ];

        # chamando a rota para autenticação [/autenticar]
        $response = $this->post('/autenticar', $dadosUsuario);

        # TODO: redirecionar para a home (/) em caso de erro

    }

    /**
     * FIXME:
     * Se eu preencher uma senha em branco,
     * também preciso ser devolvido à home (/).
     *
     * Neste caso, espera-se um erro.
     */
    public function testShouldFailOnBlankPassword()
    {
        # dados de usuário (campo de senha está em branco)
        $dadosUsuario = [
            'telefoneUsuario' => str_random(10),
            'password' => null,
        ];

        # chamando a rota para autenticação [/autenticar]
        $response = $this->post('/autenticar', $dadosUsuario);

        # TODO: redirecionar para a home (/) em caso de erro

    }

    /**
     * FIXME:
     * Se eu preencher um telefone inválido (telefone não existe na base de dados),
     * também preciso ser devolvido para a home (/), com erros [Msg: "Este usuário não existe!"].
     * Aqui neste teste, a senha também não importa, pois o usuário inválido também vale para ambos.
     *
     * Neste caso, espera-se um erro.
     */
    public function testShouldFailOnPhoneInvalid()
    {
        # garantir a criação de uusário no banco de dados
        #   (pode não haver usuários no sistema ainda)
        $usuario = \Segundo\Models\Pessoa::create([
            'nomeUsuario' => str_random(30),
            'emailUsuario' => str_random(15),
            'telefoneUsuario' => str_random(10),
            'remember_token' => str_random(10),
            'password' => bcrypt(str_random(10)),
            'statusUsuario' => 1,
        ]);
        # o usuario é outro
        $dadosUsuario = [
            'telefoneUsuario' => str_random(9),
            # o usuário digita a senha plana; criptografia na aplicação.
            'password' => $usuario->password,
        ];

        # autenticação de usuário
        $response = $this->post('/autenticar', $dadosUsuario);

        # TODO: redirecionar para a home (/), dando erros na session

    }
    // TODO: outra variação DO TESTE: apenas criar a pessoa com Pessoa::make(), sem peristi-la no banco de dados

    /**
     * FIXME:
     * O telefone (login de usuário) pode até existir, mas se a senha não conferir,
     *  barrar a autenticação de usuário, redirecionando-o à home (/) dando erros.
     */
    public function testShouldFailOnWrongPassword()
    {
        # garantir a criação de uusário no banco de dados
        #   (pode não haver usuários no sistema ainda)
        $usuario = \Segundo\Models\Pessoa::create([
            'nomeUsuario' => str_random(32),
            'emailUsuario' => str_random(16),
            'telefoneUsuario' => str_random(9),
            'remember_token' => str_random(11),
            'password' => bcrypt(str_random(11)),
            'statusUsuario' => 1,
        ]);
        # senha é outra
        $dadosUsuario = [
            'telefoneUsuario' => $usuario->telefoneUsuario,
            'password' => str_random(10),
        ];

        # autenticacao de usuário
        $response = $this->post('/autenticar', $dadosUsuario);

        # TODO: redirecionar para a home (/), dando erros na session

    }

    /**
     * TODO:
     * O teste deve falhar se o usuário for inativo no sistema.
     *
     * Neste caso, espera-se um erro.
     */
    public function testShouldFailOnInactiveUser()
    {
        # criar usuário já inativo
    }

    /**
     * TODO:
     * Usuário válido devidamente registrado com senha válida deve logar no sistena.
     *
     * Neste caso, espera-se um acerto.
     */
    public function testShouldSucceedOnLogin()
    {

    }


}
