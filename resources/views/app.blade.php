<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@1,600&family=Open+Sans&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="https://js.stripe.com/v3/"></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBoqnn-RNjHhWgqCGmHPL-QZOza_flF6hg&libraries=places"></script>

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-T7DM983');</script>
        <!-- End Google Tag Manager -->

        <script>
            window.fwSettings={
            'widget_id':51000000175
            };
            !function(){if("function"!=typeof window.FreshworksWidget){var n=function(){n.q.push(arguments)};n.q=[],window.FreshworksWidget=n}}() 
        </script>
        <script type='text/javascript' src='https://aus-widget.freshworks.com/widgets/51000000175.js' async defer></script>
    </head>
    <body class="text-sm leading-6 font-sans antialiased bg-light tracking-wider">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7DM983"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

        @inertia
    </body>
</html>
