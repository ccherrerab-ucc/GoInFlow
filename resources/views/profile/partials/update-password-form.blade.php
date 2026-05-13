<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="d-flex flex-column gap-3">

        <div>
            <label class="gf-label" for="update_password_current_password">Contraseña actual</label>
            <input id="update_password_current_password"
                   name="current_password"
                   type="password"
                   class="gf-input"
                   autocomplete="current-password">
            @if($errors->updatePassword->has('current_password'))
                <div class="gf-field-error">{{ $errors->updatePassword->first('current_password') }}</div>
            @endif
        </div>

        <div>
            <label class="gf-label" for="update_password_password">Nueva contraseña</label>
            <input id="update_password_password"
                   name="password"
                   type="password"
                   class="gf-input"
                   autocomplete="new-password">
            @if($errors->updatePassword->has('password'))
                <div class="gf-field-error">{{ $errors->updatePassword->first('password') }}</div>
            @endif
        </div>

        <div>
            <label class="gf-label" for="update_password_password_confirmation">Confirmar nueva contraseña</label>
            <input id="update_password_password_confirmation"
                   name="password_confirmation"
                   type="password"
                   class="gf-input"
                   autocomplete="new-password">
            @if($errors->updatePassword->has('password_confirmation'))
                <div class="gf-field-error">{{ $errors->updatePassword->first('password_confirmation') }}</div>
            @endif
        </div>

    </div>

    <div class="mt-4">
        <button type="submit" class="gf-btn gf-btn-outline">
            <i class="bi bi-shield-lock"></i> Actualizar contraseña
        </button>
    </div>

</form>
