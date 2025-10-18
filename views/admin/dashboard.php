<main class="admin-wrap container">
    
    <!-- Header / Navigator -->
    <header class="navigator">
        <div class="left">
            <div class="logo">
                <img src="/assets/svg/microsoft.svg" alt="microsoft">
            </div>
            <div class="subtitle">
                <h1>Activator</h1>
            </div>
        </div>
        <div class="center">
            <ul class="menu">
                <li><a href="#" id="target-register" data-target="register" class="tab">Registrar</a></li>
                <li><a href="#" id="target-finder" data-target="finder" class="tab">Consultar</a></li>
                <li><a href="#" id="target-settings" data-target="settings" class="tab">Ajustes</a></li>
            </ul>
        </div>
        <div class="right">
            <div class="profile">
                <h3>Erick Meza</h3>
            </div>
            <div class="control">
                <a href="#" class="auth-logout-link">Salir</a>
            </div>
        </div>
    </header>

    <!-- Inside / Content -->
    <div class="inside">
        <section id="tab-register" class="content">
            <div class="header">
                <h2>Registrar Nueva Licencia</h2>
                <p>Utilice el siguiente formulario para registrar una nueva licencia de producto de Microsoft.</p>
            </div>
            <div class="group">
                <h3 class="title">Datos Personales</h3>
                <div class="fields">
                    <fieldset class="input">
                        <label for="field-name">Nombre</label>
                        <input type="text" id="field-name" name="name" required>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-surname">Apellido</label>
                        <input type="text" id="field-surname" name="surname" required>
                    </fieldset>
                </div>
            </div>
            <div class="group">
                <h3 class="title">Detalles de contacto</h3>
                <div class="fields">
                    <fieldset class="input">
                        <label for="field-email">Correo Electrónico</label>
                        <input type="email" id="field-email" name="email" required>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-phone">Número de teléfono</label>
                        <input type="text" id="field-phone" name="phone" required>
                    </fieldset>
                </div>
            </div>
            <div class="group">
                <h3 class="title">Detalles de Orden</h3>
            </div>
        </section>
        <section id="tab-finder" class="content">{{finder}}</section>
        <section id="tab-settings" class="content">{{settings}}</section>
    </div>
</main>