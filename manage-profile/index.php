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
$stmt = $conn->prepare("SELECT address_id, address, postcode, state FROM customer_address WHERE cust_id = ?");
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
  <style>
    body {
      font-family: sans-serif;
      padding: 20px;
    }

    .container {
      display: flex;
      flex-direction: column;
      gap: 20px;
      max-width: 600px;
      margin: auto;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .address-group {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .address-item {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .address-item input {
      flex: 1;
    }

    .actions {
      display: flex;
      justify-content: space-between;
    }

    button {
      cursor: pointer;
      background-color: lightblue;
      padding: 7px;
      border-radius: 7px;
      font-weight: bold;
    }

    input,
    select {
      padding: 10px 15px;
      border: 1px solid purple;
      margin-bottom: 10px;
      border-radius: 7px;
    }

    .err {
      color: red;
    }
  </style>
</head>

<body>

  <div class="container">
    <h2>Manage Profile</h2>

    <form id="profileForm">
      <div class="form-group" id="email-form-group">
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        <span class="err"></span>
      </div>

      <div class="form-group" id="password-form-group">
        <label>Password:</label>
        <input type="password" name="password" required placeholder="New password">
        <span class="err"></span>
      </div>

      <div class="form-group" id="phoneNumber-form-group">
        <label>Phone Number</label>
        <input type="text" id="phone_number" name="phone_number" pattern="[\d\-]*" maxlength="13"
          value="<?= htmlspecialchars($phoneNumber) ?>">
        <span class="err"></span>
      </div>

      <div class="form-group">
        <label for="state">State:</label>
        <select name="state" required>
          <?php foreach ($states as $state): ?>
            <option value=<?= $state; ?>>
              <?= $state ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="actions">
        <button type="button" onclick="handleUpdateProfile(event)">Save Changes</button>
        <button type="reset">Reset</button>
      </div>
    </form>

    <div class="form-group address-group">
      <label>Additional Addresses:</label>
      <div id="additionalAddresses">
        <?php foreach ($addresses as $address): ?>
          <div id="address-<?= $address['address_id']; ?>" style="margin-bottom: 1em;">
            <div><strong>Address:</strong> <?= htmlspecialchars($address['address']); ?></div>
            <div><strong>Postcode:</strong> <?= htmlspecialchars($address['postcode']); ?></div>
            <div><strong>State:</strong> <?= htmlspecialchars($address['state']); ?></div>
            <button type="button" onclick="removeAddress(<?= $address['address_id']; ?>)">Remove Address</button>
          </div>
        <?php endforeach; ?>

      </div>
      <button type="button"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        onclick="openModal()">
        Add new address
      </button>
    </div>

    <!-- Pop up modal when user click on add new address btn -->
    <div id="addressModal"
      class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">

        <!-- Close button -->
        <button onclick="closeModal()"
          class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold">
          &times;
        </button>

        <h2 class="text-xl font-semibold mb-4">Add New Address</h2>

        <form id="newAddressForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Address</label>
            <input type="text" id="new-address" name="new-address"
              placeholder="Address"
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
              required>
            <span class="err"></span>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Postcode</label>
            <input type="text" id="new-postcode" name="new-postcode"
              placeholder="Postcode"
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
              required>
            <span class="err"></span>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">State</label>
            <select id="new-state" name="new-state"
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
              required>
              <?php foreach ($states as $state): ?>
                <option value="<?= $state; ?>"><?= $state ?></option>
              <?php endforeach; ?>
            </select>
            <span class="err"></span>
          </div>

          <div class="flex justify-end gap-2 pt-4">
            <button type="button" onclick="closeModal()"
              class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
              Cancel
            </button>
            <button type="submit" onclick="saveNewAddress()"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function openModal() {
      document.getElementById("addressModal").classList.remove("hidden");
    }

    function closeModal() {
      document.getElementById("addressModal").classList.add("hidden");
    }

    const handleUpdateProfile = (e) => {
      e.preventDefault();
      const profileForm = document.querySelector("#profileForm");
      const email = profileForm.querySelector("input[name=email]");
      const password = profileForm.querySelector("input[name=password]");
      const phoneNumber = profileForm.querySelector("input[name=phone_number]");

      const emailParent = email.parentElement;
      const passwordParent = password.parentElement;
      const phoneNumberParent = phoneNumber.parentElement;

      const emailValue = email.value.trim();
      const passwordValue = password.value.trim();
      const phoneNumberVal = phoneNumber.value.trim().replace(/-/g, ""); // remove hyphens

      profileForm.querySelectorAll(".err").forEach(span => span.textContent = "");

      let hasErr = false;

      // Regex for matching
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const passwordRegex = /[^a-zA-Z0-9]/;
      const phoneRegex = /^01[0-46-9]\d{7,8}$/;

      // Validate email
      if (!emailValue) {
        emailParent.querySelector(".err").innerText = "Email cannot be empty";
        hasErr = true;
      } else if (!emailRegex.test(emailValue)) {
        emailParent.querySelector(".err").innerText = "Invalid email format";
        hasErr = true;
      } else {
        emailParent.querySelector(".err").innerText = "";
      }

      // Validated password
      if (!passwordValue) {
        passwordParent.querySelector(".err").innerText = "Password cannot be empty";
        hasErr = true;
      } else if (!passwordRegex.test(passwordValue)) {
        passwordParent.querySelector(".err").innerText = "Password must contain at least 1 special character";
        hasErr = true;
      } else {
        passwordParent.querySelector(".err").innerText = "";
      }

      // Validate phone number
      if (!phoneNumberVal) {
        phoneNumberParent.querySelector(".err").innerText = "Phone number cannot be empty";
        hasErr = true;
      } else if (!phoneRegex.test(phoneNumberVal)) {
        phoneNumberParent.querySelector(".err").innerText = "Invalid phone number format";
        hasErr = true;
      } else {
        phoneNumberParent.querySelector(".err").innerText = "";
      }

      if (hasErr) return;

      fetch("update-profile.php", {
          method: "POST",
          headers: {
            "Content-type": "application/json"
          },
          body: JSON.stringify({
            email: emailValue,
            password: passwordValue,
            phone_number: phoneNumberVal
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("Profile updated successfully")
          } else {
            alert(data.message)
          }
        })
        .catch(err => alert(data.message))
    }


    const saveNewAddress = (saveBtn) => {
      const newAddressForm = document.querySelector("#newAddressForm")
      const address = newAddressForm.querySelector('input[name="new-address"]');
      const postcode = newAddressForm.querySelector('input[name="new-postcode"]');
      const state = newAddressForm.querySelector('select[name="new-state"]');

      const addressValue = address.value.trim();
      const postcodeValue = postcode.value.trim();
      const stateValue = state.value;

      let hasErr = false;

      // Clear prev error messages
      newAddressForm.querySelectorAll(".err").forEach(span => span.textContent = "");

      // Validate address
      if (addressValue) {
        address.parentElement.querySelector(".err").textContent = "Address cannot be empty";
        hasErr = true;
      }

      // Validate postcode (must be 5 digits)
      const postcodeRegex = /^\d{5}$/
      if (!postcodeRegex.test(postcodeVal)) {
        postcode.parentElement.querySelector(".err").textContent = "Postcode must be a 5-digit number";
        hasErr = true;
      }

      // Stop if has error
      if (hasErr) {
        return;
      }

      fetch("add-address.php", {
          method: "POST",
          headers: {
            "Content-type": "application/json"
          },
          body: JSON.stringify({
            address: addressValue,
            postcode: postcodeValue,
            state: stateValue
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const newAddressId = data.address_id
            const additionalAddresses = document.querySelector("#additionalAddresses");
            const newAddressDiv = document.createElement("div");
            newAddressDiv.id = `address-${newAddressId}`;
            newAddressDiv.innerHTML = `
            <div><strong>Address:</strong>${address}</div>
            <div><strong>Postcode:</strong>${postcode}</div>
            <div><strong>State:</strong>${state}</div>
            <button type="button" onclick="removeAddress(${newAddressId})">Remove Address</button>
            `
            additionalAddresses.appendChild(newAddressDiv)
          } else {
            alert("Error saving new address")
          }
        })
        .catch(err => alert("Error saving new address"))

    };

    const removeAddress = (addressId) => {
      console.log(addressId)
      fetch(`remove-address.php?address_id=${addressId}`, {
          method: "GET",
          headers: {
            "Accept": "application/json"
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            document.getElementById(`address-${addressId}`).remove();
            closeModal();
          } else {
            alert("Error occurred when deleting address");
          }
        })
        .catch(err => {
          console.error(err);
          alert("Error occurred when deleting address");
        });
    }

    const updateProfile = () => {
      document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('update_profile.php', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            alert(data.success ? 'Profile updated!' : 'Update failed.');
          })
          .catch(err => {
            console.error('Error:', err);
            alert('Something went wrong.');
          });
      });
    }
  </script>

</body>

</html>
