<main class="auth-wrap container">
    <div class="inside">
        <h1 class="title">Inciar sesión</h1>
        <form id="ews-auth-login" method="post">
            <section id="auth-credentials">
                <fieldset id="auth-username" class="field text">
                    <i class="icon material-icons">person_outline</i>
                    <input id="input-username" class="input" type="text" name="username" placeholder="Nombre de usuario">
                </fieldset>
                <fieldset id="auth-password" class="field password">
                    <i class="icon material-icons">password</i>
                    <input id="input-password" class="input" type="password" name="password" placeholder="Contraseña">
                    <button type="button" class="toggle-password toggle-password" data-input="input-password" aria-label="Mostrar contraseña">
                        <i class="icn material-icons">visibility_off</i>
                    </button>
                </fieldset>
            </section>
            <section>
                <div id="auth-message" class="message">Usa un nombre de usuario y una contraseña válidos para acceder al sistema. Dispones de 5 intentos; tras el quinto intento fallido esta página quedará bloqueada.</div>
            </section>
            <section class="submit-box">
                <div>
                    <button id="auth-submit-login" type="submit" class="button button-primary submit">Ingresar</button>
                </div>
                <div>
                    <a href="/auth/recover_password" class="link">¿Olvidaste tu contraseña?</a>
                </div>
            </section>
        </form>
    </div>
</main>