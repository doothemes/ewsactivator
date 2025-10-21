<!DOCTYPE html>
<html id="EWSActivator" lang="es-ES">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=.95, maximum-scale=.95, user-scalable=no viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="dark light">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#000000">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#000000">
    <title><?=$title;?></title>
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="manifest" href="/assets/web/manifest.json?v=<?=EWS_VERSION;?>">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="192x192" href="/assets/icons/favicon_192.png?v=<?=EWS_VERSION;?>">
    <link rel="apple-touch-icon" sizes="256x256" href="/assets/icons/favicon_256.png?v=<?=EWS_VERSION;?>">
    <link rel="apple-touch-icon" sizes="512x512" href="/assets/icons/favicon_512.png?v=<?=EWS_VERSION;?>">
    <link rel="icon" type="image/png" sizes="64x64" href="/assets/icons/favicon_64.png?v=<?=EWS_VERSION;?>">
    <link rel="icon" type="image/png" sizes="48x48" href="/assets/icons/favicon_48.png?v=<?=EWS_VERSION;?>">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/icons/favicon_32.png?v=<?=EWS_VERSION;?>">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/icons/favicon_16.png?v=<?=EWS_VERSION;?>">
    <link rel="stylesheet" id="ews-activator-app-style" href="/assets/css/app.css?ver=<?=EWS_VERSION;?>" type="text/css" media="all" />
    <script type="text/javascript" src="/assets/js/jquery.js?ver=3.7.1" id="jquery-core-js"></script>
    <script type="text/javascript" src="/assets/js/functions.js?ver=<?=EWS_VERSION;?>" id="ews-activator-functions-core-js"></script>
    <script type="text/javascript" src="/assets/js/app.js?ver=<?=EWS_VERSION;?>" id="ews-activator-app-core-js"></script>
    <script type="text/javascript" id="ews-activator-app-extra">
        // EWS Activator Application Configuration
        var ews_app = {
            "base_url" : "/",
            "ajax_url" : "/ajax/",
            "time_zone" : "America/Lima"
        };
        // Prevent caching of AJAX requests
        $(document).ready(function(){
            const loginUsername = localStorage.getItem("login_user") ?? "";
            const paymentMethod = localStorage.getItem("admin_payment_method") ?? "YAPE";
            const currencyBadge = localStorage.getItem("admin_currency_badge") ?? "PEN";
            const savedTab = localStorage.getItem("admin_active_tab") ?? "register";
            $("#target-"+savedTab).trigger("click");
            $("#field-currency").val(currencyBadge);
            $("#field-payment-method").val(paymentMethod);
            $("#input-username").val(loginUsername);
            $(".currency-badge").text(currencyBadge);
        });
    </script>
</head>
<body class="<?=$bodyclass;?> onload">
    <script type="text/javascript" id="ewsgestion-app-onload">
        window.addEventListener("load", function () {
            setTimeout(function(){
                document.body.classList.remove("onload");
                const appWrap = document.getElementById("app-wrap");
                if (appWrap) {
                    appWrap.classList.remove("hidden");
                }
            }, 700);
        });
    </script>
    <div id="app-wrap" class="wrap hidden">