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
                <div class="heading">
                    <h3 class="title">Datos Personales</h3>
                    <p class="desc">Ingresa el nombre completo del cliente para su correcta identificación en nuestros sistemas.</p>
                </div>
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
                <div class="heading">
                    <h3 class="title">Detalles de contacto</h3>
                    <p class="desc">Detalles de contacto necesarios para establecer comunicación con el cliente.</p>
                </div>
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
                <div class="heading">
                    <h3 class="title">Detalles de Orden</h3>
                    <p class="desc">Registra todos los detalles de la orden para facilitar su seguimiento en caso de ser requerido posteriormente.</p>
                </div>
                <div class="fields separator">
                    <fieldset class="input">
                        <label for="field-currency">Moneda</label>
                        <select name="currency" id="field-currency">
                            <option value="PEN">Sol Peruano</option>
                            <option value="EUR">Euro</option>
                            <option value="USD">Dolares Estado Unidendes</option>
                            <option value="CHF">Franco Suizo</option>
                            <option value="CLP">Peso Chileno</option>
                            <option value="MXN">Peso Mexicano</option>
                        </select>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-payment">Precio de venta</label>
                        <input type="number" id="field-payment" name="payment" value="0" required>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-expenditure">Precio de compra</label>
                        <input type="number" id="field-expenditure" name="expenditure" value="0">
                    </fieldset>
                </div>
                <div class="fields">
                    <fieldset class="input">
                        <label for="field-payment-method">Método de pago</label>
                        <select name="payment-method" id="field-payment-method">
                            <option value="YAPE">Yape</option>
                            <option value="PLIN">Plin</option>
                            <option value="CASH">Efectivo</option>
                            <option value="PAYPAL">PayPal</option>
                            <option value="BT_BCP">Transferencia BCP</option>
                            <option value="BT_BBVA">Transferencia BBVA</option>
                            <option value="BT_INTERBANK">Transferencia Interbank</option>
                        </select>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-payment-description">Descripción del Pago</label>
                        <input type="text" id="field-payment-description" name="payment-description" required>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-limit-activations">Limite de activaciones</label>
                        <input type="number" id="field-limit-activations" name="limit-activations" value="10">
                    </fieldset>
                </div>
            </div>
        </section>
        <section id="tab-finder" class="content">{{finder}}</section>
        <section id="tab-settings" class="content">{{settings}}</section>
    </div>
</main>