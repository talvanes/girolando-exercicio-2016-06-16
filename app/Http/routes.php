<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Formulário de login
Route::resource('/', 'Usuario\LoginController', ['only' => 'index']);

// Aqui serão recebidos os dados para autenticação
Route::resource('/autenticar', 'Usuario\AutenticarController', ['only' => 'store']);

// Esta rota vai exibir a dashboard
Route::resource('/dashboard', 'Usuario\DashboardController', ['only' => 'index']);

// Esta rota vai "desautenticar" o usuário, redirecionando-o para o formulário de login
Route::resource('/sair', 'Usuario\SairController', ['only' => 'index']); # index ou destroy