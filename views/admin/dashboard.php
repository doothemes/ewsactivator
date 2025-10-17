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
        <section id="tab-register" class="content">{{register}}</section>
        <section id="tab-finder" class="content">{{finder}}</section>
        <section id="tab-settings" class="content">{{settings}}</section>
    </div>
</main>