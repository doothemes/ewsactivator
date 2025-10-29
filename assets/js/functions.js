/**
 * Convierte una fecha y hora UTC a cualquier zona horaria establecida desde {ews_app.timezone}.
 * @param {string|Date} datetime - Fecha y hora en formato UTC.
 * @returns {string} - Fecha y hora convertida en formato 'YYYY-MM-DD HH:MM:SS'.
 */
function convertTime(datetime){
    try {
        const date = new Date(datetime);
        const options = {
            timeZone: ews_app.timezone,
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
            hour12: false
        };
        const formatter = new Intl.DateTimeFormat("en-CA", options);
        const parts = formatter.formatToParts(date);
        const values = Object.fromEntries(parts.map(p => [p.type, p.value]));
        return `${values.year}-${values.month}-${values.day} ${values.hour}:${values.minute}:${values.second}`;
    } catch (e) {
        console.error("Invalid datetime provided:", datetime, e);
        return "";
    }
}

/**
 * Converts a UTC date to a "time ago" format adjusted to Lima, Peru timezone.
 * @param {string|Date} dateInput - UTC date string or Date object.
 * @returns {string} Human readable relative time in Spanish.
 */
function timeAgoLima(dateInput) {
    // Parse input as Date object
    const date = new Date(dateInput);
  
    // Get current time in Lima (UTC-5)
    const now = new Date();
    const limaOffset = -5 * 60; // UTC-5 in minutes
    const localNow = new Date(now.getTime() + (now.getTimezoneOffset() + limaOffset) * 60000);
    const localDate = new Date(date.getTime() + (date.getTimezoneOffset() + limaOffset) * 60000);
    // Calculate difference in seconds
    const diff = Math.floor((localNow - localDate) / 1000);
    if (diff < 0) return "En el futuro 👀";
    if (diff < 5) return "Justo ahora";
    if (diff < 60) return `hace ${diff}s`;
    const minutes = Math.floor(diff / 60);
    if (minutes < 60) return `hace ${minutes}m`;
    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `hace ${hours}h`;
    const days = Math.floor(hours / 24);
    if (days < 7) return `hace ${days}d`;
    const weeks = Math.floor(days / 7);
    if (weeks < 4) return `hace ${weeks} semana${weeks > 1 ? "s" : ""}`;
    const months = Math.floor(days / 30);
    if (months < 12) return `hace ${months} mes${months > 1 ? "es" : ""}`;
    const years = Math.floor(days / 365);
    return `hace ${years} año${years > 1 ? "s" : ""}`;
}

/**
 * Actualiza todos los elementos con la clase "time" para mostrar el tiempo transcurrido en formato "hace X".
 * Utiliza la función timeAgoLima para el cálculo.
 */
function updateTimes() {
    $(".time").each(function(){
        const dateTime = $(this).data("time");
        if(dateTime){
            $(this).text(timeAgoLima(dateTime));
        }
    });
}
  


/**
 * Establece los valores predeterminados de los campos del formulario de registro.
 * @param {string} form
 * @param {object} defaults - Objeto con los valores predeterminados.
 */
function formResetter(formID){
    let resetTimer;
    const form = $(formID);
    // Efecto visual de reseteo
    form.find("div.oder-summary").addClass("onload");
    // Restablecer después de un segundo
    clearTimeout(resetTimer);
    resetTimer = setTimeout(function() {
        // Reiniciar todos los campos del formulario
        form.find("input[type=text], input[type=email], input[type=hidden]").val("");
        form.find("input[name=total_payment], input[name=total_expenditure]").val("0.00");
        // Reiniciar selector de productos
        form.find("select[name=microsoft_office").val("").trigger("change");
        form.find("select[name=microsoft_windows").val("").trigger("change");
        // Restablecer valores visibles de resumen
        $("#summary-subtotal").text("0.00");
        $("#summary-discount").text("- 0.00");
        $("#discount-percentage").text("0%");
        $("#summary-total").text("0.00");
        // Quitar efecto visual
        form.find("div.oder-summary").removeClass("onload");
    }, 500);
}

/**
 * Compone la URL de Gravatar a partir del hash MD5 del correo electrónico.
 * @param {string} md5Hash 
 * @returns 
 */
function getGravatarURL(md5Hash) {
    return `https://www.gravatar.com/avatar/${md5Hash}?s=90&d=https%3A%2F%2Fi.imgur.com%2FgK1XylW.png`;
}

/**
 * Genera la vista detallada de una orden y sus comentarios
 * 
 * @param {object} data - Datos de la orden y comentarios.
 */
function generateViewResult(data){
    // --- Extraer datos principales ---
    var $products = data.products || [];
    var $comments = data.comments || [];
    var $office_edition = data.office_edition || null;
    var $windows_edition = data.windows_edition || null;
    // --- Contenedores principales ---
    const orderContainer = $("#view-result-order");
    const commentsContainer = $("#view-result-comments");
    // --- Generar vista de comentarios ---
    let comments_HTML = "";
    let comment_COUNT = 0;
    // Compilar vista de comentarios
    if($comments.length){
        $comments.forEach(comment => {
            comment_COUNT += 1;
            comments_HTML += `
                <div id="commnet_${comment.uid}" data-uid="${comment.uid}" class="comment-item delete-comment">
                    <div class="avatar ${comment.status}">
                        <i class="icon material-icons">${comment.icon}</i>
                    </div>
                    <div class="content">
                        <div class="author" data-ip="${comment.ip_address}">
                            <span class="name" data-username="${comment.username}">${comment.fullname}</span> 
                            <span class="time" data-time="${comment.date}">${timeAgoLima(comment.date)}</span>
                        </div>
                        <div class="text">${comment.comment}</div>
                    </div>
                </div>
            `;
        });
    }
    // Determinar encabezados según cantidad de productos
    let productHeading = "Producto adquirido";
    let productLabel = "Producto relacionado";
    // Ajustar si hay más de un producto
    if($products.length >= 2){
        productHeading = "Productos adquiridos";
        productLabel = "Productos relacionados";
    }
    // Compilar lista de productos
    const products = [];
    // Compilar vista de windows
    if($windows_edition){
        products.push(`<span class="value">${ews_app.microsoft_windows[$windows_edition]}</span>`);
    }
    // Compilar vista de office
    if($office_edition){
        products.push(`<span class="value">${ews_app.microsoft_office[$office_edition]}</span>`);
    }
    // Combinar productos en HTML
    let productsHTML = products.join("");
    // --- Generar vista de orden ---
    let order_HTML = `
        <div class="view-order-details">
            <div class="vod-box">
                <h3 class="title">
                    <i class="material-icons">person</i>
                    <span>Información del cliente</span>
                </h3>
                <div class="view-data">
                    <div class="item">
                        <span class="label">Nombre Completo</span>
                        <span class="value">${data.firstname || "John"} ${data.lastname || "Doe"}</span>
                    </div>
                    <div class="item">
                        <span class="label">Correo electrónico</span>
                        <span class="value">${data.email || "john@unknown.com"}</span>
                    </div>
                    <div class="item">
                        <span class="label">Número telefónico</span>
                        <span class="value">${data.phone || "+00 000 000 000"}</span>
                    </div>
                </div>
            </div>
            <div class="vod-box">
                <h3 class="title">
                    <i class="material-icons">token</i>
                    <span>${productHeading}</span>
                </h3>
                <div class="view-data">
                    <div class="item">
                        <span class="label">${productLabel}s</span>
                        <div class="products">
                            ${productsHTML || `<span class="value">Orden sin productos</span>`}
                        </div>
                    </div>
                    <div class="item">
                        <span class="label">Límite de activaciones</span>
                        <span class="value activations">
                            <div><b id="ews-activation-count">${data.count_activations || "0"}</b> | <b id="ews-activation-limit">${data.limit_activations || "0"}</b></div>
                            <div class="activation-control">
                                <button type="button" class="ews-activation-control" data-id="${data.id}" data-operation="add">+</button>
                                <button type="button" class="ews-activation-control" data-id="${data.id}" data-operation="subtract">-</button>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
            <div class="vod-box">
                <h3 class="title">
                    <i class="material-icons">key</i>
                    <span>Clave de licencia</span>
                </h3>
                <button data-command="irm ${window.location.origin}/c/${data.id} |iex" class="view-data-key copy-command">
                    <div class="left">
                        <code class="license-key">${data.id}</code>
                        <p class="wrn">Acumula <strong>${(data.count_requests+1)}</strong> peticiones API registradas.</p>
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
                        <span class="value"><b>${data.payment_method}:</b> <code>${data.payment_description || "ninguno"}</code></span>
                    </div>
                    <div class="item">
                        <span class="label">Fecha de registro</span>
                        <span class="value">${convertTime(data.created)}</span>
                    </div>
                    <div class="item">
                        <span class="label">Última actualización</span>
                        <span class="value time" data-time="${data.updated}">${timeAgoLima(data.updated)}</span>
                    </div>
                </div>
            </div>
            <div class="vod-box">
                <div class="view-data order">
                    <div class="order-data subtotal">
                        <span class="label">Subtotal</span>
                        <span class="value">${data.subtotal.toFixed(2) || "0.00"} <small class="currency-badge">${data.currency || "NA"}</small></span>
                    </div>
                    <div class="order-data discount">
                        <span class="label">Descuento aplicado</span>
                        <span class="value">-${data.total_discount.toFixed(2) || "0.00"} <small class="currency-badge">${data.currency || "NA"}</small></span>
                    </div>
                    <div class="order-data total">
                        <span class="label">Total</span>
                        <span class="value total-payment">${data.total_payment.toFixed(2) || "0.00"} <small class="currency-badge">${data.currency || "NA"}</small></span>
                    </div>
                </div>
            </div>
            <div class="vod-box">
                <h3 class="title">
                    <i class="material-icons">attach_money</i>
                    <span>Resumen de venta</span>
                </h3>
                <div class="view-data order">
                    <div class="order-data expenditure">
                        <span class="label">Registro de gastos</span>
                        <span class="value">${data.total_expenditure.toFixed(2) || "0.00"} <small class="currency-badge">${data.currency || "NA"}</small></span>
                    </div>
                    <div class="order-data profit total">
                        <span class="label">Ganancia</span>
                        <span class="value total-profit">+${data.total_profit.toFixed(2) || "0.00"} <small class="currency-badge">${data.currency || "NA"}</small></span>
                    </div>
                </div>
            </div>
        </div>
    `;
    // --- Generar vista de comentarios ---
    let comment_HTML = `
        <h3 class="heading">
            <div class="text">
                <i class="material-icons">mode_comment</i>
                <span>Notas y comentarios (<b id="comment-counter">${comment_COUNT}</b>)</span>
            </div>
            <div class="controls">
                <a id="update-order-information" href="#" data-key="${data.id}" class="button button-secondary">Actualizar datos</a>
            </div>
        </h3>
        <div class="comments-form">
            <form id="ews-admin-post-comment-license" class="post-comment-form" data-order="${data.id}">
                <div class="comments-notices hidden">{{notice_comment}}</div>
                <div class="writer-side">
                    <div class="comment-writer">
                        <textarea id="comment-text" name="comment_txt" class="comment-text autoheight" placeholder="Escribir comentario.."></textarea>
                        <input type="hidden" name="license_uid" value="${data.id}">
                        <input type="hidden" name="license_sct" value="${data.secret}">
                        <input type="hidden" name="license_cll" value="${data.collectionId}">
                    </div>
                    <div class="comment-button">
                        <button id="submit-comment" type="submit" rows="1" class="submit-comment">
                            <i class="material-icons">send</i>
                        </button>
                    </div>
                </div>
                <div class="status-side">
                    <div class="indicator">
                        <label class="commnet-status checked none">
                            <span class="status-none">Normal</span>
                            <input type="radio" name="comment_status" value="none" checked>
                        </label>
                        <label class="commnet-status success">
                            <span class="status-success">Éxito</span>
                            <input type="radio" name="comment_status" value="success">
                        </label>
                        <label class="commnet-status info">
                            <span class="status-info">Info</span>
                            <input type="radio" name="comment_status" value="info">
                        </label>
                        <label class="commnet-status warning">
                            <span class="status-warning">Advertencia</span>
                            <input type="radio" name="comment_status" value="warning">
                        </label>
                        <label class="commnet-status error">
                            <span class="status-error">Error</span>
                            <input type="radio" name="comment_status" value="error">
                        </label>
                    </div>
                    <div id="character-counter" class="character-counter">0</div>
                </div>
            </form>
        </div>
        <div id="comments-list-${data.id}" class="comments-list">${comments_HTML || `<div class="no-comments">No hay comentarios para mostrar.</div>`}</div>
    `;
    // --- Insertar en el DOM ---
    orderContainer.removeClass("onload").html(order_HTML);
    commentsContainer.removeClass("onload").html(comment_HTML);
}
