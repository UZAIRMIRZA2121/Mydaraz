<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .content {
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 8px;
            margin: 20px;
        }
        h2 {
            color: #4CAF50;
        }
        p {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>{{ $title }}</h2>
        <p>Dear {{ $vendorName }},</p>
    
        {{-- Main Conditions Based on Status --}}
        @if ($status == 'approved' && $templateName == 'registration-approved')
            <p>Congratulations! Your vendor registration has been approved. You can now start using the platform to grow your business.</p>
        @elseif ($status == 'denied' && $templateName == 'registration-denied')
            <p>We regret to inform you that your vendor registration has been denied. If you have any questions, please contact our support team for further details.</p>
        @elseif ($status == 'suspended' && $templateName == 'account-suspended')
            <p>We regret to inform you that your account has been suspended due to certain policy violations. Please contact support if you believe this is a mistake or for further clarification.</p>
        @elseif ($status == 'approved' && $templateName == 'account-activation')
            <p>Good news! Your account has been activated successfully. You can now log in and start using the platform.</p>
        @else
            <p>We encountered an issue with your account. Please contact support for assistance.</p>
        @endif
    
        {{-- General Footer --}}
        <p>Best Regards,</p>
        <p>AlBazar Team</p>
    </div>
    
    
</body>
</html>
