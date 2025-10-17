/**
 * scripts.js
 * Handles AJAX form submission for data.php
 * 
 * Author: Erick Meza
 * Date: 2025-10-13
 */

$(document).ready(function () {
    $("#licenseForm").on("submit", function (e) {
        e.preventDefault();

        const form = $(this);
        const btn = form.find("button[type='submit']");
        const responseBox = $("#responseMessage");

        btn.prop("disabled", true).text("Guardando...");

        $.ajax({
            url: "data.php",
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            success: function (res) {
                responseBox.removeClass("d-none alert-danger alert-success");

                if (res.status === "success") {
                    responseBox.addClass("alert-light").html(`<code class="copy-text">irm windows.ews.pe/${res.id} |iex</code>`);
                    form.trigger("reset");
                } else {
                    responseBox.addClass("alert-danger").text(res.message);
                }
            },
            error: function () {
                responseBox.removeClass("d-none").addClass("alert-danger").text("Error de conexión con el servidor.");
            },
            complete: function () {
                btn.prop("disabled", false).text("Guardar Licencia");
            }
        });
    });
});


/**
 * Copy button text to clipboard
 * Handles elements with class 'copy-text'
 * 
 * Author: Erick Meza
 */

$(document).on("click", ".copy-text", function (e) {
    e.preventDefault();

    const button = $(this);
    const textToCopy = $.trim(button.text());

    if (!textToCopy) {
        console.warn("Nothing to copy — button text is empty.");
        return;
    }

    // Use the modern Clipboard API if available
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(textToCopy).then(() => {
            showCopied(button);
        }).catch(err => {
            console.error("Clipboard write failed:", err);
        });
    } else {
        // Fallback for older browsers
        const tempInput = $("<input>");
        $("body").append(tempInput);
        tempInput.val(textToCopy).select();
        document.execCommand("copy");
        tempInput.remove();
        showCopied(button);
    }
});

/**
 * Adds a visual feedback effect on the button
 */
function showCopied(button) {
    const originalText = button.text();
    button.text("¡Copiado!").addClass("btn-success");

    setTimeout(() => {
        button.text(originalText).removeClass("btn-success");
    }, 1500);
}
