<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Vendor Registration Update' }}</title>

    <style>
        /* Global reset for margins and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            padding: 20px;
        }

        /* Container for the message */
        .message-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        h1 {
            font-size: 24px;
            color: #4CAF50;
            margin-bottom: 15px;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
        }

        .approved {
            font-weight: bold;
            color: #4CAF50;
            margin-top: 20px;
        }

        .under-review {
            font-weight: bold;
            color: #FF9800;
            margin-top: 20px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .message-container {
                padding: 20px;
            }
            h1 {
                font-size: 20px;
            }
            p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="message-container">
        <h1>Vendor Registration Status</h1>
        <p class="approved">Your vendor registration has been approved. You will be notified if any further action is required.</p>
        <p class="under-review">Your vendor registration is still under review.</p>
    </div>

</body>
</html>
