<?php
session_start(); // Start the session to access username

// Check if the user is logged in
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Text Page</title>
    <style>
        body {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full height of the viewport */
            margin: 0;
            background-color: #f4f4f4; /* Light background color */
            font-family: Arial, sans-serif;
        }

        .animated-text {
            color: royalblue;
            font-size: 72px; /* Font size for the animated text */
            opacity: 0; /* Start invisible */
            animation: zoomFade 3s infinite; /* Animation */
            text-align: center;
        }

        @keyframes zoomFade {
            0% {
                transform: scale(1);
                opacity: 0;
            }
            50% {
                transform: scale(1.5); /* Zoom in */
                opacity: 1; /* Fully visible */
            }
            100% {
                transform: scale(1);
                opacity: 0; /* Fade out */
            }
        }

        .paragraph {
            text-align: center; /* Center the paragraph text */
            font-size: 20px; /* Font size for the paragraph */
            margin-top: 20px; /* Space above the paragraph */
        }

        .image-container {
            text-align: center; /* Center the image */
            margin-bottom: 20px; /* Space below the image */
            animation: swing 2s infinite; /* Add swinging effect */
        }

        @keyframes swing {
            0% {
                transform: rotate(0deg);
            }
            20% {
                transform: rotate(15deg);
            }
            40% {
                transform: rotate(-10deg);
            }
            60% {
                transform: rotate(5deg);
            }
            80% {
                transform: rotate(-5deg);
            }
            100% {
                transform: rotate(0deg);
            }
        }

        img {
            max-width: 30%; /* Make the image responsive */
            height: auto; /* Maintain aspect ratio */
        }
    </style>
</head>
<body>
    <div>
        <div class="image-container">
            <img src="../img/shalem.png" alt="Your Image Description"> <!-- Add your image here -->
        </div>
        <div class="animated-text">Shalem IT Consult</div> <!-- Animated text -->
        <p class="paragraph">Contact Us: 0543977899 / 0548765756 / 0264580788 / 0247312674</p> <!-- Paragraph below the animated text -->
    </div>
</body>
</html>