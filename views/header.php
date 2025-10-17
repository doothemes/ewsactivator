<!DOCTYPE html>
<html lang="es-ES">
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
    <link rel="stylesheet" id="ews-activator-app-style" href="/assets/css/app.css?ver=<?=EWS_VERSION;?>" type="text/css" media="all" />
    <script type="text/javascript" src="/assets/js/jquery.js?ver=3.7.1" id="jquery-core-js"></script>
    <script type="text/javascript" src="/assets/js/app.js?ver=<?=EWS_VERSION;?>" id="ews-activator-app-core-js"></script>
    <script type="text/javascript" id="ews-activator-app-extra">
        // EWS Activator Application Configuration
        var ews_app = {
            "base_url" : "/",
            "ajax_url" : "/ajax/",
        };
        // Prevent caching of AJAX requests
        $(document).ready(function () {});
    </script>
</head>
<body class="<?=$bodyclass;?>">
    <div class="wrap">