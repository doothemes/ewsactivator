(function($){

    // EWS namespace
    var EWS = {};

    $(document).ready(function(){
        EWS.Header();
        EWS.AuthLogin();
        EWS.AuthRecoveryPassword();
        EWS.AdminTabs();
        EWS.AdminRegisterActivatorHelper();
        EWS.AdminRegisterActivator();
    });

    EWS.Header = function(){
        let lastScrollTop = 0;
        let scrollTimeout = null;
        let isSticky = true;
        const tolerance = 15; // píxeles mínimos para evitar parpadeos en móviles
        const animDuration = 150; // duración de la animación en ms
        const $header = $("header.navigator");
        // Manejador de scroll
        $(window).on("scroll touchmove", function () {
            const currentScroll = $(this).scrollTop();
            // Ignora movimientos pequeños
            if(Math.abs(currentScroll - lastScrollTop) < tolerance) return;
            // Cancelamos cualquier animación previa
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                if (currentScroll > lastScrollTop && isSticky) {
                // Scroll hacia abajo → desaparecer suavemente
                $header.stop(true, true).animate(
                    { top: "-100px", opacity: 0 },
                    animDuration,
                    "swing",
                    function () {
                    $header.removeClass("sticky"); // se quita después de la animación
                    }
                );
                isSticky = false;
                } else if (currentScroll < lastScrollTop && !isSticky) {
                    // Scroll hacia arriba → volver a aparecer suavemente
                    $header.addClass("sticky").css({ top: "-100px", opacity: 0 }).stop(true, true).animate(
                        { top: 0, opacity: 1 },
                        animDuration,
                        "swing"
                    );
                    isSticky = true;
                }
                lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
            }, 50); // pequeño debounce para mayor suavidad
        });
    }

    EWS.AdminRegisterActivator = function(){

        var $registerForm = "#ews-admin-register-license";

        function formResetter(){
            let resetTimer;
            const form = $($registerForm);
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
        
        $(document).on("submit", $registerForm, function (e){
            e.preventDefault();
            const form = $(this);
            const Button = form.find('button[type="submit"]');
            const ButtonText = Button.text();
            
            Button.prop("disabled", true).text("Registrando..");
            form.find("div.oder-summary").addClass("onload");

            $.ajax({
                url: ews_app.ajax_url+"license_creator",
                type: "POST",
                dataType: "json",
                data: form.serialize(),
                success: function(response){
                   
                    if(response.success == true){

                    }else{

                    }

                    $("#target-finder").trigger("click");

                    formResetter();

                    Button.prop("disabled", false).text(ButtonText);
                }
            });
        });

        // Manejador de reseteo del formulario
        $(document).on("reset", $registerForm, function (e){
            // Prevenir el reseteo por defecto
            e.preventDefault();
            // Llamar a la función de reseteo personalizada
            formResetter();
        });
    }

    EWS.AdminRegisterActivatorHelper = function(){
        // Precios base de los productos
        const ProductPrices = {
            office: 45,
            windows: 65
        };
        // Límites de descuento
        const minDiscountPercent = 0;
        const maxDiscountPercent = 25; 
        // Double-tap detection variables
        let lastTap = 0;
        // Control para habilitar/deshabilitar descuentos
        let discountsEnabled = true; // ← cambia a false para desactivar
        // Precio inicial del pedido
        let priceOrder = 0;

        // Acción común para resetear el producto
        function triggerProductReset(btn, product) {
            let btnTimer;
            btn.addClass("fadeout");
            clearTimeout(btnTimer);
            btnTimer = setTimeout(function() {
                $("#field-product-" + product)
                    .val("")
                    .trigger("change");
                btn.removeClass("fadeout");
            }, 300);
        }

        // Restablecer descuentos a 0%
        function resetDiscounts() {
            let resetTimer;
            const $fieldset = $("#fieldset-payment");
            const $order = $("#order-details");
            // Aplicamos efecto visual de reseteo
            $order.addClass("onload");
            $fieldset.removeClass("shake");
            clearTimeout(resetTimer);
            resetTimer = setTimeout(function () {
                // Reiniciar valores visuales
                $("#summary-discount").text("- 0.00");
                $("#discount-percentage").text("0%");
                $("#field-total-discount").val("0");
                // Restablecer el total al subtotal actual
                const subtotal = parseFloat($("#summary-subtotal").text()) || 0;
                $("#summary-total").text(subtotal.toFixed(2));
                $("#field-payment").val(subtotal.toFixed(2));
                // Quitar efecto visual de "onload"
                $order.removeClass("onload");
                // Calcular la posición para centrar el fieldset
                const fieldsetTop = $fieldset.offset().top;
                const fieldsetHeight = $fieldset.outerHeight();
                const windowHeight = $(window).height();
                const scrollTo = fieldsetTop - (windowHeight / 2) + (fieldsetHeight / 2);
                // Scroll suave al centro
                $("html, body").stop(true).animate({ scrollTop: scrollTo }, 500, "swing", function () {
                    // Luego del scroll, aplicamos shake y focus
                    $fieldset.addClass("shake").find("input[type=number]").focus();
                });
            }, 350);
        }

        // Resaltar un fieldset específico
        function highligthtFieldset(fieldsetId, inputType = "number") {
            let highlightTimer;
            const $fieldset = $("#fieldset-" + fieldsetId);
            const fieldsetTop = $fieldset.offset().top;
            const fieldsetHeight = $fieldset.outerHeight();
            const windowHeight = $(window).height();
            // Calculamos el scroll ideal para centrar el elemento
            const scrollTo = fieldsetTop - (windowHeight / 2) + (fieldsetHeight / 2);
            // Animamos el scroll al centro
            $("html, body").stop(true).animate({ scrollTop: scrollTo }, 500, "swing");
            // Efecto visual
            $fieldset.addClass("fadein").removeClass("shake");
            clearTimeout(highlightTimer);
            highlightTimer = setTimeout(function () {
              $fieldset.removeClass("fadein").find(`input[type=${inputType}]`).focus();
            }, 350);
        }
          
        // Actualización de precios y resumen del pedido
        function updatePrice(){
            const officeVal = $("#field-product-office").val();
            const windowsVal = $("#field-product-windows").val();
            // Mostrar u ocultar productos en el resumen
            $("#order-product-office").toggleClass("hidden", !officeVal);
            $("#order-product-windows").toggleClass("hidden", !windowsVal);
            // Calcular subtotal
            let subtotal = 0;
            if (officeVal) subtotal += ProductPrices.office;
            if (windowsVal) subtotal += ProductPrices.windows;
            // Variables de descuento
            let discountPercent = 0;
            let discountValue = 0;
            // Aplicar descuento aleatorio si corresponde
            if (discountsEnabled && officeVal && windowsVal) {
                discountPercent = parseFloat(
                    (Math.random() * (maxDiscountPercent - minDiscountPercent) + minDiscountPercent).toFixed(2)
                );
                // Asegurarnos de no pasarnos del límite permitido
                if (discountPercent > maxDiscountPercent) {
                    discountPercent = maxDiscountPercent;
                }
                discountValue = parseFloat(((subtotal * discountPercent) / 100).toFixed(2));
            }
            // Calcular total final
            const total = parseFloat((subtotal - discountValue).toFixed(2));
            // Actualizar resumen visual
            $("#summary-subtotal").text(subtotal.toFixed(2));
            $("#summary-discount").text(`- ${discountValue.toFixed(2)}`);
            $("#discount-percentage").text(`${discountPercent}%`);
            $("#field-subtotal").val(subtotal.toFixed(2));
            $("#field-total-discount").val(discountValue.toFixed(2));
            $("#summary-total").text(total.toFixed(2));
            // Guardar y mostrar total
            priceOrder = total;
            $("#field-payment").val(total.toFixed(2));
        }

        // Actualizar precio de Office al cambiar selección
        $("#field-product-office").on("change", function() {
            const slc = $(this);
            const val = slc.val();
            const txt = slc.find("option:selected").text();
            const ord = $("#order-product-office");
            ord.find(".name").text(txt);
            ord.find(".product-price").text(ProductPrices.office.toFixed(2));
            updatePrice();
        });

        // Actualizar precio de Windows al cambiar selección
        $("#field-product-windows").on("change", function() {
            const slc = $(this);
            const val = slc.val();
            const txt = slc.find("option:selected").text();
            const ord = $("#order-product-windows");
            ord.find(".name").text(txt);
            ord.find(".product-price").text(ProductPrices.windows.toFixed(2));
            updatePrice();
        });

        // Actualizar moneda si cambia
        $("#field-currency").on("change", function(){
            const currency = $(this).val();
            $(".currency-badge").text(currency);
            localStorage.setItem("admin_currency_badge", currency);
        });

        // Actualizar método de pago si cambia
        $("#field-payment-method").on("change", function(){
            const paymentMethod = $(this).val();
            localStorage.setItem("admin_payment_method", paymentMethod);
        });

        // Detectar cambio o escritura manual del campo de pago
        $("#field-payment").on("input change", function() {
            let value = parseFloat($(this).val()) || 0;
            // Calcular subtotal actual
            let subtotal = 0;
            const officeVal = $("#field-product-office").val();
            const windowsVal = $("#field-product-windows").val();
            if (officeVal) subtotal += ProductPrices.office;
            if (windowsVal) subtotal += ProductPrices.windows;
            if (subtotal === 0) return;
            // Evitar que el pago sea mayor al subtotal
            if(value > subtotal){
                value = subtotal;
                $(this).val(subtotal.toFixed(2));
            }
            // Calcular descuento real
            let discountValue = parseFloat((subtotal - value).toFixed(2));
            let discountPercent = parseFloat(((discountValue / subtotal) * 100).toFixed(2));
            // Aplicar límite máximo de descuento
            if (discountPercent > 100) {
                discountPercent = 100;
                discountValue = parseFloat(((subtotal * discountPercent) / 100).toFixed(2));
                value = subtotal - discountValue;
                $(this).val(value.toFixed(2));
            }
            // Actualizar resumen visual
            $("#summary-subtotal").text(subtotal.toFixed(2));
            $("#summary-discount").text(`- ${discountValue.toFixed(2)}`);
            $("#discount-percentage").text(`${discountPercent}%`);
            $("#field-subtotal").val(subtotal.toFixed(2));
            $("#field-total-discount").val(discountValue.toFixed(2));
            $("#summary-total").text(value.toFixed(2));
        });

        // Si hay doble click resaltar el fieldset de pago
        $("#card-total").on("dblclick", function() {
            highligthtFieldset("payment");
        });

        // Soporte movil, Si hay doble tap resaltar el fieldset de pago
        $("#card-total").on("touchstart", function(e){
            const now = Date.now();
            if(now - lastTap < 300){
                highligthtFieldset("payment");
            }
            lastTap = now;
        });

        // Si hay doble click quitar los descuentos
        $("#card-discount").on("dblclick", function() {
            resetDiscounts();
        });

        // Soporte movil, Si hay doble tap quitar los descuentos
        $("#card-discount").on("touchstart", function(e){
            const now = Date.now();
            if(now - lastTap < 300){
                resetDiscounts();
            }
            lastTap = now;
        });

        // Si hay doble click quitar el producto seleccionado
        $(".button-product").on("dblclick", function() {
            const btn = $(this);
            const product = btn.data("product");
            triggerProductReset(btn, product);
        });

        // Soporte movil, Si ahy doble tap quitar el producto seleccionado
        $(".button-product").on("touchstart", function(e) {
            const now = Date.now();
            const btn = $(this);
            const product = btn.data("product");
            if(now - lastTap < 300){
                e.preventDefault(); // evita zoom o scroll no deseado
                triggerProductReset(btn, product);
            }
            lastTap = now;
        });
    }

    EWS.AdminTabs = function(){
        var $tabButtons = $(".tab");
        var $tabContents = $(".content");
        // Manejador de clic en los botones de pestañas
        $(document).on("click", ".tab", function(e){
            // Evitar comportamiento predeterminado
            e.preventDefault();
            // Obtener el objetivo de la pestaña seleccionada
            var target = $(this).data("target");
            // Guardar la pestaña seleccionada en localStorage
            localStorage.setItem("admin_active_tab", target);
            // Activar el botón de pestaña seleccionado
            $tabButtons.removeClass("on");
            $(this).addClass("on");
            // Mostrar el contenido de la pestaña seleccionada
            $tabContents.removeClass("on fadein");
            $("#tab-"+target).addClass("on fadein");
        });
    };


    EWS.AuthLogin = function(){

        // Cerrar sesión
        $(document).on("click", ".auth-logout-link", function (e){
            e.preventDefault();
            $.ajax({
                url: ews_app.ajax_url+"auth_logout",
                type: "POST",
                dataType: "json"
            }).done(function(response){
                if(response.success == true){
                    window.location.href = ews_app.base_url+"auth/login";
                }
            });
            return false;
        });

        // Mostrar / Ocultar contraseña
        $(document).on("click", ".toggle-password", function () {
            const $btn = $(this);
            const $icon = $btn.find("i");
            const $idata = $btn.data("input");
            const $input = $("#"+$idata);
            // Alternar tipo de input y estado del ícono
            if ($input.attr("type") === "password") {
                // Mostrar contraseña
                $input.attr("type", "text");
                $icon.text("visibility");
                $btn.attr("aria-label", "Ocultar contraseña");
            } else {
                // Ocultar contraseña
                $input.attr("type", "password");
                $icon.text("visibility_off");
                $btn.attr("aria-label", "Mostrar contraseña");
            }
            // Efecto visual rápido al presionar
            $btn.addClass("active");
            setTimeout(() => $btn.removeClass("active"), 150);
        });
        
        // Manejador de envío del formulario de login
        $(document).on("submit", "#ews-auth-login", function (e){
            e.preventDefault();
            var AuthTimer;
            var AuthBtnTimer;
            const form = $(this);
            const Button = form.find('button[type="submit"]');
            const ButtonText = Button.text();
            const MessageText = $("#auth-message").text();
            const InputUsername = form.find("#input-username").val();
            const InputPassword = form.find("#input-password").val();
            // Reestablecer animaciones
            form.find(".shake").removeClass("shake");
            form.find(".jump").removeClass("jump");
            Button.prop('disabled', true).text('Procesando..');
            // Validar campos obligatorios
            if(!InputUsername || !InputPassword){
                // Animar campos vacíos
                $("#auth-credentials").addClass("shake");
                clearTimeout(AuthTimer);
                AuthTimer = setTimeout(function(){
                    Button.prop('disabled', false).text(ButtonText);
                }, 500);
                return;
            }else{
                // Enviar datos al servidor
                $.ajax({
                    url: ews_app.ajax_url+"auth_login",
                    type: "POST",
                    dataType: "json",
                    data: form.serialize()
                }).done(function(response){
                    // Login exitoso
                    if(response.success == true){
                        window.location.href = ews_app.base_url+"admin/dashboard";
                        localStorage.setItem("login_user", response.data.username);
                    }
                }).fail(function(jqXHR, textStatus, errorThrown){
                    let msg = 'Error desconocido.';
                    let fld = false;
                    try {
                        const json = JSON.parse(jqXHR.responseText);
                        msg = json.message || json.error || msg;
                        fld = json.field;
                    } catch(e) {
                        msg = jqXHR.responseText || errorThrown || textStatus;
                    }
                    if(fld != false){
                        $("#auth-"+fld).addClass("shake").find("input").focus();
                    }else{
                        $("#auth-credentials").addClass("shake");
                    }
                    // Mostrar mensaje de error
                    $("#auth-message").addClass("jump").text(msg);
                    // Reestablecer estado del mensaje
                    clearTimeout(AuthTimer);
                    AuthTimer = setTimeout(function(){
                        $("#auth-message").removeClass("jump").text(MessageText);
                    }, 2500);
                }).always(function(){
                    // Reestablecer estado del botón
                    clearTimeout(AuthBtnTimer);
                    AuthBtnTimer = setTimeout(function(){
                        Button.prop('disabled', false).text(ButtonText);
                    }, 2500);
                });
            }
            return false;
        });
    }


    EWS.AuthRecoveryPassword = function(){
        // Elementos principales del flujo
        var $WrapRecoverPass = $("#auth-recover-password");
        var $WrapValidateOTP = $("#auth-validate-otp");
        var $WrapUpdatePass = $("#auth-update-password");
        var $GetOTPCode = new URLSearchParams(window.location.search).get("otp");
        var $GetUsername = new URLSearchParams(window.location.search).get("username");
        // Si se recibe el nombre de usuario por URL, rellenar el campo
        if($GetUsername){
            $("#input-username").val($GetUsername);
            $("#auth-password").addClass("jump").find("input").focus();
        }
        // Si se recibe el código OTP por URL, ir directamente a la validación
        if($GetOTPCode){
            $WrapRecoverPass.addClass("hidden");
            $WrapUpdatePass.addClass("hidden");
            $WrapValidateOTP.removeClass("hidden");
        }
        // Iniciar Proceso, este manejador genera un código OTP y lo envía al usuario
        $(document).on("submit", "#ews-auth-recover-password", function (e){
            e.preventDefault();
            var FormTimer;
            const form = $(this);
            const Button = form.find('button[type="submit"]');
            const ButtonText = Button.text();
            const NoticeDiv = form.find(".message");
            const NoticeText = NoticeDiv.text();
            $("#auth-username").removeClass("shake");
            Button.prop('disabled', true).text('Procesando..');
            $.ajax({
                url: ews_app.ajax_url+"auth_recover_password",
                type: "POST",
                dataType: "json",
                data: form.serialize(),
                success: function(response){
                    if(response.success == true){
                        // Continuar al siguiente paso
                        $WrapRecoverPass.addClass("hidden");
                        $WrapValidateOTP.removeClass("hidden").addClass("jump");
                        // Establecer Campos ocultos
                        $(".hidden-username").val(response.data.username);
                        $(".hidden-userkey").val(response.data.userkey);
                        // Almacenar en localStorage
                        localStorage.setItem("auth_username", response.data.username);
                        localStorage.setItem("auth_userkey", response.data.userkey);

                    }else{
                        // Animar campo con error
                        $("#auth-username").addClass("shake").find("input").focus();
                        // Mostrar mensaje de error
                        NoticeDiv.text(response.message);
                        // Reestablecer estado del formulario
                        clearTimeout(FormTimer);
                        FormTimer = setTimeout(function(){
                            // Reestablecer mensaje
                            NoticeDiv.text(NoticeText);
                            // Reestablecer botón
                            Button.prop('disabled', false).text(ButtonText);
                        }, 2500);
                    }
                }
            });
            return false;
        });

        // Este manejador valida el código OTP ingresado por el usuario
        $(document).on("submit", "#ews-auth-validate-otp", function (e){
            e.preventDefault();
            var FormTimer;
            const form = $(this);
            const Button = form.find('button[type="submit"]');
            const ButtonText = Button.text();
            const NoticeDiv = form.find(".message");
            const NoticeText = NoticeDiv.text();
            const UserName = localStorage.getItem("auth_username");
            const UserKey = localStorage.getItem("auth_userkey");
            $("#auth-otp").removeClass("shake");
            Button.prop('disabled', true).text('Procesando..');
            $.ajax({
                url: ews_app.ajax_url+"auth_validate_otp",
                type: "POST",
                dataType: "json",
                data: form.serialize(),
                success: function(response){
                    if(response.success == true){
                        // Continuar al siguiente paso
                        $WrapValidateOTP.addClass("hidden");
                        $WrapUpdatePass.removeClass("hidden").addClass("jump");
                    }else{
                        // Animar campo con error                           
                        $("#auth-otp").addClass("shake").find("input").focus();
                        // Mostrar mensaje de error
                        NoticeDiv.text(response.message);
                        // Reestablecer estado del formulario
                        clearTimeout(FormTimer);
                        FormTimer = setTimeout(function(){
                            // Reestablecer mensaje
                            NoticeDiv.text(NoticeText);
                            // Reestablecer botón
                            Button.prop('disabled', false).text(ButtonText);
                        }, 2500);
                    }
                }
            });
            return false;
        });

        // Completar el proceso de recuperación de contraseña
        $(document).on("submit", "#ews-auth-update-password", function (e){
            e.preventDefault();
            var FormTimer;
            const form = $(this);
            const Button = form.find('button[type="submit"]');
            const ButtonText = Button.text();
            const NoticeDiv = form.find(".message");
            const NoticeText = NoticeDiv.text();
            const UserName = localStorage.getItem("auth_username");
            $("#auth-password1").removeClass("shake");
            $("#auth-password2").removeClass("shake");
            Button.prop('disabled', true).text('Procesando..');
            $.ajax({
                url: ews_app.ajax_url+"auth_change_password",
                type: "POST",
                dataType: "json",
                data: form.serialize(),
                success: function(response){
                    if(response.success == true){
                        // Restablecer localStorage
                        localStorage.removeItem("auth_username");
                        localStorage.removeItem("auth_userkey");
                        // Redirigir al login con mensaje de éxito
                        window.location.href = ews_app.base_url+`auth/login?reset=success&username=${UserName}`;
                    }else{
                        // Animar campo con error
                        $("#auth-password1").addClass("shake").find("input").focus();
                        $("#auth-password2").addClass("shake").find("input").focus();
                        // Mostrar mensaje de error
                        NoticeDiv.text(response.message);
                        // Reestablecer estado del formulario
                        clearTimeout(FormTimer);
                        FormTimer = setTimeout(function(){
                            // Reestablecer mensaje
                            NoticeDiv.text(NoticeText);
                            // Reestablecer botón
                            Button.prop('disabled', false).text(ButtonText);
                        }, 2500);
                    }
                }
            });
            return false;
        });
    }
})(jQuery);