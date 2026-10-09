<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="/icon.png?v=1">
        <link rel="apple-touch-icon" href="/icon.png">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Force Light Mode Default Theme Script -->
        <script>
            (function () {
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    if (!savedTheme) {
                        localStorage.setItem('theme', 'light');
                    }
                }
            })();
        </script>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-[#F9F7F1] dark:bg-[#152B1C] text-gray-900 dark:text-gray-100 transition-colors">
        @inertia
    </body>
</html>