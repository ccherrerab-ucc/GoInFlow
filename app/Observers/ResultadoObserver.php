<?php

namespace App\Observers;

use App\Observers\Concerns\AuditsChanges;

class ResultadoObserver
{
    use AuditsChanges;

    protected string $auditoriaObjeto = 'Resultado';
}
