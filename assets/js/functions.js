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