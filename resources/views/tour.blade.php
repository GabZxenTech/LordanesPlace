<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>360° Virtual Tour - LorDane's Place</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html { width: 100%; height: 100%; overflow: hidden; background: #000; }

        .tour-wrapper {
            position: relative;
            width: 100vw;
            height: 100vh;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 999;
            background: rgba(0,0,0,0.6);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(4px);
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: rgba(0,0,0,0.85);
            color: white;
        }
    </style>
</head>
<body>

<div class="tour-wrapper">
    <a href="{{ url('/') }}" class="back-btn">
        ← Back to Home
    </a>

    <iframe 
    src="{{ asset('lordanes360view/index.html') }}" 
    allowfullscreen>
</iframe>

</div>

</body>
</html>