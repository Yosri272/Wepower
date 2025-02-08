<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forbidden - Access Denied</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .error-container {
            text-align: center;
        }

        .error-code {
            font-size: 8rem;
            color: #dc3545;
        }

        .error-message {
            font-size: 2rem;
            margin-top: 20px;
            color: #343a40;
        }

        .btn-home {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1.2rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="error-container">
            <div class="error-code">403</div>
            <div class="error-message">Oops! Access Denied</div>
            <p class="text-muted">Sorry, you do not have the right roles to access this page.</p>
            <a href="/" class="btn btn-primary btn-home">Go to Home Page</a>
        </div>
    </div>
</body>

</html>
