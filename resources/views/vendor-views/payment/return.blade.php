<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .status {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        .success { color: green; }
        .failed { color: red; }
    </style>
</head>
<body>
    <h1>Payment Status</h1>
    <p class="status {{ $status }}">{{ $message }}</p>
    <a href="/">Go Back to Home</a>
</body>
</html>
