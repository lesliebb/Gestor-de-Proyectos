<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=comic-neue:700|nunito:600" rel="stylesheet" />

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        body {
            font-family: 'Nunito', sans-serif;
            color: #2D3748;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .image-container {
            position: relative;
            /* Adjust max-width as needed */
            max-width: 700px; 
            width: 90vw;
        }
        .image-container img {
            display: block;
            width: 100%;
            height: auto;
        }
        .sign-content {
            position: absolute;
            top: 78%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 85%;
            height: auto;
            text-align: center;
        }
        .error-code {
            font-family: 'Comic Neue', cursive;
            font-weight: 700;
            font-size: clamp(2rem, 10vw, 7rem);
            line-height: 1;
            color: #4A5568;
        }
        .error-message {
            font-weight: 600;
            font-size: clamp(0.8rem, 3vw, 1.5rem);
            margin-top: 0.25rem;
            color: #718096;
        }
        .home-link {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: #4299E1;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 0.5rem;
            font-size: clamp(0.7rem, 2.5vw, 1rem);
            transition: background-color 0.2s ease-in-out;
        }
        .home-link:hover {
            background-color: #3182CE;
        }
    </style>
</head>
<body>
    <div class="image-container">
        <img src="{{ asset('images/errors/HoldingSignDog.jpg') }}" alt="Un error ha ocurrido">
        <div class="sign-content">
            <div class="error-code">@yield('code')</div>
            <div class="error-message">@yield('message')</div>
            
            <a href="{{ app('router')->has('dashboard') ? route('dashboard') : url('/') }}" class="home-link">
                Regresar al inicio
            </a>
        </div>
    </div>
</body>
</html>

