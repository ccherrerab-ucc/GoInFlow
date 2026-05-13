@extends('administrator.app')

@section('title', 'Centro de Ayuda')

@section('content')

{{-- Encabezado --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <div class="gf-page-title">Centro de ayuda</div>
        <div class="gf-page-sub">Manual de usuario, reglas del sistema y soporte</div>
    </div>
    <a href="mailto:administrador@ucatolica.edu.co?subject=Soporte%20Evidentia"
       class="gf-btn gf-btn-primary">
        <i class="bi bi-envelope"></i> Contactar administrador
    </a>
</div>

{{-- Pestañas principales --}}
<ul class="nav nav-tabs mb-4" id="ayudaTabs" role="tablist"
    style="border-bottom:2px solid var(--primary-border);gap:4px;">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="manual-tab"
                data-bs-toggle="tab" data-bs-target="#manual"
                type="button" role="tab"
                style="font-size:13px;font-weight:600;color:var(--gray-600);
                       border:none;border-bottom:3px solid transparent;
                       border-radius:0;padding:10px 18px;background:transparent;">
            <i class="bi bi-book me-2"></i>Manual de usuario
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="reglas-tab"
                data-bs-toggle="tab" data-bs-target="#reglas"
                type="button" role="tab"
                style="font-size:13px;font-weight:600;color:var(--gray-600);
                       border:none;border-bottom:3px solid transparent;
                       border-radius:0;padding:10px 18px;background:transparent;">
            <i class="bi bi-info-circle me-2"></i>Reglas y roles
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="soporte-tab"
                data-bs-toggle="tab" data-bs-target="#soporte"
                type="button" role="tab"
                style="font-size:13px;font-weight:600;color:var(--gray-600);
                       border:none;border-bottom:3px solid transparent;
                       border-radius:0;padding:10px 18px;background:transparent;">
            <i class="bi bi-headset me-2"></i>Soporte y FAQ
        </button>
    </li>
</ul>

<div class="tab-content" id="ayudaTabsContent">

    {{-- ══════════════════════════════════════════════
         PESTAÑA 1 · MANUAL DE USUARIO
    ══════════════════════════════════════════════ --}}
    <div class="tab-pane fade show active" id="manual" role="tabpanel">

        {{-- Introducción al proyecto --}}
        <div class="gf-card" style="background:var(--primary);border-color:var(--primary-dark);margin-bottom:24px;">
            <div class="row align-items-center g-0">
                <div class="col-auto" style="padding-right:24px;">
                    <div style="width:64px;height:64px;border-radius:14px;background:rgba(255,255,255,0.15);
                                border:2px solid rgba(255,255,255,0.25);display:flex;align-items:center;
                                justify-content:center;">
                        <i class="bi bi-diagram-3-fill" style="font-size:30px;color:#fff;"></i>
                    </div>
                </div>
                <div class="col">
                    <div style="font-size:18px;font-weight:700;color:#fff;margin-bottom:4px;">
                        Evidentia — Sistema de Gestión CNA
                    </div>
                    <div style="font-size:13px;color:var(--primary-border);line-height:1.7;">
                        Evidentia es la plataforma institucional de la <strong style="color:#fff;">Universidad Católica de Colombia</strong>
                        para gestionar el proceso de acreditación ante el Consejo Nacional de Acreditación (<strong style="color:#fff;">CNA</strong>).
                        Permite organizar factores, características, aspectos y evidencias, controlar el flujo de aprobación
                        de documentos y generar resultados de evaluación, todo con trazabilidad completa.
                    </div>
                </div>
            </div>
        </div>

        {{-- Flujo general del sistema --}}
        <div class="gf-card" style="margin-bottom:24px;">
            <div class="gf-card-title"><i class="bi bi-arrow-right-circle"></i> Flujo general del sistema</div>
            <div style="font-size:13px;color:var(--gray-600);margin-bottom:20px;line-height:1.6;">
                Todo el proceso de acreditación sigue una cadena jerárquica. Cada nivel debe existir antes de crear el siguiente.
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                @php
                $pasos = [
                    ['icono'=>'bi-grid-3x3-gap','label'=>'Factor','sub'=>'Área temática del CNA'],
                    ['icono'=>'bi-star','label'=>'Característica','sub'=>'Criterio del factor'],
                    ['icono'=>'bi-card-checklist','label'=>'Aspecto','sub'=>'Subcriterio a evaluar'],
                    ['icono'=>'bi-file-earmark-check','label'=>'Evidencia','sub'=>'Documento soporte'],
                    ['icono'=>'bi-trophy','label'=>'Resultado','sub'=>'Valoración final'],
                ];
                @endphp
                @foreach($pasos as $i => $paso)
                    <div style="flex:1;min-width:120px;text-align:center;
                                background:var(--primary-light);border:1px solid var(--primary-border);
                                border-radius:12px;padding:14px 10px;">
                        <i class="bi {{ $paso['icono'] }}" style="font-size:22px;color:var(--primary);display:block;margin-bottom:6px;"></i>
                        <div style="font-size:13px;font-weight:700;color:var(--primary);">{{ $paso['label'] }}</div>
                        <div style="font-size:11px;color:var(--gray-600);margin-top:2px;">{{ $paso['sub'] }}</div>
                    </div>
                    @if(!$loop->last)
                        <div style="flex-shrink:0;color:var(--primary-border);font-size:20px;">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Módulos detallados --}}
        <div class="gf-card-title mb-3" style="font-size:15px;font-weight:700;color:var(--gray-900);">
            <i class="bi bi-list-ul" style="color:var(--primary);"></i> Guía módulo a módulo
        </div>

        <div class="accordion" id="manualAccordion">

            {{-- Factores --}}
            <div class="gf-card mb-3 p-0" style="overflow:hidden;">
                <button class="accordion-button" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mod-factores"
                        style="background:transparent;border:none;width:100%;text-align:left;
                               padding:16px 20px;font-size:14px;font-weight:700;color:var(--primary);
                               display:flex;align-items:center;gap:12px;box-shadow:none;">
                    <div style="width:36px;height:36px;border-radius:9px;background:var(--primary-light);
                                border:1px solid var(--primary-border);display:flex;align-items:center;
                                justify-content:center;flex-shrink:0;">
                        <i class="bi bi-grid-3x3-gap" style="color:var(--primary);font-size:16px;"></i>
                    </div>
                    <span>Módulo: Factores</span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size:14px;transition:.2s;"></i>
                </button>
                <div id="mod-factores" class="accordion-collapse collapse show" data-bs-parent="#manualAccordion">
                    <div style="padding:0 20px 20px;border-top:1px solid var(--gray-100);">
                        <p style="font-size:13px;color:var(--gray-600);margin:16px 0 12px;line-height:1.7;">
                            Los <strong>Factores</strong> son las grandes áreas temáticas definidas por el CNA que la
                            institución debe evaluar (p. ej., Misión y Proyecto Institucional, Estudiantes, Profesores, etc.).
                            Son el primer nivel de la jerarquía y solo el <strong>Administrador</strong> y el
                            <strong>Director de Programa</strong> pueden crearlos o editarlos.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div style="background:var(--gray-50);border-radius:10px;padding:14px 16px;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">
                                        <i class="bi bi-plus-circle" style="color:var(--primary);"></i> Crear un factor
                                    </div>
                                    <ol style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li>Ve al menú <em>Factores</em> en la barra lateral.</li>
                                        <li>Haz clic en <strong>Nuevo factor</strong>.</li>
                                        <li>Completa nombre, descripción (opcional) y fechas.</li>
                                        <li>Asigna un <strong>Responsable</strong>; el sistema le asignará automáticamente el rol Director.</li>
                                        <li>Guarda. El factor aparece en el listado con estado <em>Activo</em>.</li>
                                    </ol>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background:var(--gray-50);border-radius:10px;padding:14px 16px;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">
                                        <i class="bi bi-exclamation-triangle" style="color:var(--danger-text);"></i> Puntos clave
                                    </div>
                                    <ul style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li>El ojo <i class="bi bi-eye"></i> en el listado muestra la descripción sin abrir el formulario.</li>
                                        <li>Suprimir un factor <strong>no</strong> elimina sus características; quedan marcadas como Suprimidas.</li>
                                        <li>Asignar un nuevo responsable cambia el rol del usuario anterior si era Director de ese factor.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Características --}}
            <div class="gf-card mb-3 p-0" style="overflow:hidden;">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mod-car"
                        style="background:transparent;border:none;width:100%;text-align:left;
                               padding:16px 20px;font-size:14px;font-weight:700;color:var(--primary);
                               display:flex;align-items:center;gap:12px;box-shadow:none;">
                    <div style="width:36px;height:36px;border-radius:9px;background:var(--primary-light);
                                border:1px solid var(--primary-border);display:flex;align-items:center;
                                justify-content:center;flex-shrink:0;">
                        <i class="bi bi-star" style="color:var(--primary);font-size:16px;"></i>
                    </div>
                    <span>Módulo: Características</span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size:14px;transition:.2s;"></i>
                </button>
                <div id="mod-car" class="accordion-collapse collapse" data-bs-parent="#manualAccordion">
                    <div style="padding:0 20px 20px;border-top:1px solid var(--gray-100);">
                        <p style="font-size:13px;color:var(--gray-600);margin:16px 0 12px;line-height:1.7;">
                            Las <strong>Características</strong> son los criterios específicos dentro de cada factor
                            (p. ej., "Misión institucional", "Integridad institucional"). Cada característica pertenece
                            a un único factor y tiene un <strong>Líder de Característica</strong> asignado que gestiona
                            su flujo de evidencias.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div style="background:var(--gray-50);border-radius:10px;padding:14px 16px;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">
                                        <i class="bi bi-plus-circle" style="color:var(--primary);"></i> Crear una característica
                                    </div>
                                    <ol style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li>Ve a <em>Características</em> en el menú.</li>
                                        <li>Haz clic en <strong>Nueva característica</strong>.</li>
                                        <li>Selecciona el <strong>Factor</strong> al que pertenece.</li>
                                        <li>Completa nombre, descripción, ruta de carpeta y fechas.</li>
                                        <li>Asigna un <strong>Responsable</strong>; el sistema le otorgará el rol <em>Líder de Característica</em>.</li>
                                        <li>Guarda y el sistema genera el flujo de aprobación automáticamente.</li>
                                    </ol>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background:var(--gray-50);border-radius:10px;padding:14px 16px;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">
                                        <i class="bi bi-folder2" style="color:var(--primary);"></i> Campo: Ruta de carpeta
                                    </div>
                                    <p style="font-size:12px;color:var(--gray-600);line-height:1.8;margin:0;">
                                        Indica la ruta en el repositorio documental donde se almacenan las evidencias
                                        de esta característica (p. ej., <code>Factores/Factor 1/Característica 2</code>).
                                        Es referencial; el sistema no navega automáticamente a esa ruta.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aspectos --}}
            <div class="gf-card mb-3 p-0" style="overflow:hidden;">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mod-asp"
                        style="background:transparent;border:none;width:100%;text-align:left;
                               padding:16px 20px;font-size:14px;font-weight:700;color:var(--primary);
                               display:flex;align-items:center;gap:12px;box-shadow:none;">
                    <div style="width:36px;height:36px;border-radius:9px;background:var(--primary-light);
                                border:1px solid var(--primary-border);display:flex;align-items:center;
                                justify-content:center;flex-shrink:0;">
                        <i class="bi bi-card-checklist" style="color:var(--primary);font-size:16px;"></i>
                    </div>
                    <span>Módulo: Aspectos por evaluar</span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size:14px;transition:.2s;"></i>
                </button>
                <div id="mod-asp" class="accordion-collapse collapse" data-bs-parent="#manualAccordion">
                    <div style="padding:0 20px 20px;border-top:1px solid var(--gray-100);">
                        <p style="font-size:13px;color:var(--gray-600);margin:16px 0 12px;line-height:1.7;">
                            Los <strong>Aspectos</strong> son los subcriterios concretos que se evalúan dentro de cada
                            característica. Cada aspecto tiene un <strong>Enlace</strong> responsable de cargar
                            las evidencias que lo sustentan.
                        </p>
                        <div style="background:var(--gray-50);border-radius:10px;padding:14px 16px;">
                            <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">
                                <i class="bi bi-plus-circle" style="color:var(--primary);"></i> Crear un aspecto
                            </div>
                            <ol style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                <li>Ve a <em>Aspectos</em> en el menú.</li>
                                <li>Haz clic en <strong>Nuevo aspecto</strong>.</li>
                                <li>Selecciona la <strong>Característica</strong> a la que pertenece (agrupada por factor).</li>
                                <li>Completa nombre, descripción, ruta de carpeta y fechas.</li>
                                <li>Asigna un <strong>Responsable (Enlace)</strong> que será el encargado de cargar sus evidencias.</li>
                                <li>Guarda. El aspecto queda disponible para recibir evidencias.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Evidencias --}}
            <div class="gf-card mb-3 p-0" style="overflow:hidden;">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mod-ev"
                        style="background:transparent;border:none;width:100%;text-align:left;
                               padding:16px 20px;font-size:14px;font-weight:700;color:var(--primary);
                               display:flex;align-items:center;gap:12px;box-shadow:none;">
                    <div style="width:36px;height:36px;border-radius:9px;background:var(--primary-light);
                                border:1px solid var(--primary-border);display:flex;align-items:center;
                                justify-content:center;flex-shrink:0;">
                        <i class="bi bi-file-earmark-check" style="color:var(--primary);font-size:16px;"></i>
                    </div>
                    <span>Módulo: Evidencias</span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size:14px;transition:.2s;"></i>
                </button>
                <div id="mod-ev" class="accordion-collapse collapse" data-bs-parent="#manualAccordion">
                    <div style="padding:0 20px 20px;border-top:1px solid var(--gray-100);">
                        <p style="font-size:13px;color:var(--gray-600);margin:16px 0 12px;line-height:1.7;">
                            Las <strong>Evidencias</strong> son los documentos o soportes que demuestran el cumplimiento
                            de un aspecto. Deben pasar por un flujo de aprobación antes de poder usarse en un Resultado.
                        </p>

                        {{-- Flujo visual --}}
                        <div style="background:var(--primary-light);border:1px solid var(--primary-border);
                                    border-radius:10px;padding:16px;margin-bottom:16px;">
                            <div style="font-size:12px;font-weight:700;color:var(--primary);margin-bottom:12px;">
                                <i class="bi bi-arrow-repeat"></i> Flujo de aprobación
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                @php
                                $estados = [
                                    ['color'=>'#888780','bg'=>'#F1EFE8','border'=>'#D3D1C7','icono'=>'bi-pencil','label'=>'Borrador'],
                                    ['color'=>'#185FA5','bg'=>'#E6F1FB','border'=>'#B5D4F4','icono'=>'bi-send','label'=>'En revisión'],
                                    ['color'=>'#27500A','bg'=>'#EAF3DE','border'=>'#97C459','icono'=>'bi-check-circle','label'=>'Aprobada'],
                                    ['color'=>'#A32D2D','bg'=>'#FCEBEB','border'=>'#F09595','icono'=>'bi-x-circle','label'=>'Rechazada'],
                                ];
                                @endphp
                                @foreach($estados as $j => $est)
                                    <div style="text-align:center;background:{{ $est['bg'] }};border:1px solid {{ $est['border'] }};
                                                border-radius:8px;padding:8px 14px;flex:1;min-width:90px;">
                                        <i class="bi {{ $est['icono'] }}" style="color:{{ $est['color'] }};font-size:16px;display:block;margin-bottom:4px;"></i>
                                        <span style="font-size:11px;font-weight:700;color:{{ $est['color'] }};">{{ $est['label'] }}</span>
                                    </div>
                                    @if($j < 2)
                                        <div style="color:var(--gray-400);font-size:16px;flex-shrink:0;">
                                            <i class="bi bi-arrow-right"></i>
                                        </div>
                                    @elseif($j === 2)
                                        <div style="font-size:11px;color:var(--gray-600);text-align:center;flex-shrink:0;">ó</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div style="background:var(--gray-50);border-radius:10px;padding:12px 14px;height:100%;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:6px;">
                                        <i class="bi bi-person" style="color:var(--primary);"></i> Enlace
                                    </div>
                                    <ul style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li>Crea la evidencia en estado <em>Borrador</em>.</li>
                                        <li>Completa los datos y la URL del documento.</li>
                                        <li>Envía al flujo con el botón <strong>Iniciar revisión</strong>.</li>
                                        <li>Si es rechazada, la edita y vuelve a enviar.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div style="background:var(--gray-50);border-radius:10px;padding:12px 14px;height:100%;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:6px;">
                                        <i class="bi bi-person-check" style="color:var(--primary);"></i> Líder de Característica
                                    </div>
                                    <ul style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li>Recibe las evidencias en revisión.</li>
                                        <li>Puede <strong>Aprobar</strong> o <strong>Rechazar</strong>.</li>
                                        <li>Si rechaza, puede escribir un comentario.</li>
                                        <li>Solo evidencias <em>Aprobadas</em> se usan en Resultados.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div style="background:var(--gray-50);border-radius:10px;padding:12px 14px;height:100%;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:6px;">
                                        <i class="bi bi-link-45deg" style="color:var(--primary);"></i> URL de evidencia
                                    </div>
                                    <p style="font-size:12px;color:var(--gray-600);line-height:1.8;margin:0;">
                                        Cada evidencia registra la URL donde está almacenado el documento
                                        (OneDrive, SharePoint, etc.). El sistema no sube archivos directamente;
                                        almacena el enlace de acceso.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resultados --}}
            <div class="gf-card mb-3 p-0" style="overflow:hidden;">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mod-res"
                        style="background:transparent;border:none;width:100%;text-align:left;
                               padding:16px 20px;font-size:14px;font-weight:700;color:var(--primary);
                               display:flex;align-items:center;gap:12px;box-shadow:none;">
                    <div style="width:36px;height:36px;border-radius:9px;background:var(--primary-light);
                                border:1px solid var(--primary-border);display:flex;align-items:center;
                                justify-content:center;flex-shrink:0;">
                        <i class="bi bi-trophy" style="color:var(--primary);font-size:16px;"></i>
                    </div>
                    <span>Módulo: Resultados</span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size:14px;transition:.2s;"></i>
                </button>
                <div id="mod-res" class="accordion-collapse collapse" data-bs-parent="#manualAccordion">
                    <div style="padding:0 20px 20px;border-top:1px solid var(--gray-100);">
                        <p style="font-size:13px;color:var(--gray-600);margin:16px 0 12px;line-height:1.7;">
                            Los <strong>Resultados</strong> consolidan la evaluación asociando evidencias aprobadas
                            a una valoración formal. Solo se pueden usar evidencias con estado <strong>Aprobada</strong>.
                            Cada usuario solo puede asociar evidencias de los aspectos a los que tiene acceso.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div style="background:var(--gray-50);border-radius:10px;padding:14px 16px;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">
                                        <i class="bi bi-plus-circle" style="color:var(--primary);"></i> Crear un resultado
                                    </div>
                                    <ol style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li>Ve a <em>Resultados</em> en el menú.</li>
                                        <li>Haz clic en <strong>Nuevo resultado</strong>.</li>
                                        <li>Completa nombre, descripción y fechas.</li>
                                        <li>Selecciona el estado de evaluación.</li>
                                        <li>Navega la jerarquía Factor → Característica → Aspecto y marca las evidencias aprobadas.</li>
                                        <li>Guarda. Debe haber al menos una evidencia seleccionada.</li>
                                    </ol>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background:var(--gray-50);border-radius:10px;padding:14px 16px;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">
                                        <i class="bi bi-lock" style="color:var(--primary);"></i> Control de acceso
                                    </div>
                                    <ul style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li><strong>Enlace:</strong> solo puede editar los resultados que él mismo creó.</li>
                                        <li><strong>Líder:</strong> edita resultados de sus características.</li>
                                        <li><strong>Admin / Dir. Programa:</strong> acceso total.</li>
                                        <li>No es posible asociar evidencias de aspectos ajenos aunque se conozca el ID.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Usuarios --}}
            <div class="gf-card mb-3 p-0" style="overflow:hidden;">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mod-users"
                        style="background:transparent;border:none;width:100%;text-align:left;
                               padding:16px 20px;font-size:14px;font-weight:700;color:var(--primary);
                               display:flex;align-items:center;gap:12px;box-shadow:none;">
                    <div style="width:36px;height:36px;border-radius:9px;background:var(--primary-light);
                                border:1px solid var(--primary-border);display:flex;align-items:center;
                                justify-content:center;flex-shrink:0;">
                        <i class="bi bi-people" style="color:var(--primary);font-size:16px;"></i>
                    </div>
                    <span>Módulo: Gestión de usuarios</span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size:14px;transition:.2s;"></i>
                </button>
                <div id="mod-users" class="accordion-collapse collapse" data-bs-parent="#manualAccordion">
                    <div style="padding:0 20px 20px;border-top:1px solid var(--gray-100);">
                        <p style="font-size:13px;color:var(--gray-600);margin:16px 0 12px;line-height:1.7;">
                            Solo el <strong>Administrador</strong> tiene acceso al módulo de gestión de usuarios.
                            Desde aquí puede crear, editar y suprimir cuentas, además de cambiar roles manualmente.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div style="background:var(--gray-50);border-radius:10px;padding:14px 16px;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">
                                        <i class="bi bi-person-plus" style="color:var(--primary);"></i> Crear usuario
                                    </div>
                                    <ol style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li>Ve a <em>Usuarios</em> en el menú.</li>
                                        <li>Haz clic en <strong>Nuevo usuario</strong>.</li>
                                        <li>Completa los datos personales, área y departamento.</li>
                                        <li>Selecciona el <strong>Rol</strong> inicial. El rol cambia automáticamente cuando se asigna al usuario como responsable de un factor o característica.</li>
                                        <li>Guarda. El usuario recibirá sus credenciales por correo.</li>
                                    </ol>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background:var(--gray-50);border-radius:10px;padding:14px 16px;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">
                                        <i class="bi bi-arrow-up-circle" style="color:var(--primary);"></i> Cambio de rol automático
                                    </div>
                                    <ul style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li>Asignar como responsable de un <strong>Factor</strong> → rol <em>Director</em>.</li>
                                        <li>Asignar como responsable de una <strong>Característica</strong> → rol <em>Líder de Característica</em>.</li>
                                        <li>Los usuarios nuevos registrados públicamente reciben el rol <em>Enlace</em> por defecto.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Perfil --}}
            <div class="gf-card mb-3 p-0" style="overflow:hidden;">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mod-perfil"
                        style="background:transparent;border:none;width:100%;text-align:left;
                               padding:16px 20px;font-size:14px;font-weight:700;color:var(--primary);
                               display:flex;align-items:center;gap:12px;box-shadow:none;">
                    <div style="width:36px;height:36px;border-radius:9px;background:var(--primary-light);
                                border:1px solid var(--primary-border);display:flex;align-items:center;
                                justify-content:center;flex-shrink:0;">
                        <i class="bi bi-person-circle" style="color:var(--primary);font-size:16px;"></i>
                    </div>
                    <span>Mi perfil</span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size:14px;transition:.2s;"></i>
                </button>
                <div id="mod-perfil" class="accordion-collapse collapse" data-bs-parent="#manualAccordion">
                    <div style="padding:0 20px 20px;border-top:1px solid var(--gray-100);">
                        <p style="font-size:13px;color:var(--gray-600);margin:16px 0 12px;line-height:1.7;">
                            Desde <strong>Mi perfil</strong> (icono de usuario en la barra superior) cada usuario
                            puede actualizar sus datos personales y cambiar su contraseña.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div style="background:var(--gray-50);border-radius:10px;padding:12px 14px;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:6px;">
                                        <i class="bi bi-check2-circle" style="color:var(--primary);"></i> Puedes hacer
                                    </div>
                                    <ul style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li>Actualizar nombre, apellidos y correo electrónico.</li>
                                        <li>Cambiar tu área y departamento.</li>
                                        <li>Establecer una nueva contraseña.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background:var(--gray-50);border-radius:10px;padding:12px 14px;">
                                    <div style="font-size:12px;font-weight:700;color:var(--gray-900);margin-bottom:6px;">
                                        <i class="bi bi-x-circle" style="color:var(--danger-text);"></i> No puedes hacer
                                    </div>
                                    <ul style="margin:0;padding-left:16px;font-size:12px;color:var(--gray-600);line-height:1.8;">
                                        <li>Cambiar tu propio rol (lo gestiona el administrador).</li>
                                        <li>Eliminar tu cuenta (la gestión de cuentas es exclusiva del administrador).</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- fin accordion --}}
    </div>

    {{-- ══════════════════════════════════════════════
         PESTAÑA 2 · REGLAS Y ROLES
    ══════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="reglas" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="gf-card">
                    <div class="gf-card-title"><i class="bi bi-info-circle"></i> Reglas generales del sistema</div>
                    @php
                    $reglas = [
                        ['icono'=>'bi-layers','titulo'=>'Estructura jerárquica','texto'=>'La información está organizada en cuatro niveles: Factor → Característica → Aspecto → Evidencia. Cada nivel depende del anterior; no es posible registrar evidencias sin aspectos, ni aspectos sin características.'],
                        ['icono'=>'bi-trash3','titulo'=>'Supresión en lugar de eliminación','texto'=>'Ningún registro se elimina físicamente de la base de datos. Al "suprimir" un elemento, su estado cambia a Suprimido y deja de mostrarse en las listas. Esto preserva la trazabilidad histórica requerida por el proceso CNA.'],
                        ['icono'=>'bi-arrow-repeat','titulo'=>'Flujo de aprobación de evidencias','texto'=>'Las evidencias siguen el ciclo: Borrador → En revisión → Aprobada / Rechazada. Solo el Líder de Característica puede aprobar o rechazar; si rechaza, la evidencia regresa a Borrador para ser corregida por el Enlace.'],
                        ['icono'=>'bi-eye-slash','titulo'=>'Visibilidad según rol','texto'=>'Cada usuario solo ve la información que le corresponde según su rol y las características o aspectos que tiene asignados. Si no ves un recurso, es probable que no tengas asignación o permiso para ello.'],
                        ['icono'=>'bi-person-badge','titulo'=>'Cambio de rol automático','texto'=>'Cuando se asigna a un usuario como responsable de un Factor, el sistema le otorga el rol Director. Si se le asigna una Característica, recibe el rol Líder de Característica.'],
                        ['icono'=>'bi-shield-lock','titulo'=>'Accesos denegados','texto'=>'Si intentas realizar una acción sin el permiso necesario, el sistema te redirigirá al inicio con un aviso. Contacta al administrador si consideras que el acceso debería habilitarse para tu rol.'],
                    ];
                    @endphp
                    <div class="d-flex flex-column gap-3">
                        @foreach($reglas as $r)
                        <div style="display:flex;gap:14px;align-items:flex-start;">
                            <div style="width:36px;height:36px;border-radius:9px;background:var(--primary-light);
                                        border:1px solid var(--primary-border);display:flex;align-items:center;
                                        justify-content:center;flex-shrink:0;">
                                <i class="bi {{ $r['icono'] }}" style="color:var(--primary);font-size:16px;"></i>
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:var(--gray-900);margin-bottom:3px;">{{ $r['titulo'] }}</div>
                                <div style="font-size:12px;color:var(--gray-600);line-height:1.6;">{{ $r['texto'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="gf-card">
                    <div class="gf-card-title"><i class="bi bi-people"></i> Permisos por rol</div>
                    @php
                    $roles = [
                        ['nombre'=>'Administrador','color'=>'var(--primary)','bg'=>'var(--primary-light)','border'=>'var(--primary-border)','icono'=>'bi-shield-fill-check','permisos'=>['Acceso total a todos los módulos','Gestión de usuarios y roles','Consulta de auditoría completa','Crear factores y toda la estructura']],
                        ['nombre'=>'Director de Programa','color'=>'#5B2D8E','bg'=>'#F3EEFF','border'=>'#C9A8F0','icono'=>'bi-mortarboard-fill','permisos'=>['Lectura de toda la estructura CNA','Crear y suprimir factores y sub-niveles','Dashboard global como el administrador','Sin acceso a gestión de usuarios']],
                        ['nombre'=>'Director','color'=>'#1A6B3A','bg'=>'#EAF3DE','border'=>'#97C459','icono'=>'bi-person-workspace','permisos'=>['Lectura de factores y sub-niveles propios','Solo ve lo asignado a su factor','Sin acceso de escritura en factores']],
                        ['nombre'=>'Líder de Característica','color'=>'#633806','bg'=>'var(--warning-bg)','border'=>'var(--warning-border)','icono'=>'bi-person-check-fill','permisos'=>['Crear y editar aspectos de sus características','Cargar, revisar y aprobar evidencias','Registrar y editar resultados de su ámbito']],
                        ['nombre'=>'Enlace','color'=>'var(--gray-600)','bg'=>'var(--gray-50)','border'=>'var(--gray-100)','icono'=>'bi-person','permisos'=>['Cargar evidencias en sus aspectos asignados','Registrar resultados propios','Ver aspectos y evidencias según asignación']],
                    ];
                    @endphp
                    <div class="d-flex flex-column gap-3">
                        @foreach($roles as $rol)
                        <div style="border:1px solid {{ $rol['border'] }};border-radius:10px;background:{{ $rol['bg'] }};padding:12px 14px;">
                            <div style="display:flex;align-items:center;margin-bottom:8px;">
                                <i class="bi {{ $rol['icono'] }}" style="color:{{ $rol['color'] }};font-size:15px;margin-right:8px;"></i>
                                <span style="font-size:13px;font-weight:700;color:{{ $rol['color'] }};">{{ $rol['nombre'] }}</span>
                            </div>
                            <ul style="margin:0;padding-left:18px;list-style:disc;">
                                @foreach($rol['permisos'] as $p)
                                <li style="font-size:12px;color:var(--gray-600);line-height:1.7;">{{ $p }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         PESTAÑA 3 · SOPORTE Y FAQ
    ══════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="soporte" role="tabpanel">

        {{-- Tarjeta de contacto --}}
        <div class="gf-card" style="background:var(--primary);border-color:var(--primary-dark);margin-bottom:24px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,0.15);
                            border:2px solid rgba(255,255,255,0.25);display:flex;align-items:center;
                            justify-content:center;flex-shrink:0;">
                    <i class="bi bi-headset" style="font-size:24px;color:#fff;"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:2px;">¿Necesitas ayuda?</div>
                    <div style="font-size:13px;color:var(--primary-border);line-height:1.5;">
                        Comunícate con el administrador del sistema. Incluye tu nombre completo, rol y una descripción del problema.
                    </div>
                    <a href="mailto:administrador@ucatolica.edu.co?subject=Soporte%20Evidentia"
                       style="display:inline-flex;align-items:center;gap:6px;margin-top:10px;
                              font-size:13px;font-weight:600;color:#fff;text-decoration:none;
                              background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);
                              padding:6px 14px;border-radius:8px;">
                        <i class="bi bi-envelope-fill"></i> administrador@ucatolica.edu.co
                    </a>
                </div>
            </div>
        </div>

        {{-- FAQ --}}
        <div class="gf-card">
            <div class="gf-card-title"><i class="bi bi-chat-left-dots"></i> Preguntas frecuentes</div>
            <div class="accordion accordion-flush" id="faqAccordion">
                @php
                $faqs = [
                    ['id'=>'faq1','q'=>'¿Por qué no veo algún módulo del menú?','r'=>'El menú se adapta a tu rol. Si no ves Factores, Características u otras secciones, tu rol no tiene acceso a ese nivel. Contacta al administrador si crees que debería habilitarse.'],
                    ['id'=>'faq2','q'=>'¿Puedo recuperar un elemento suprimido?','r'=>'Los elementos suprimidos no se eliminan físicamente. Solo el administrador puede restaurar el estado de un registro. Escribe al correo de soporte indicando qué necesitas recuperar.'],
                    ['id'=>'faq3','q'=>'¿Qué hago si mi evidencia fue rechazada?','r'=>'Cuando una evidencia es rechazada, vuelve al estado Borrador. Puedes editarla, corregir lo indicado por el Líder y volver a enviarla al flujo de revisión.'],
                    ['id'=>'faq4','q'=>'¿Cómo solicito un cambio de rol?','r'=>'Escribe a administrador@ucatolica.edu.co indicando tu nombre, rol actual y el rol que necesitas. El administrador realizará el ajuste.'],
                    ['id'=>'faq5','q'=>'¿Por qué no puedo seleccionar ciertas evidencias al crear un resultado?','r'=>'Solo aparecen las evidencias con estado Aprobada y que pertenecen a los aspectos de tu ámbito. Si una evidencia no aparece, verifica que esté aprobada o que el aspecto esté asignado a ti.'],
                    ['id'=>'faq6','q'=>'Accedo a una ruta directamente y me redirige al inicio, ¿por qué?','r'=>'El sistema aplica control de acceso por rol. Si intentas acceder a un módulo para el que no tienes permiso, serás redirigido al inicio con un aviso. Esto es una medida de seguridad, no un error.'],
                ];
                @endphp
                @foreach($faqs as $faq)
                <div class="accordion-item" style="border:none;border-bottom:1px solid var(--gray-50);">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $faq['id'] }}"
                                style="font-size:12px;font-weight:600;color:var(--gray-900);
                                       background:transparent;padding:12px 4px;box-shadow:none;">
                            {{ $faq['q'] }}
                        </button>
                    </h2>
                    <div id="{{ $faq['id'] }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body"
                             style="font-size:12px;color:var(--gray-600);line-height:1.7;padding:0 4px 14px;">
                            {{ $faq['r'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>{{-- fin tab-content --}}

@push('scripts')
<script>
// Estilo activo de las pestañas
document.querySelectorAll('#ayudaTabs .nav-link').forEach(function(btn) {
    btn.addEventListener('shown.bs.tab', function () {
        document.querySelectorAll('#ayudaTabs .nav-link').forEach(function(b) {
            b.style.color         = 'var(--gray-600)';
            b.style.borderBottom  = '3px solid transparent';
        });
        btn.style.color        = 'var(--primary)';
        btn.style.borderBottom = '3px solid var(--primary)';
    });
});
// Activar estilo inicial
document.getElementById('manual-tab').style.color        = 'var(--primary)';
document.getElementById('manual-tab').style.borderBottom = '3px solid var(--primary)';
</script>
@endpush

@endsection
