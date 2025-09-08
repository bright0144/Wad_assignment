<?php
session_start();


if (!isset($_SESSION['id'])) {
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Not Logged In</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .message-box {
                background-color: white;
                padding: 30px;
                border: 1px solid #ccc;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .message-box h2 {
                margin-bottom: 20px;
                color: #333;
            }
            .message-box a {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007BFF;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s;
            }
            .message-box a:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="message-box">
            <h2>You are not logged in or your session has expired.</h2>
            <a href="index.php">Go to Login</a>
        </div>
    </body>
    </html>
    ';
    exit();
} else {
    return "success";
}
?>
