<?php

namespace App\Observers;

use App\Observers\Concerns\AuditsChanges;

class CaracteristicaObserver
{
    use AuditsChanges;

    protected string $auditoriaObjeto = 'Característica';
}
