(function($){

    // EWS namespace
    var EWS = {};

    $(document).ready(function(){
        EWS.AuthLogin();
        EWS.AuthRecoveryPassword();
        EWS.AdminTabs();
        EWS.AdminRegisterLicense();
    });

    EWS.AdminRegisterLicense = function(){
        // Precios base de los productos
        const ProductPrices = {
            office: 45.00,
            windows: 65.00
        };
        // Double-tap detection variables
        let lastTap = 0;
        // Control para habilitar/deshabilitar descuentos
        let discountsEnabled = true; // ← cambia a false para desactivar
        // Precio inicial del pedido
        let priceOrder = 0;

        // Acción común para resetear el producto
        function triggerProductReset(btn, product) {
            let btnTimer;
            btn.addClass("jump");
            clearTimeout(btnTimer);
            btnTimer = setTimeout(function() {
                $("#field-product-" + product)
                    .val("")
                    .trigger("change");
                btn.removeClass("jump");
            }, 800);
        }

        // Actualización de precios y resumen del pedido
        function updatePrice() {
            // Obtener valores seleccionados
            const officeVal = $("#field-product-office").val();
            const windowsVal = $("#field-product-windows").val();
            const DiscountInitial = 7.99;
            const DiscountFinal = 20.99;
            // Mostrar u ocultar productos en el resumen
            $("#order-product-office").toggleClass("hidden", !officeVal);
            $("#order-product-windows").toggleClass("hidden", !windowsVal);
            // Restablecer precios
            let newPrice = 0;
            // Agregar precios de productos seleccionados
            if(officeVal) newPrice += ProductPrices.office;
            if(windowsVal) newPrice += ProductPrices.windows;
            // Inicializar variables de descuento
            let discountPercent = 0;
            let discountValue = 0;
            // Aplicar descuento si ambos productos están seleccionados
            if(discountsEnabled && officeVal && windowsVal){
                // Generar porcentaje de descuento aleatorio
                discountPercent = parseFloat((Math.random() * (DiscountFinal - DiscountInitial) + DiscountInitial).toFixed(2));
                // Calcular valor del descuento
                discountValue = parseFloat(((newPrice * discountPercent) / 100).toFixed(2));
                // Sustraer descuento del precio total
                newPrice -= discountValue;
            }
            // Mostrar el monto subtotal antes del descuento
            $("#summary-subtotal").text((newPrice + discountValue).toFixed(2));
            // Actualizar si hubo cambios en el precio
            if(newPrice !== priceOrder) {
                priceOrder = parseFloat(newPrice.toFixed(2));
                $("#field-payment").val(priceOrder.toFixed(2));
                // Si el descuento está habilitado y se aplicó, mostrarlo
                if(discountsEnabled && discountPercent > 0){
                    $("#summary-discount").text(`- ${discountValue.toFixed(2)}`);
                    $("#discount-percentage").text(`${discountPercent}%`);
                }else{
                    $("#summary-discount").text("- 0.00");
                    $("#discount-percentage").text("0%");
                }
                // Mostrar el precio total actualizado
                $("#summary-total").text(priceOrder.toFixed(2));
            }
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

        $(document).on("click", ".toggle-password", function () {
            const $btn = $(this);
            const $icon = $btn.find("i");
            const $idata = $btn.data("input");
            const $input = $("#"+$idata);
        
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

            

            form.find(".shake").removeClass("shake");
            form.find(".jump").removeClass("jump");
            Button.prop('disabled', true).text('Procesando..');

            if(!InputUsername || !InputPassword){
                $("#auth-credentials").addClass("shake");
                clearTimeout(AuthTimer);
                AuthTimer = setTimeout(function(){
                    Button.prop('disabled', false).text(ButtonText);
                }, 500);
                return;
            }else{
                $.ajax({
                    url: ews_app.ajax_url+"auth_login",
                    type: "POST",
                    dataType: "json",
                    data: form.serialize()
                }).done(function(response){
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
    
                    $("#auth-message").addClass("jump").text(msg);

                    clearTimeout(AuthTimer);
                    AuthTimer = setTimeout(function(){
                        $("#auth-message").removeClass("jump").text(MessageText);
                    }, 2500);
    
                }).always(function(){
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