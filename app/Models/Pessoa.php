<?php

namespace Segundo\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    /** @var bool  */
    public static $snakeAttributes = false;

    /** @var string  */
    protected $table = 'Usuario';

    /** @var string  */
    protected $primaryKey = 'idUsuario';

    /** @var array  */
    protected $fillable = [
        'nomeUsuario',
        'emailUsuario',
        'telefoneUsuario',
        'password',
        'remember_token',
        'statusUsuario',
    ];

    /** @var array  */
    protected $hidden = ['password', 'remember_token'];
}
