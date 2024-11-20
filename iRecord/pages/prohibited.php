<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .content {
            text-align: center;
        }
        .emoji {
            font-size: 4em;
            animation: fadeInOut 2s infinite;
        }
        @keyframes fadeInOut {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0;
            }
        }
        .access-denied {
            font-size: 4em;
        }
        .description {
            font-size: 20px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="emoji">🚫</div>
        <div class="access-denied">Access Denied</div>
        <p class="description">You do not have permission to view this page.<br> Please contact your administrator for assistance.</p>
    </div>
</body>
</html>
