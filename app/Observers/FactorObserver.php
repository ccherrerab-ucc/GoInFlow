<?php

namespace App\Observers;

use App\Observers\Concerns\AuditsChanges;

class FactorObserver
{
    use AuditsChanges;

    protected string $auditoriaObjeto = 'Factor';
}
