<!-- Navigate back to Home Page when user is not logged in or session is invalid. -->
<?php
session_start(); 

if (!isset($_SESSION['id'])) {
   header("Location: /Wad_assignment/auth/login/log-out.php");
   exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Us</title>

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

  <style>
    /* Basic reset */
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
      color: #222;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      padding: 0 20px;
    }

    h1 {
      text-align: center;
      color: #ff6600;
      margin-bottom: 30px;
    }

    /* Contact info section */
    .contact-info {
      display: flex;
      justify-content: space-around;
      margin-bottom: 40px;
      background: #222;
      color: #eee;
      padding: 30px 20px;
      border-radius: 8px;
    }

    .contact-item {
      display: flex;
      align-items: center;
      gap: 15px;
      flex: 1;
      max-width: 280px;
    }

    .contact-item button {
      background: none;
      border: none;
      color: #ff6600;
      font-size: 40px;
      cursor: default;
      user-select: none;
    }

    .contact-item a,
    .contact-item span {
      color: #eee;
      font-size: 18px;
      text-decoration: none;
      word-break: break-word;
    }

    .contact-item a:hover {
      text-decoration: underline;
    }

    /* Contact form styles */
    form {
      background: white;
      padding: 30px 25px;
      border-radius: 8px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }

    form label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #333;
    }

    form input[type="text"],
    form input[type="email"],
    form textarea {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
      resize: vertical;
      transition: border-color 0.3s ease;
    }

    form input[type="text"]:focus,
    form input[type="email"]:focus,
    form textarea:focus {
      border-color: #ff6600;
      outline: none;
    }

    form button[type="submit"] {
      background-color: #ff6600;
      color: white;
      border: none;
      padding: 14px 25px;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      width: 100%
    }

    form button[type="submit"]:hover {
      background-color: #e65c00;
    }

    .addressContainer {
      display: flex;
      margin-right: 20px;

    }

    /* Responsive */
    @media (max-width: 700px) {
      .contact-info {
        flex-direction: column;
        gap: 25px;
      }

      .contact-item {
        max-width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
               <?php include("../header/index.php"); ?>

  <div class="container">
    <h1 style ="margin-top: 150px;">Contact Us</h1>



    <form action="#" method="post">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" placeholder="Your full name" required />

      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Your email address" required />

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="6" placeholder="Write your message here..." required></textarea>

      <button type="submit">Send Message</button>
    </form>

    <div style="margin-top: 40px;">
  <h1 style="text-align: center; color: #ff6600;">Our Location</h1>
  <div class = "addressContainer" >
    <div class ="word">
      <h2>Neo Chean Ming</h2>
      <p>Telephone: +60 12 223 1234</p>
      <p>Email: xx@gmail.com</p>
      <p> Location: Jalan Sungai Long, Bandar Sungai Long, 43000 Kajang, Selangor</p>
    </div>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127492.08319207394!2d101.72871372297307!3d3.060527843490911!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc34a5f21a8235%3A0x78796ffc32ce3fcd!2sUniversiti%20Tunku%20Abdul%20Rahman%20(UTAR)!5e0!3m2!1sen!2smy!4v1757494244135!5m2!1sen!2smy" width="100%" height="450px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>
</div>
                   

  </div>
  <?php include("../footer/index.php"); ?>
</body>
</html>
