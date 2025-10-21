<div class="search-box">
    <input id="search-input" type="text" class="search-input" name="keyword" placeholder="Buscar licencias" />
    <button id="search-button" class="search-button">
        <i class="material-icons">search</i>
    </button>
</div>


<div class="response">

    <div class="view-order-details">
        <div class="vod-box">
            <h3 class="title">
                <i class="material-icons">person</i>
                <span>Información del cliente</span>
            </h3>
            <div class="view-data">
                <div class="item">
                    <span class="label">Nombre Completo</span>
                    <span class="value">Erick Meza</span>
                </div>
                <div class="item">
                    <span class="label">Correo electrónico</span>
                    <span class="value">emeza@ews.pe</span>
                </div>
                <div class="item">
                    <span class="label">Número telefónico</span>
                    <span class="value">+51 933 585 544</span>
                </div>
            </div>
        </div>
        <div class="vod-box">
            <h3 class="title">
                <i class="material-icons">token</i>
                <span>Información del producto(s)</span>
            </h3>
            <div class="view-data">
                <div class="item">
                    <span class="label">Productos relacionados</span>
                    <div class="products">
                        <span class="value">Office Professional Plus 2019</span>
                        <span class="value">Windows 11 Pro</span>
                    </div>
                </div>
                <div class="item">
                    <span class="label">Activaciones</span>
                    <span class="value"><b>0</b> ativaciones de <b>10</b> disponibles</span>
                </div>
            </div>
        </div>

        <div class="vod-box">
            <h3 class="title">
                <i class="material-icons">key</i>
                <span>Clave de licencia</span>
            </h3>
            <button data-command="irm https://windows.ews.pe/c/302e34d398d0266cf24c021c53154dd8 |iex" class="view-data-key">
                <div class="left">
                    <code class="license-key">302e34d398d0266cf24c021c53154dd8</code>
                    <p class="wrn">Guarda esta clave en un lugar seguro.</p>
                </div>
                <div class="right">
                    <span>Copiar Comando</span>
                </div>
            </button>
        </div>

        <div class="vod-box">
            <h3 class="title">
                <i class="material-icons">text_snippet</i>
                <span>Resumen de orden</span>
            </h3>
            <div class="view-data">
                <div class="item">
                    <span class="label">Descripción del pago</span>
                    <span class="value"><b>YAPE:</b> <code>3221460012</code></span>
                </div>
                <div class="item">
                    <span class="label">Fecha de registro</span>
                    <span class="value">2025-10-16 14:18:04</span>
                </div>
                <div class="item">
                    <span class="label">Última actualización</span>
                    <span class="value">2025-10-21 02:23:28</span>
                </div>
            </div>
        </div>

        <div class="vod-box">
            <div class="view-data order">
                <div class="order-data subtotal">
                    <span class="label">Subtotal</span>
                    <span class="value">110.00 <small class="currency-badge">PEN</small></span>
                </div>
                <div class="order-data discount">
                    <span class="label">Descuento aplicado</span>
                    <span class="value">-20.64 <small class="currency-badge">PEN</small></span>
                </div>
                <div class="order-data total">
                    <span class="label">Total</span>
                    <span class="value">89.36 <small class="currency-badge">PEN</small></span>
                </div>
            </div>
        </div>
    </div>

    <div class="view-order-comments">
        {{comentarios}}
    </div>

</div>


<div class="results"></div>