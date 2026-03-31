<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;

class DepartamentoController extends Controller
{
    public function getByArea($area_id)
    {
        $departamentos = Departamento::where('area_id', $area_id)->get();

        return response()->json($departamentos);
    }
}
