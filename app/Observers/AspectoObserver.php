<?php

namespace App\Observers;

use App\Observers\Concerns\AuditsChanges;

class AspectoObserver
{
    use AuditsChanges;

    protected string $auditoriaObjeto = 'Aspecto';
}
