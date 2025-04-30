<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Backend' }}</title>
        @vite('resources/css/app.css')
    </head>
    <body class="flex flex-col h-full min-h-screen">
        <main class="dark:bg-gray-900 dark:text-white flex-grow h-full">
            {{ $slot }}
        </main>
    </body>
</html>
