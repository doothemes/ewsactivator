<main class="auth-wrap container">
    
    <!-- Recover Password Section -->
    <div id="auth-recover-password" class="inside">
        <h1 class="title">Restablecer contraseña</h1>
        <form id="ews-auth-recover-password" method="post" disabled>
            <section id="auth-credentials">
                <fieldset id="auth-username" class="field text">
                    <i class="icon material-icons">person_outline</i>
                    <input id="input-username" class="input" type="text" name="username" placeholder="Nombre de usuario o Correo electrónico">
                </fieldset>
            </section>
            <section>
                <div id="auth-message-recover-password" class="message">Para comenzar con el proceso, necesitamos verificar tu correo electrónico. En breve recibirás una <strong>clave de seis dígitos</strong> que deberás ingresar para continuar.</div>
            </section>
            <section class="submit-box">
                <div>
                    <button id="auth-submit-recover-password" type="submit" class="button button-primary submit">Enviar código</button>
                </div>
                <div>
                    <a href="/auth/login" class="link">Iniciar sesión</a>
                </div>
            </section>
        </form>
    </div>

    <!-- Validate OTP Section -->
    <div id="auth-validate-otp" class="inside hidden">
        <h1 class="title">Validar OTP</h1>
        <form id="ews-auth-validate-otp" method="post">
            <section id="auth-credentials-validate-otp">
                <fieldset id="auth-otp" class="field text">
                    <i class="icon material-icons">dialpad</i>
                    <input id="input-otp" class="input otp" type="number" name="otp" placeholder="Código de verificación" value="<?= $_GET['otp'] ?? ''; ?>">
                </fieldset>
            </section>
            <section>
                <div id="auth-message-validate-otp" class="message">Para comenzar con el proceso, necesitamos verificar tu correo electrónico. En breve recibirás una <strong>clave de seis dígitos</strong> que deberás ingresar para continuar.</div>
            </section>
            <section class="submit-box">
                <div>
                    <button id="auth-submit-validate-otp" type="submit" class="button button-primary submit">Validar código</button>
                </div>
                <div>
                    <a href="/auth/recover_password" class="link">Solicitar nuevo código</a>
                </div>
            </section>
            <input type="hidden" class="hidden-username" name="username">
            <input type="hidden" class="hidden-userkey" name="userkey">
        </form>
    </div>

    <!-- Update Password Section -->
    <div id="auth-update-password" class="inside hidden">
        <h1 class="title">Actualizar contraseña</h1>
        <form id="ews-auth-update-password" method="post">
            <section id="auth-credentials-update-password">
                <fieldset id="auth-password1" class="field password">
                    <i class="icon material-icons">password</i>
                    <input id="input-password1" class="input" type="password" name="password1" placeholder="Nueva contraseña">
                    <button type="button" class="toggle-password toggle-password" data-input="input-password1" aria-label="Mostrar contraseña">
                        <i class="icn material-icons">visibility_off</i>
                    </button>
                </fieldset>
                <fieldset id="auth-password2" class="field password">
                    <i class="icon material-icons">password</i>
                    <input id="input-password2" class="input" type="password" name="password2" placeholder="Confirmar contraseña">
                    <button type="button" class="toggle-password toggle-password" data-input="input-password2" aria-label="Mostrar contraseña">
                        <i class="icn material-icons">visibility_off</i>
                    </button>
                </fieldset>
            </section>
            <section>
                <div id="auth-message-update-password" class="message">Actualiza tu contraseña. Te recomendamos usar una combinación de letras, números y símbolos para garantizar una mayor seguridad.</div>
            </section>
            <section class="submit-box">
                <div>
                    <button id="auth-submit-update-password" type="submit" class="button button-primary submit">Actualizar contraseña</button>
                </div>
                <div>
                    <a href="/auth/login" class="link" onclick="return confirm('¿Realmente quieres cancelar el restablecimiento de tu contraseña?');">Iniciar sesión</a>
                </div>
            </section>
            <input type="hidden" class="hidden-username" name="username">
            <input type="hidden" class="hidden-userkey" name="userkey">
        </form>
    </div>
</main>