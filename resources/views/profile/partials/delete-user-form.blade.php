<p style="font-size:12px;color:var(--gray-600);line-height:1.6;margin-bottom:16px;">
    Una vez eliminada tu cuenta, todos los datos asociados se perderán de forma permanente.
    Asegúrate de querer continuar antes de confirmar.
</p>

<button type="button"
        class="gf-btn gf-btn-danger"
        data-bs-toggle="modal"
        data-bs-target="#modalDeleteAccount">
    <i class="bi bi-trash3"></i> Eliminar mi cuenta
</button>

{{-- Modal de confirmación --}}
<div class="modal fade" id="modalDeleteAccount" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content" style="border-radius:14px;border:none;
                     box-shadow:0 8px 32px rgba(12,68,124,0.18);overflow:hidden;">

            {{-- Header --}}
            <div style="background:var(--danger-bg);border-bottom:1px solid var(--danger-border);
                         padding:20px 24px 16px;display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:50%;background:var(--danger-border);
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-trash3" style="font-size:18px;color:var(--danger-text);"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--danger-text);">
                        Eliminar cuenta
                    </div>
                    <div style="font-size:12px;color:var(--danger-text);opacity:.8;">
                        Esta acción no se puede deshacer
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <form method="post" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                @csrf
                @method('delete')

                <div style="padding:20px 24px;">
                    <p style="font-size:13px;color:var(--gray-600);margin-bottom:16px;line-height:1.6;">
                        Ingresa tu contraseña actual para confirmar la eliminación definitiva de tu cuenta.
                    </p>
                    <label class="gf-label" for="delete_password">Contraseña</label>
                    <input id="delete_password"
                           name="password"
                           type="password"
                           class="gf-input"
                           placeholder="Tu contraseña actual"
                           autocomplete="current-password">
                    @if($errors->userDeletion->has('password'))
                        <div class="gf-field-error mt-1">{{ $errors->userDeletion->first('password') }}</div>
                    @endif
                </div>

                {{-- Footer --}}
                <div style="padding:0 24px 20px;display:flex;justify-content:flex-end;gap:10px;">
                    <button type="button"
                            class="gf-btn gf-btn-outline"
                            style="height:36px;"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="gf-btn gf-btn-danger" style="height:36px;">
                        <i class="bi bi-trash3"></i> Confirmar eliminación
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('modalDeleteAccount')).show();
});
</script>
@endpush
@endif
