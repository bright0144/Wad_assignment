<?php
include_once("../../config/config.php");

session_start();

// Input values
$email = '';
$password = '';

// Input error
$emailErr = '';
$passwordErr = '';

// Login error
$loginErr = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST['email']) ?? '';
  $password = trim($_POST['password']) ?? '';

  // Initialize all the error to empty string
  $emailErr = $passwordErr = "";

  $inputErr = false;

  if (empty($email)) {
    $emailErr = "Email is required";
    $inputErr = true;
  } else if (!validateEmail($email)) {
    $emailErr = "Please enter a valid email";
    $inputErr = true;
  }

  if (empty($password)) {
    $passwordErr = "Password is required";
    $inputErr = true;
  } else if (strlen($password) < 6) {
    $passwordErr = "Password must have at least 6 chacters";
    $inputErr = true;
  } else if (!validatePassword($password)) {
    $passwordErr = "Password must contain at least one special character(s)";
    $inputErr = true;
  }

  // Only proceed if email and password input are valid
  if (!$inputErr) {

    $user = "select username, password, cust_id from customer where email = ?";

    $stmt = mysqli_prepare($conn, $user);

    mysqli_stmt_bind_param($stmt, "s", $email);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 0) {
      // User does not exist
      $loginErr = "Email does not exists";
    } else {
      mysqli_stmt_bind_result($stmt, $fetchedUsername, $fetchedPassword, $customer_id);
      mysqli_stmt_fetch($stmt);

      if (!password_verify($password, $fetchedPassword)) {
        $loginErr = "Password is incorrect";
      } else {
        $_SESSION["id"] = $customer_id;
        header("Location: ../../index.php");
      }
    }

    mysqli_stmt_close($stmt);
  }

  if (!$loginErr && !$inputErr) {
    // TODO
  }

  mysqli_close($conn);
}

function validateemail(string $email)
{
  $emailregex = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";
  return preg_match($emailregex, $email) === 1;
}

function validatepassword(string $password)
{
  // at least one special character
  $passwordregex = "/[^a-za-z0-9]/";
  return preg_match($passwordregex, $password) === 1;
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="./login.css">
  <title>Login</title>
</head>

<body>
  <div class="max-w-[500px] w-full mx-auto mt-20 p-8 bg-[#222] border-2 border-yellow-200 rounded-3xl shadow-2xl shadow-gray-900/40 text-white">
    <!-- Sign in form -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="flex flex-col gap-6">

      <!-- Logo and title -->
      <div class="flex flex-col items-center justify-center text-center">
        <div class="w-35 h-35">
          <img class="w-full h-full object-cover" src="/Wad_assignment/image/yu-gi-oh-sign-in.png" alt="yu gi oh sign in logo">
        </div>
        <h2 class="text-4xl font-extrabold text-white">Welcome Back</h2>
        <p class="text-sm text-gray-400 mt-1">Please log in to your account</p>
      </div>

      <label class="flex flex-col gap-1">
        <span class="text-sm font-semibold">Email</span>
        <input type="email" id="email" name="email" required
          value="<?php echo htmlspecialchars($email) ?>"
          class="bg-[#333] text-white border-2 border-yellow-300 rounded-lg px-4 py-3 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-300  transition-all duration-200"
          placeholder="sample@utar.my" />
        <span class="text-red-400 text-sm h-5 err-msg font-bold"><?php echo htmlspecialchars($emailErr) ?></span>
      </label>

      <label class="flex flex-col gap-1">
        <span class="text-sm text-gray-400 font-semibold">Password</span>
        <input type="password" id="password" name="password" required
          value="<?php echo htmlspecialchars($password) ?>"
          class="bg-[#333] text-white border-2 border-yellow-300 rounded-lg px-4 py-3 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-300  transition-all duration-200"
          placeholder="••••••••" />
        <span class="text-red-400 text-sm h-5 err-msg font-bold"><?php echo htmlspecialchars($passwordErr) ?></span>
      </label>

      <button type="submit"
        class="bg-[#FFA500] hover:opacity-70 text-white font-bold py-3 rounded-lg transition-opacity duration-200 uppercase">
        Sign In
      </button>
    </form>

    <!-- Error container -->
    <div class="text-red-400 text-sm h-5 err-msg font-bold text-center mt-4 font-bold">
      <?php echo htmlspecialchars($loginErr) ?>
    </div>

    <!-- Link to sign up page -->
    <div class="text-center mt-6 text-md">
      Do not have an account yet?
      <a href="../register/index.php" class="ml-2 inline-block transition-transform duration-200
          hover:scale-110 hover:underline text-[#FFA500]">Sign Up here!</a>
    </div>
  </div>

</body>

<script src="./login.js"></script>

</html>