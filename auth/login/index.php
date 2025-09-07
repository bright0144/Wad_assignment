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
        header("Location: ../../product/productList/index.php"); 
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
  $emailregex = "/\w+@\w+\.\w+/";
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
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="../../index.css">
  <title>Login</title>
</head>

<body>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <div class="max-w-[500px] w-full mx-auto mt-20 p-8 sm:p-10 bg-gray-950 border border-slate-800 rounded-3xl shadow-2xl shadow-gray-950/50 text-white font-['Plus_Jakarta_Sans'] transition-all duration-300 hover:scale-[1.005] hover:shadow-cyan-500/10">
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="flex flex-col gap-6">
      <div class="flex flex-col items-center justify-center text-center">
        <svg class="w-12 h-12 text-cyan-400 mb-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0 14c-2.67 0-5.34-.84-7.46-2.26C5.54 16.5 8.68 15 12 15s6.46 1.5 7.46 2.74C17.34 19.16 14.67 20 12 20z" />
        </svg>
        <h2 class="text-4xl font-extrabold text-cyan-400 leading-tight">Welcome Back</h2>
        <p class="text-sm text-gray-400 mt-1">Please log in to your account</p>
      </div>

      <label class="flex flex-col gap-1">
        <span class="text-sm text-gray-400 font-semibold">Email</span>
        <input type="text" id="email" name="email" required
          value="<?php echo htmlspecialchars($email) ?>"
          class="bg-gray-900 text-white border border-slate-700 rounded-lg px-4 py-3 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200"
          placeholder="hello@your-email.com" />
        <span class="text-red-400 text-sm h-5 err-msg font-bold"><?php echo htmlspecialchars($emailErr) ?></span>
      </label>

      <label class="flex flex-col gap-1">
        <span class="text-sm text-gray-400 font-semibold">Password</span>
        <input type="password" id="password" name="password" required
          value="<?php echo htmlspecialchars($password) ?>"
          class="bg-gray-900 text-white border border-slate-700 rounded-lg px-4 py-3 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200"
          placeholder="••••••••" />
        <span class="text-red-400 text-sm h-5 err-msg font-bold"><?php echo htmlspecialchars($passwordErr) ?></span>
      </label>

      <button type="submit"
        class="bg-cyan-500 hover:bg-cyan-400 text-white font-bold py-3 rounded-lg transition-all duration-200 uppercase tracking-wide transform hover:-translate-y-1">
        Sign In
      </button>
    </form>

    <div class="text-red-400 text-sm h-5 err-msg font-bold text-center mt-4 font-bold">
      <?php echo htmlspecialchars($loginErr) ?>
    </div>

    <div class="text-center mt-4 text-sm text-gray-400">
      Don't have an account?
      <a href="../register/" class="text-cyan-400 font-semibold hover:underline hover:text-cyan-300 transition-colors duration-200">Sign Up here!</a>
    </div>
  </div>

</body>

<script src="./login.js"></script>

</html>