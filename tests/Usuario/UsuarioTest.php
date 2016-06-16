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
        $this->get('/')
            ->see('Login')
            ->dontSee("Bem-vindo");
    }

    /**
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

        # redirecionar para a home (/) em caso de erro
        $response->assertRedirectedTo('/');
        $response->followRedirects();
        # agora estou na home (/)
        $this->see('Login')
            ->dontSee("Bem-vindo");
    }

    /**
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

        # redirecionar para a home (/) em caso de erro
        $response->assertRedirectedTo('/')
            ->followRedirects();
        # agora estou na home (/)
        $this->see('Login')
            ->dontSee("Bem-vindo");
    }

    /**
     * Se eu preencher um telefone inválido (telefone não existe na base de dados),
     * também preciso ser devolvido para a home (/), com erros [Msg: "Este usuário não existe!"].
     * Aqui neste teste, a senha também não importa, pois o usuário inválido também vale para ambos.
     *
     * Neste caso, espera-se um erro.
     */
    public function testShouldFailOnPhoneInvalid_UserExists()
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

        # redirecionar para a home (/), dando erros na session
        $response->assertRedirectedTo('/')
            ->followRedirects()
            ->assertSessionHas('Erro', "Este usuário não existe!");
        # agora estou na home (/)
        $this->see('Login')
            ->dontSee("Bem-vindo");

    }

    /**
     * O mesmo teste acima, mas sem persistência de registro no banco de dados
     */
    public function testShouldFailOnPhoneInvalid_UserNotExists()
    {
        # garantindo usuário na base de dados
        $usuario = (object) [
            'nomeUsuario' => str_random(40),
            'emailUsuario' => str_random(20),
            'telefoneUsuario' => str_random(8),
            'remember_token' => str_random(16),
            'password' => bcrypt(str_random(16)),
            'statusUsuario' => 1,
        ];
        # o usuário ainda não existe
        $dadosUsuario = [
            'telefoneUsuario' => $usuario->telefoneUsuario,
            'password' => $usuario->password,
        ];

        # autenticação de usuário
        $response = $this->post('/autenticar', $dadosUsuario);

        # redirecionar para a home (/), dando erros na session
        $response->assertRedirectedTo('/')
            ->followRedirects()
            ->assertSessionHas('Erro', "Este usuário não existe!");
        # agora estou na home (/)
        $this->see('Login')
            ->dontSee("Bem-vindo");
    }

    /**
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
            'password' => bcrypt(str_random(10)),
        ];

        # autenticacao de usuário
        $response = $this->post('/autenticar', $dadosUsuario);

        # redirecionar para a home (/), dando erros na session
        $response->assertRedirectedTo('/')
            ->followRedirects()
            ->assertSessionHas('Erro', "A senha não confere!");
        # agora estou na home (/)
        $this->see('Login')
            ->dontSee("Bem-vindo");

    }

    /**
     * O teste deve falhar se o usuário for inativo no sistema.
     *
     * Neste caso, espera-se um erro.
     */
    public function testShouldFailOnInactiveUser()
    {
        # criar usuário já inativo
        $usuario = \Segundo\Models\Pessoa::create([
            'nomeUsuario' => str_random(25),
            'emailUsuario' => str_random(13),
            'telefoneUsuario' => str_random(10),
            'remember_token' => str_random(14),
            'password' => bcrypt(str_random(14)),
            'statusUsuario' => 0,
        ]);
        # as credenciais estão corretas
        $dadosUsuario = [
            'telefoneUsuario' => $usuario->telefoneUsuario,
            'password' => $usuario->password,
        ];

        # autenticação do usuário
        $response = $this->post('/autenticar', $dadosUsuario);

        # garantir que usuário inativo também não logue
        $response->assertRedirectedTo('/')
            ->followRedirects()
            ->assertSessionHas('Erro', "Usuário inativo não pode se autenticar!");
        # agor estou na home (/)
        $this->see('Login')
            ->dontSee("Bem-vindo");
    }

    /**
     * Usuário válido, ativo que digitou a senha correta deve logar no sistena.
     *
     * Neste caso, espera-se um acerto.
     */
    public function testShouldSucceedOnLogin()
    {
        # garantir que o usuário seja criado
        #   (afinal, não se pode confiar no banco de dados)
        $usuario = \Segundo\Models\Pessoa::create([
            'nomeUsuario' => str_random(20),
            'emailUsuario' => str_random(10),
            'telefoneUsuario' => str_random(9),
            'remember_token' => str_random(15),
            'password' => bcrypt(str_random(15)),
            'statusUsuario' => 1,
        ]);
        # as credenciais estão corretas
        $dadosUsuario = [
            'telefoneUsuario' => $usuario->telefoneUsuario,
            'password' => $usuario->password,
        ];

        # autenticação do usuário
        $response = $this->post('/autenticar', $dadosUsuario);

        # garantir que o usuário ativo com as credenciais corretas logue no sistema
        $response->assertRedirectedTo('/dashboard')
            ->followRedirects()
            ->assertSessionHas('Usuario');
        # agora estou na dashboard (/dashboard)
        $this->see("Bem-vindo {$usuario->nomeUsuario}")
            ->dontSee('Login');
        #echo print_r($response->response->getContent(), true);
    }


    /**
     * Usuário registrado vai sair da sessão.
     * 
     * Neste caso, espera-se um acerto.
     */
    public function testShouldSuccedOnLogout()
    {
        # garantindo que o usuário exista (ele já será ativo)
        #   (afinal, não se pode confiar no banco de dados)
        $usuario = \Segundo\Models\Pessoa::create([
            'nomeUsuario' => str_random(20),
            'emailUsuario' => str_random(28),
            'telefoneUsuario' => str_random(10),
            'password' => bcrypt(str_random(36)),
            'statusUsuario' => 1,
        ]);

        # considerando que o usuário já esteja autenticado...
        $response = $this->withSession(['Usuario' => $usuario])
            ->visit('/dashboard');
        # ...quero que ele saia da sessão (consumir a rota /sair)
        $response->get('/sair')
            ->assertRedirectedTo('/')
            ->followRedirects()
            ->assertSessionHas('Sucesso', "Usuário saiu da sessão com sucesso!");
        # agora estou na home (/)
        $this->see('Login')
            ->dontSee("Bem-vindo {$usuario->nomeUsuario}");

    }


}
