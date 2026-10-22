<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class SingleRowAlreadyExists extends RuntimeException
{
    public static function for(Model $model): self
    {
        return new self('Tabel '.$model->getTable().' hanya boleh berisi satu baris.');
    }
}
