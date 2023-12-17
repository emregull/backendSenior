<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mukellef CO</title>
</head>
    <body>
        <div class="container">
            @auth
                <p>Hello, {{ auth()->user()->name }}!</p>
            @else
                <p>You are not logged in.</p>
            @endauth
        </div>
    </body>
</html>
