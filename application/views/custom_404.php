<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f2f2f2;
            padding: 50px;
        }
        h1 {
            font-size: 60px;
            color: #333;
        }
        p {
            font-size: 18px;
            color: #666;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Oops! The page you are looking for doesn't exist.</p>
    <a href="<?= base_url(); ?>">Go Back to Home</a>
</body>
</html>
