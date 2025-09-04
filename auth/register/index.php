<?php
include_once("../../config/config.php");


// Input value
$email = '';
$password = '';
$username = '';
$phoneNumber = '';
$address = '';
$postcode = '';
$states = [
  "Johor",
  "Kedah",
  "Kelantan",
  "Malacca",
  "Negeri Sembilan",
  "Pahang",
  "Penang",
  "Perak",
  "Perlis",
  "Sabah",
  "Sarawak",
  "Selangor",
  "Terengganu"
];
$state = '';


// Input error
$emailErr = '';
$passwordErr = '';
$usernameErr = '';
$phoneNumberErr = '';
$addressErr = '';
$postcodeErr = '';
$stateErr = '';

// Register error
$registerErr = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST['email']) ?? '';
  $password = trim($_POST['password']) ?? '';
  $username = trim($_POST['username']) ?? '';
  $address = trim($_POST['address']) ?? '';
  $postcode = trim($_POST['postcode']) ?? '';
  $state = trim($_POST['state']) ?? '';

  // Replace all hyphens (-) and white space
  $phoneNumber = $_POST['phonenumber'] ? preg_replace('/[\-\s]/', '', trim($_POST['phonenumber'])) : '';

  $inputErr = false;

  // Email validation
  if (empty($email)) {
    $emailErr = "Email is required";
    $inputErr = true;
  } else if (!validateEmail($email)) {
    $emailErr = "Please enter a valid email";
    $inputErr = true;
  } else if (checkEmailExists($email, $conn)) {
    $emailErr = "Email already existed";
    $inputErr = true;
  }

  // Password validation
  if (empty($password)) {
    $passwordErr = "Password is required";
    $inputErr = true;
  } else if (strlen($password) < 6) {
    $passwordErr = "Password must have at least 6 characters";
    $inputErr = true;
  } else if (!validatePassword($password)) {
    $passwordErr = "Password must contain at least one special character(s)";
    $inputErr = true;
  }

  // Username validation
  if (empty($username)) {
    $usernameErr = "Username is required";
    $inputErr = true;
  } else if (checkUsernameExists($username, $conn)) {
    $usernameErr = "Username already exists";
    $inputErr = true;
  }

  // Phone number validation
  if (empty($phoneNumber)) {
    $phoneNumberErr = "Phone number is required";
    $inputErr = true;
  } else if (!validatePhoneNumber($phoneNumber)) {
    $phoneNumberErr = "Please enter a valid phone number";
    $inputErr = true;
  } else if (checkPhonenumberExists($phoneNumber, $conn)) {
    $phoneNumberErr = "Phone number already exists";
    $inputErr = true;
  }

  // Address validation
  if (empty($address)) {
    $addressErr = "Address is required";
    $inputErr = true;
  }

  // Postcode validation
  if (empty($postcode)) {
    $postcodeErr = "Postcode is required";
    $inputErr = true;
  } else if (!validatePostcode($postcode)) {
    $postcodeErr = "Please enter a valid 5-digit postcode";
    $inputErr = true;
  }

  // State validation
  if (empty($state)) {
    $stateErr = "State is required";
    $inputErr = true;
  } else if (!in_array($state, $states)) {
    $stateErr = "Please select a valid state";
    $inputErr = true;
  }

  // Only proceed if email and password input are valid
  if (!$inputErr) {
    // Example insert query template
    $insertQuery = "INSERT INTO customer (email, username, password, phone_number, address, postcode, state) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $insertQuery);

    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssssss", $email, $username, $hashedPassword, $phoneNumber, $address, $postcode, $state);

    if (!mysqli_stmt_execute($stmt)) {
      $registerErr = "Error registering your account. Please try again later.";
    } else {
      header("Location: ../login/");
    }
    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
}

function validateEmail(string $email)
{
  $emailregex = "/\w+@\w+\.\w+/";
  return preg_match($emailregex, $email) === 1;
}

// Check if the email has been used by other customer
function checkEmailExists(string $email, mysqli $conn)
{
  $user = "select email from customer where email = ?";
  $stmt = mysqli_prepare($conn, $user);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);

  $emailExists = false;

  if (mysqli_stmt_num_rows($stmt) > 0) {
    $emailExists = true;
  }

  mysqli_stmt_close($stmt);

  return $emailExists;
}

// Check if the username has been taken by other customer
function checkUsernameExists(string $username, mysqli $conn)
{
  $user = "select username from customer where username = ?";
  $stmt = mysqli_prepare($conn, $user);
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);

  $usernameExists = false;

  if (mysqli_stmt_num_rows($stmt) > 0) {
    $usernameExists = true;
  }

  mysqli_stmt_close($stmt);

  return $usernameExists;
}

// Check if the phone number has been taken by other customer
function checkPhonenumberExists(string $phoneNumber, mysqli $conn)
{
  $user = "select phone_number from customer where phone_number = ?";
  $stmt = mysqli_prepare($conn, $user);
  mysqli_stmt_bind_param($stmt, "s", $phoneNumber);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);

  $phoneNumberExists = false;

  if (mysqli_stmt_num_rows($stmt) > 0) {
    $phoneNumberExists = true;
  }

  mysqli_stmt_close($stmt);

  return $phoneNumberExists;
}

function validatePassword(string $password)
{
  // at least one special character
  $passwordregex = "/[^a-za-z0-9]/";
  return preg_match($passwordregex, $password) === 1;
}

function validatePostcode(string $postcode)
{
  // Must be number with length of 5
  $postcodeRegex = "/\d{5}/";
  return preg_match($postcodeRegex, $postcode) === 1;
}

function validatePhoneNumber(string $phoneNumber)
{
  /*
 * All the hyphense and dash will be replaced before getting matched 
 * with the regex 
 * 
 * The number must be mobile number instead of office number, if it starts with 03, 
 * it will be invalid
 *
 * Example of valid phone number:
 * 1. 01012345678
 * 2. 0101234567
 * 
 * */
  $phoneRegex = "/^01[0-46-9]\d{7,8}$/";
  return preg_match($phoneRegex, $phoneNumber);
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="./register-style.css">
  <link rel="stylesheet" href="../../index.css">
  <title>Sign Up</title>
</head>

<body>
  <main class="max-w-[500px] w-full mx-auto mt-20 bg-gradient-to-br from-gray-800 via-slate-700 to-gray-900 border border-gray-600 rounded-xl shadow-2xl text-white font-medium p-6">
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="flex flex-col" id="loginFormContainer">
      <h2 class="text-3xl font-bold text-center text-cyan-400">Sign Up Now</h2>

      <!-- Email Field -->
      <label class="flex flex-col gap-1">
        <span class="text-sm text-gray-300">Email</span>
        <input type="text" id="email" name="email" required placeholder="Your email"
          value="<?php echo htmlspecialchars($email) ?>"
          class="bg-gray-900 text-white border border-gray-600 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200" />
        <!-- Email error message -->
        <span class="text-red-400 text-sm h-5 err-msg font-bold"><?php echo htmlspecialchars($emailErr) ?></span>
      </label>

      <!-- Username Field -->
      <label class="flex flex-col gap-1">
        <span class="text-sm text-gray-300">Username</span>
        <input type="text" id="username" name="username" required placeholder="Your username"
          value="<?php echo htmlspecialchars($username) ?>"
          class="bg-gray-900 text-white border border-gray-600 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200" />
        <!-- Username error message -->
        <span class="text-red-400 text-sm h-5 err-msg font-bold font-bold"><?php echo htmlspecialchars($usernameErr) ?></span>
      </label>

      <!-- Password Field -->
      <label class="flex flex-col gap-1">
        <span class="text-sm text-gray-300">Password</span>
        <input type="password" id="password" name="password" required placeholder="Your password"
          value="<?php echo htmlspecialchars($password) ?>"
          class="bg-gray-900 text-white border border-gray-600 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200" />
        <!-- Password error message -->
        <span class="text-red-400 text-sm h-5 err-msg font-bold"><?php echo htmlspecialchars($passwordErr) ?></span>
      </label>

      <!-- Phone Number Field -->
      <label class="flex flex-col gap-1">
        <span class="text-sm text-gray-300">Phone Number</span>
        <input type="text" id="phonenumber" name="phonenumber" pattern="[\d\-]*" maxlength="13" placeholder="012-3456-7890"
          value="<?php echo htmlspecialchars($phoneNumber) ?>"
          class="bg-gray-900 text-white border border-gray-600 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200" />
        <!-- Phone Number error message -->
        <span class="text-red-400 text-sm h-5 err-msg font-bold"><?php echo htmlspecialchars($phoneNumberErr) ?></span>
      </label>

      <!-- Address Field -->
      <label class="flex flex-col gap-1">
        <span class="text-sm text-gray-300">Address</span>
        <input type="text" id="address" name="address" required placeholder="Your street address"
          value="<?php echo htmlspecialchars($address) ?>"
          class="bg-gray-900 text-white border border-gray-600 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200" />
        <!-- Address error message -->
        <span class="text-red-400 text-sm h-5 err-msg font-bold"><?php echo htmlspecialchars($addressErr) ?></span>
      </label>

      <!-- Postcode Field -->
      <label class="flex flex-col gap-1">
        <span class="text-sm text-gray-300">Postcode</span>
        <input type="text" id="postcode" name="postcode" maxlength="5" pattern="\d*" placeholder="Your postcode"
          value="<?php echo htmlspecialchars($postcode) ?>"
          class="bg-gray-900 text-white border border-gray-600 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200" />
        <!-- Postcode error message -->
        <span class="text-red-400 text-sm h-5 err-msg font-bold"><?php echo htmlspecialchars($postcodeErr) ?></span>
      </label>

      <!-- State Field -->
      <label class="flex flex-col gap-1">
        <span class="text-sm text-gray-300">State</span>
        <select name="state" class="bg-gray-900 text-white border border-gray-600 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200" required aria-placeholder="Select your state">
          <?php
          foreach ($states as $state) {
            echo "<option value='" . htmlspecialchars($state) . "'>$state</option>";
          }

          ?>
        </select>
        <!-- State error message -->
        <span class="text-red-400 text-sm h-5 err-msg font-bold"><?php echo htmlspecialchars($stateErr) ?></span>
      </label>

      <!-- Submit Button -->
      <button type="submit"
        class="bg-cyan-500 hover:opacity-70 text-white font-semibold py-2 rounded-md transition-opacity uppercase tracking-wide">
        Sign Up
      </button>
    </form>

    <div class="text-center mt-6 text-sm text-gray-300">
      Already have an account?
      <a href="../login/" class="text-cyan-400 hover:underline hover:text-cyan-300">Sign In here!</a>
    </div>
  </main>

</body>
<script src="./register.js"></script>

</html>