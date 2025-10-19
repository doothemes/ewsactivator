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
                <h2>Registrar nueva activación</h2>
                <p>Utilice el siguiente formulario para registrar una nueva licencia de producto de Microsoft.</p>
            </div>
            <div class="group">
                <div class="heading">
                    <h3 class="title">Datos Personales</h3>
                    <p class="desc">Ingresa el nombre completo del cliente para su correcta identificación en nuestros sistemas.</p>
                </div>
                <div class="fields">
                    <fieldset class="input">
                        <label for="field-firstname">Nombre</label>
                        <input type="text" id="field-firstname" name="firstname" required>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-lastname">Apellido</label>
                        <input type="text" id="field-lastname" name="lastname" required>
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
                        <label for="field-email-address">Correo Electrónico</label>
                        <input type="email" id="field-email-address" name="email-address" required>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-phone-number">Número de teléfono</label>
                        <input type="text" id="field-phone-number" name="phone-number" required>
                    </fieldset>
                </div>
            </div>
            <div class="group">
                <div class="header">
                    <h2>Seleccionar Producto(s)</h2>
                    <p class="desc">Puedes seleccionar mas de un producto, el sistema sugerira un precio de venta.</p>
                </div>
                <div class="fields">
                    <fieldset class="input">
                        <label for="field-product-office">Microsoft Office</label>
                        <select name="microsoft_office" data-identity="a43269512d7ad1034031f913a61944ba" id="field-product-office">
                            <option value="">Seleccionar edición</option>
                            <optgroup label="Office 2016">
                                <option value="OFFICE_HOME_STUDENT_2016">Office Home & Student 2016</option>
                                <option value="OFFICE_HOME_BUSSINESS_2016">Office Home & Business 2016</option>
                                <option value="OFFICE_PRO_2016">Office Professional 2016</option>
                                <option value="OFFICE_PRO_PLUS_2016">Office Professional Plus 2016</option>
                                <option value="OFFICE_STANDARD_2016">Office Standard 2016</option>
                                <option value="OFFICE_EDUCATION_2016">Office Education 2016</option>
                                <option value="OFFICE_365_2016">Office 365 (2016)</option>
                            </optgroup>
                            <optgroup label="Office 2019">
                                <option value="OFFICE_HOME_STUDENT_2019">Office Home & Student 2019</option>
                                <option value="OFFICE_HOME_BUSSINESS_2019">Office Home & Business 2019</option>
                                <option value="OFFICE_PRO_2019">Office Professional 2019</option>
                                <option value="OFFICE_PRO_PLUS_2019">Office Professional Plus 2019</option>
                                <option value="OFFICE_STANDARD_2019">Office Standard 2019</option>
                                <option value="OFFICE_LTSC_STANDARD_2019">Office LTSC Standard 2019</option>
                                <option value="OFFICE_LTSC_PRO_PLUS_2019">Office LTSC Professional Plus 2019</option>
                                <option value="OFFICE_365_2019">Office 365 (2019)</option>
                            </optgroup>
                            <optgroup label="Office 2021">
                                <option value="OFFICE_HOME_STUDENT_2021">Office Home & Student 2021</option>
                                <option value="OFFICE_HOME_BUSSINESS_2021">Office Home & Business 2021</option>
                                <option value="OFFICE_PRO_2021">Office Professional 2021</option>
                                <option value="OFFICE_LTSC_STANDARD_2021">Office LTSC Standard 2021</option>
                                <option value="OFFICE_LTSC_PRO_PLUS_2021">Office LTSC Professional Plus 2021</option>
                                <option value="OFFICE_FOR_MAC_2021">Office 2021 for Mac</option>
                                <option value="MICROSOFT_365_2021">Microsoft 365 (2021)</option>
                            </optgroup>
                            <optgroup label="Office 2024">
                                <option value="OFFICE_HOME_STUDENT_2024">Office Home & Student 2024</option>
                                <option value="OFFICE_HOME_BUSSINESS_2024">Office Home & Business 2024</option>
                                <option value="OFFICE_PRO_2024">Office Professional 2024</option>
                                <option value="OFFICE_LTSC_STANDARD_2024">Office LTSC Standard 2024</option>
                                <option value="OFFICE_LTSC_PRO_PLUS_2024">Office LTSC Professional Plus 2024</option>
                                <option value="OFFICE_FOR_MAC_2024">Office 2024 for Mac</option>
                                <option value="MICROSOFT_365_2024">Microsoft 365 (2024)</option>
                            </optgroup>
                            <optgroup label="Microsoft 365">
                                <option value="MICROSOFT_365_PERSONAL">Microsoft 365 Personal</option>
                                <option value="MICROSOFT_365_FAMILY">Microsoft 365 Family</option>
                                <option value="MICROSOFT_365_BUSSINESS_BASIC">Microsoft 365 Business Basic</option>
                                <option value="MICROSOFT_365_BUSSINESS_STANDARD">Microsoft 365 Business Standard</option>
                                <option value="MICROSOFT_365_BUSSINESS_PREMIUM">Microsoft 365 Business Premium</option>
                                <option value="MICROSOFT_365_APPS_BUSSINESS">Microsoft 365 Apps for Business</option>
                                <option value="MICROSOFT_365_APPS_ENTERPRISE">Microsoft 365 Apps for Enterprise</option>
                                <option value="MICROSOFT_365_EDUCATION">Microsoft 365 Education</option>
                                <option value="MICROSOFT_365_GOVERNMENT">Microsoft 365 Government</option>
                                <option value="MICROSOFT_365_NONPROFIT">Microsoft 365 Nonprofit</option>
                                <option value="MICROSOFT_365_E3_E5">Microsoft 365 E3 / E5</option>
                            </optgroup>
                        </select>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-product-windows">Microsoft Windows</label>
                        <select name="microsoft_windows" data-identity="9ce3f67202fa6adfae56987cb5ec7ed0" id="field-product-windows">
                            <option value="">Seleccionar edición</option>
                            <optgroup label="Windows 11">
                                <option value="WINDOWS_11_HOME">Windows 11 Home</option>
                                <option value="WINDOWS_11_PRO">Windows 11 Pro</option>
                                <option value="WINDOWS_11_PRO_WORKSTATION">Windows 11 Pro for Workstations</option>
                                <option value="WINDOWS_11_PRO_EDUCATION">Windows 11 Pro Education</option>
                                <option value="WINDOWS_11_EDUCATION">Windows 11 Education</option>
                                <option value="WINDOWS_11_ENTERPRISE">Windows 11 Enterprise</option>
                                <option value="WINDOWS_11_ENTERPRISE_LTSC">Windows 11 Enterprise LTSC</option>
                                <option value="WINDOWS_11_SE">Windows 11 SE</option>
                                <option value="WINDOWS_11_IOT_ENTERPRISE">Windows 11 IoT Enterprise</option>
                                <option value="WINDOWS_11_N_KN">Windows 11 N / KN</option>
                            </optgroup>
                            <optgroup label="Windows 10">
                                <option value="WINDOWS_10_HOME">Windows 10 Home</option>
                                <option value="WINDOWS_10_PRO">Windows 10 Pro</option>
                                <option value="WINDOWS_10_PRO_WORKSTATION">Windows 10 Pro for Workstations</option>
                                <option value="WINDOWS_10_PRO_EDUCATION">Windows 10 Pro Education</option>
                                <option value="WINDOWS_10_EDUCATION">Windows 10 Education</option>
                                <option value="WINDOWS_10_ENTERPRISE">Windows 10 Enterprise</option>
                                <option value="WINDOWS_10_ENTERPRISE_LTSC">Windows 10 Enterprise LTSC</option>
                                <option value="WINDOWS_10_IOT_ENTERPRISE">Windows 10 IoT Enterprise</option>
                                <option value="WINDOWS_10_N_KN">Windows 10 N / KN</option>
                            </optgroup>
                        </select>
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
                            <optgroup label="Fiat ">
                                <option value="EUR">EUR - Euro</option>
                                <option value="USD">USD - Dolar</option>
                                <option value="PEN">PEN - Sol Peruano</option>
                                <option value="CHF">CHF - Franco Suizo</option>
                                <option value="CLP">CLP - Peso Chileno</option>
                                <option value="MXN">MXN - Peso Mexicano</option>
                            </optgroup>
                            <optgroup label="Criptomonedas">
                                <option value="BTC">BTC - Bitcoin</option>
                                <option value="ETH">ETH - Ethereum</option>
                                <option value="USDT">USDT - Tether</option>
                                <option value="USDC">USDC - USD Coin</option>
                                <option value="BNB">BNB - Binance Coin</option>
                            </optgroup>
                        </select>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-payment">Precio de venta</label>
                        <input type="number" id="field-payment" name="payment" placeholder="0.00" required>
                        <span class="currency-badge">PEN</span>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-expenditure">Precio de compra</label>
                        <input type="number" id="field-expenditure" name="expenditure" placeholder="0.00">
                        <span class="currency-badge">PEN</span>
                    </fieldset>
                </div>
                <div class="fields">
                    <fieldset class="input">
                        <label for="field-payment-method">Método de pago</label>
                        <select name="payment-method" id="field-payment-method">
                            <option value="CASH">Efectivo</option>
                            <option value="YAPE">Yape</option>
                            <option value="PLIN">Plin</option>
                            <option value="PAYPAL">PayPal</option>
                            <option value="BINANCE">Binance</option>
                            <option value="BT_BCP">Transferencia BCP</option>
                            <option value="BT_BBVA">Transferencia BBVA</option>
                            <option value="BT_INTERBANK">Transferencia Interbank</option>
                        </select>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-payment-description">Descripción del Pago</label>
                        <input type="text" id="field-payment-description" name="payment-description" placeholder="PD00001" required>
                    </fieldset>
                    <fieldset class="input">
                        <label for="field-limit-activations">Limite de activaciones</label>
                        <input type="number" id="field-limit-activations" name="limit-activations" min="1" max="10" value="10">
                    </fieldset>
                </div>
            </div>
            <div class="oder-summary">
                <h3 class="title">Resumen de la orden</h3>
                <div class="products">
                    <button id="order-product-office" data-product="office" class="item fadein hidden button-product">
                        <div class="item-details">
                            <div class="logo">
                                <img src="/assets/svg/office.svg" alt="Office">
                            </div>
                            <div class="name">{{product_editition_text}}</div>
                        </div>
                        <div class="item-totals">
                            <span class="product-price">{{unit_price}}</span>
                            <span class="currency-badge">{{currency}}</span>
                        </div>
                    </button>
                    <button id="order-product-windows" data-product="windows" class="item fadein hidden button-product">
                        <div class="item-details">
                            <div class="logo">
                                <img src="/assets/svg/windows.svg" alt="windows">
                            </div>
                            <div class="name">{{product_editition_text}}</div>
                        </div>
                        <div class="item-totals">
                            <span class="product-price">{{unit_price}}</span>
                            <span class="currency-badge">{{currency}}</span>
                        </div>
                    </button>
                </div>
                <div class="details">
                    <div class="card-price">
                        <div class="amount">
                            <span id="summary-subtotal">0.00</span>
                            <span class="currency-badge">{{currency}}</span>
                        </div>
                        <div class="desc">Subtotal</div>
                    </div>
                    <div class="card-price">
                        <div class="amount">
                            <span id="summary-discount">- 0.00</span>
                            <span class="currency-badge">{{currency}}</span>
                        </div>
                        <div class="desc">Descuento <span id="discount-percentage">0%</span></div>
                    </div>
                    <div class="card-price">
                        <div class="amount">
                            <span id="summary-total">0.00</span>
                            <span class="currency-badge">{{currency}}</span>
                        </div>
                        <div class="desc">Total</div>
                    </div>
                </div>
            </div>
            <div class="controls">
                <div class="btns">
                    <button class="button button-primary" type=submit id="register-submit">Completar orden</button> 
                    <button class="button button-secondary" type=reset id="register-reset">Cancelar</button>
                </div>
                <div class="ntce">
                    <p class="notice">Asegúrate de que todos los datos sean correctos. Si detectas algún error, comunícate con nosotros para actualizar la orden.</p>
                </div>
            </div>
        </section>
        <section id="tab-finder" class="content">{{finder}}</section>
        <section id="tab-settings" class="content">{{settings}}</section>
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