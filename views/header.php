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
            "time_zone" : "America/Lima",
            "microsoft_office" : {
                // Office 2016 Products
                "OFFICE_HOME_STUDENT_2016" : "Office Home & Student 2016",
                "OFFICE_HOME_BUSSINESS_2016" : "Office Home & Business 2016",
                "OFFICE_PRO_2016" : "Office Professional 2016",
                "OFFICE_PRO_PLUS_2016" : "Office Professional Plus 2016",
                "OFFICE_STANDARD_2016" : "Office Standard 2016",
                "OFFICE_EDUCATION_2016" : "Office Education 2016",
                "OFFICE_365_2016" : "Office 365 (2016)",
                // Office 2019 Products
                "OFFICE_HOME_STUDENT_2019" : "Office Home & Student 2019",
                "OFFICE_HOME_BUSSINESS_2019" : "Office Home & Business 2019",
                "OFFICE_PRO_2019" : "Office Professional 2019",
                "OFFICE_PRO_PLUS_2019" : "Office Professional Plus 2019",
                "OFFICE_STANDARD_2019" : "Office Standard 2019",
                "OFFICE_LTSC_STANDARD_2019" : "Office LTSC Standard 2019",
                "OFFICE_LTSC_PRO_PLUS_2019" : "Office LTSC Professional Plus 2019",
                "OFFICE_365_2019" : "Office 365 (2019)",
                // Office 2021 Products
                "OFFICE_HOME_STUDENT_2021" : "Office Home & Student 2021",
                "OFFICE_HOME_BUSSINESS_2021" : "Office Home & Business 2021",
                "OFFICE_PRO_2021" : "Office Professional 2021",
                "OFFICE_LTSC_STANDARD_2021" : "Office LTSC Standard 2021",
                "OFFICE_LTSC_PRO_PLUS_2021" : "Office LTSC Professional Plus 2021",
                "OFFICE_FOR_MAC_2021" : "Office for Mac 2021",
                "MICROSOFT_365_2021" : "Microsoft 365 (2021)",
                // Office 2024 Products
                "OFFICE_HOME_STUDENT_2024" : "Office Home & Student 2024",
                "OFFICE_HOME_BUSSINESS_2024" : "Office Home & Business 2024",
                "OFFICE_PRO_2024" : "Office Professional 2024",
                "OFFICE_LTSC_STANDARD_2024" : "Office LTSC Standard 2024",
                "OFFICE_LTSC_PRO_PLUS_2024" : "Office LTSC Professional Plus 2024",
                "OFFICE_FOR_MAC_2024" : "Office for Mac 2024",
                "MICROSOFT_365_2024" : "Microsoft 365 (2024)",
                // Microsft 365 Subscriptions
                "MICROSOFT_365_PERSONAL" : "Microsoft 365 Personal",
                "MICROSOFT_365_FAMILY" : "Microsoft 365 Family",
                "MICROSOFT_365_BUSSINESS_BASIC" : "Microsoft 365 Business Basic",
                "MICROSOFT_365_BUSSINESS_STANDARD" : "Microsoft 365 Business Standard",
                "MICROSOFT_365_BUSSINESS_PREMIUM" : "Microsoft 365 Business Premium",
                "MICROSOFT_365_APPS_BUSSINESS" : "Microsoft 365 Apps for Business",
                "MICROSOFT_365_APPS_ENTERPRISE" : "Microsoft 365 Apps for Enterprise",
                "MICROSOFT_365_EDUCATION" : "Microsoft 365 Education",
                "MICROSOFT_365_GOVERNMENT" : "Microsoft 365 Government",
                "MICROSOFT_365_NONPROFIT" : "Microsoft 365 Nonprofit",
                "MICROSOFT_365_E3_E5" : "Microsoft 365 E3/E5"
            },
            "microsoft_windows" : {
                // Windows 11 Editions
                "WINDOWS_11_HOME" : "Windows 11 Home",
                "WINDOWS_11_PRO" : "Windows 11 Pro",
                "WINDOWS_11_PRO_WORKSTATION" : "Windows 11 Pro for Workstations",
                "WINDOWS_11_PRO_EDUCATION" : "Windows 11 Pro Education",
                "WINDOWS_11_EDUCATION" : "Windows 11 Education",
                "WINDOWS_11_ENTERPRISE" : "Windows 11 Enterprise",
                "WINDOWS_11_ENTERPRISE_LTSC" : "Windows 11 Enterprise LTSC",
                "WINDOWS_11_SE" : "Windows 11 SE",
                "WINDOWS_11_IOT_ENTERPRISE" : "Windows 11 IoT Enterprise",
                "WINDOWS_11_N_KN" : "Windows 11 N/KN",
                // Windows 10 Editions
                "WINDOWS_10_HOME" : "Windows 10 Home",
                "WINDOWS_10_PRO" : "Windows 10 Pro",
                "WINDOWS_10_PRO_WORKSTATION" : "Windows 10 Pro for Workstations",
                "WINDOWS_10_PRO_EDUCATION" : "Windows 10 Pro Education",
                "WINDOWS_10_EDUCATION" : "Windows 10 Education",
                "WINDOWS_10_ENTERPRISE" : "Windows 10 Enterprise",
                "WINDOWS_10_ENTERPRISE_LTSC" : "Windows 10 Enterprise LTSC",
                "WINDOWS_10_IOT_ENTERPRISE" : "Windows 10 IoT Enterprise",
                "WINDOWS_10_N_KN" : "Windows 10 N/KN"
            }
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