<?php

namespace App\Observers;

use App\Observers\Concerns\AuditsChanges;

class EvidenciaObserver
{
    use AuditsChanges;

    protected string $auditoriaObjeto = 'Evidencia';
}
