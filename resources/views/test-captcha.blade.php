<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test reCAPTCHA</title>
    {!! NoCaptcha::renderJs() !!}
</head>
<body>
    <h1>Google reCAPTCHA Test</h1>

    <form action="{{ url('/test-captcha') }}" method="POST">
        @csrf
        <div>
            {!! NoCaptcha::display() !!}
        </div>
        @error('g-recaptcha-response')
            <p style="color: red;">{{ $message }}</p>
        @enderror

        <button type="submit">Submit</button>
    </form>
</body>
</html>
