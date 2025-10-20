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
                <li><a href="#" id="target-downloads" data-target="downloads" class="tab">Descargas</a></li>
            </ul>
        </div>
        <div class="right">
            <div class="profile">
                <h3><?= $_SESSION['ews_auth']['fullname'] ?? 'John Doe' ?></h3>
            </div>
            <div class="control">
                <a href="#" class="auth-logout-link">Salir</a>
            </div>
        </div>
    </header>
    <!-- Inside / Content -->
    <div class="inside">
        <!-- Register Section -->
        <section id="tab-register" class="content">
            <?php include EWS_VIEWS_PATH . '/admin/sections/register.php'; ?>
        </section>

        <!-- Finder Section -->
        <section id="tab-finder" class="content">
            <?php include EWS_VIEWS_PATH . '/admin/sections/finder.php'; ?>
        </section>

        <!-- Settings Section -->
        <section id="tab-settings" class="content">{{settings}}</section>

        <!-- Settings Downloads -->
        <section id="tab-downloads" class="content">{{downloads}}</section>
    </div>
    <footer class="dasboard">
        <div class="copy">
            <div class="logo">
                <img src="/assets/svg/ews.svg" alt="EWS Networks">
            </div>
            <div class="text">
                <small>© <?=date('Y'); ?>, Version: EWSActivator@<?=EWS_VERSION?></small>
            </div>
        </div>
    </footer>
</main>