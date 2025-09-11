<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("Location: ../index.php");
  exit;
}

include_once("../config/config.php");

$email = $phoneNumber = "";

// Get user details 
$userId = $_SESSION['id'];
$stmt = $conn->prepare("SELECT email, phone_number FROM customer WHERE cust_id = ?;");
$stmt->bind_param("i", $userId);
if ($stmt->execute()) {
  $result = $stmt->get_result();
  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $email = $user['email'];
    $phoneNumber = $user['phone_number'];
  } else {
    echo "User not found.";
  }
} else {
  echo "Error executing query.";
}

$stmt->close();

// Get user address
$addresses = [];
$stmt = $conn->prepare("SELECT address_id, address, postcode, state FROM customer_addresses WHERE cust_id = ?");
$stmt->bind_param("i", $userId);
if ($stmt->execute()) {
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $addresses[] = $row;
  }
} else {
  echo "Error executing query.";
}

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Manage Profile</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./styles.css">

</head>

<body class="manage-profile">
  <?php include("../header/index.php"); ?>
  <main class="pt-[30px]">
    <div class="mx-auto p-6 max-w-3xl">
      <h2 class="text-2xl font-bold text-white mb-6">Manage Profile</h2>

      <form id="profileForm" class="space-y-5 bg-[#222] p-6 rounded-xl shadow-lg text-white">
        <!-- Email field -->
        <div class="form-group">
          <label class="block font-semibold mb-1">Email:</label>
          <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"
            class="w-full rounded-lg px-3 py-2 text-gray-900 focus:ring-2 focus:ring-[#FFA500] focus:outline-none border-2 border-gray-500 text-white bg-black focus:border-transparent"
            required>
          <span class="err text-sm text-red-300"></span>
        </div>

        <!-- Password -->
        <div class="form-group">
          <label class="block font-semibold mb-1">Password:</label>
          <input type="password" name="password" placeholder="New password"
            class="w-full rounded-lg px-3 py-2 text-gray-900 focus:ring-2 focus:ring-[#FFA500] focus:outline-none border-2 border-gray-500 text-white bg-black focus:border-transparent"
            required>
          <span class="err text-sm text-red-300"></span>
        </div>

        <!-- Phone Number -->
        <div class="form-group">
          <label class="block font-semibold mb-1">Phone Number:</label>
          <input type="text" id="phone_number" name="phone_number" pattern="[\d\-]*" maxlength="13"
            value="<?= htmlspecialchars($phoneNumber) ?>"
            class="w-full rounded-lg px-3 py-2 text-gray-900 focus:ring-2 focus:ring-[#FFA500] focus:outline-none border-2 border-gray-500 text-white bg-black focus:border-transparent"
            <span class="err text-sm text-red-300"></span>
        </div>

        <!-- State -->
        <div class="form-group">
          <label class="block font-semibold mb-1">State:</label>
          <select name="state"
            class="w-full rounded-lg px-3 py-2 text-gray-900 focus:ring-2 focus:ring-[#FFA500] focus:outline-none border-2 border-gray-500 text-white bg-black focus:border-transparent"
            required>
            <?php foreach ($states as $state): ?>
              <option value="<?= $state; ?>"><?= $state ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Save and reset form btn -->
        <div class="flex gap-4 mt-4">
          <button type="button" onclick="handleUpdateProfile(event)"
            class="flex-1 bg-[#FFA500] hover:bg-[#cc8400] text-white font-semibold py-2 rounded-lg shadow transition">
            Save Changes
          </button>
          <button type="reset"
            class="flex-1 border border-white hover:bg-white hover:text-gray-900 text-white font-semibold py-2 rounded-lg transition">
            Reset
          </button>
        </div>
      </form>

      <!-- Additional Addresses -->
      <div class="mt-8 bg-[#222] p-6 rounded-xl shadow-lg text-white flex flex-col">
        <label class="block font-semibold mb-2">Additional Addresses:</label>
        <div id="additionalAddresses" class="space-y-4">
          <?php foreach ($addresses as $address): ?>
            <div id="address-<?= $address['address_id']; ?>" class="bg-[#333] p-4 rounded-lg shadow flex flex-col gap-1">
              <div><strong>Address:</strong> <?= htmlspecialchars($address['address']); ?></div>
              <div><strong>Postcode:</strong> <?= htmlspecialchars($address['postcode']); ?></div>
              <div><strong>State:</strong> <?= htmlspecialchars($address['state']); ?></div>
              <!-- Remove address btn -->
              <button type="button"
                class="mt-2 w-fit px-3 py-1 bg-red-500 hover:bg-red-400 rounded-lg text-white font-medium transition"
                onclick="removeAddress(<?= $address['address_id']; ?>)">Remove Address</button>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Add new address btn -->
        <button type="button" onclick="openModal()"
          class="mt-4 px-4 py-2 bg-[#FFA550] hover:bg-[#cc8400] rounded-lg text-white font-semibold transition self-center w-[50%]">
          Add New Address
        </button>
      </div>

      <!-- Address Modal -->
      <div id="addressModal"
        class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50 text-white backdrop-blur-xl">
        <div class="bg-[#222] rounded-xl shadow-lg w-full max-w-md p-6 relative">
          <!-- Close button -->
          <button onclick="closeModal()"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>

          <h2 class="text-xl font-semibold mb-4">Add New Address</h2>

          <form id="newAddressForm" class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Address</label>
              <input type="text" id="new-address" name="new-address" placeholder="Address"
                class="w-full border-2 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#FFA500] focus:outline-none bg-black focus:border-transparent border-gray-500"
                required>
              <span class="err text-sm text-red-500"></span>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Postcode</label>
              <input type="text" id="new-postcode" name="new-postcode" placeholder="Postcode"
                class="w-full border-2 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#FFA500] focus:outline-none bg-black focus:border-transparent border-gray-500"
                required>
              <span class="err text-sm text-red-500"></span>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">State</label>
              <select id="new-state" name="new-state"
                class="w-full border-2 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#FFA500] focus:outline-none bg-black focus:border-transparent border-gray-500"
                required>
                <?php foreach ($states as $state): ?>
                  <option value="<?= $state; ?>"><?= $state ?></option>
                <?php endforeach; ?>
              </select>
              <span class="err text-sm text-red-500"></span>
            </div>

            <div class="flex justify-end gap-2 pt-4">
              <button type="submit" onclick="saveNewAddress(event)"
                class="px-4 py-2 bg-[#FFA500] hover:bg-[#cc8400] rounded-lg text-white font-semibold transition flex-1">
                Save
              </button>
              <button type="button" onclick="closeModal()"
                class="px-4 py-2 border rounded-lg hover:bg-gray-100 text-white transition flex-1 hover:text-gray-800">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
  <?php include("../footer/index.php"); ?>
</body>
<script src="./script.js"></script>

</html>