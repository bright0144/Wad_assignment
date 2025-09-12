function openModal() {
  document.getElementById("addressModal").classList.remove("hidden");
}

function closeModal() {
  document.getElementById("addressModal").classList.add("hidden");
}

const handleUpdateProfile = (e) => {
  e.preventDefault();
  console.log("Clicked");
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

  profileForm
    .querySelectorAll(".err")
    .forEach((span) => (span.textContent = ""));

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
    passwordParent.querySelector(".err").innerText =
      "Password must contain at least 1 special character";
    hasErr = true;
  } else {
    passwordParent.querySelector(".err").innerText = "";
  }

  // Validate phone number
  if (!phoneNumberVal) {
    phoneNumberParent.querySelector(".err").innerText =
      "Phone number cannot be empty";
    hasErr = true;
  } else if (!phoneRegex.test(phoneNumberVal)) {
    phoneNumberParent.querySelector(".err").innerText =
      "Invalid phone number format";
    hasErr = true;
  } else {
    phoneNumberParent.querySelector(".err").innerText = "";
  }

  if (hasErr) return;

  fetch("update-profile.php", {
    method: "PUT",
    headers: {
      "Content-type": "application/json",
    },
    body: JSON.stringify({
      email: emailValue,
      password: passwordValue,
      phone_number: phoneNumberVal,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        alert("Profile updated successfully");
      } else {
        alert(data.message);
      }
    })
    .catch((err) => {
      alert("Error occured when updating profile");
      console.log(err);
    });
};

const saveNewAddress = (e) => {
  e.preventDefault();

  const newAddressForm = document.querySelector("#newAddressForm");
  const address = newAddressForm.querySelector('input[name="new-address"]');
  const postcode = newAddressForm.querySelector('input[name="new-postcode"]');
  const state = newAddressForm.querySelector('select[name="new-state"]');

  const addressValue = address.value.trim();
  const postcodeValue = postcode.value.trim();
  const stateValue = state.value;

  console.log(postcode);

  let hasErr = false;

  // Clear prev error messages
  newAddressForm
    .querySelectorAll(".err")
    .forEach((span) => (span.textContent = ""));

  // Validate address
  if (!addressValue) {
    address.parentElement.querySelector(".err").textContent =
      "Address cannot be empty";
    hasErr = true;
  }

  // Validate postcode (must be 5 digits)
  const postcodeRegex = /^\d{5}$/;
  if (!postcodeRegex.test(postcodeValue)) {
    postcode.parentElement.querySelector(".err").textContent =
      "Postcode must be a 5-digit number";
    hasErr = true;
  }

  // Stop if has error
  if (hasErr) {
    return;
  }

  fetch("add-address.php", {
    method: "POST",
    headers: {
      "Content-type": "application/json",
    },
    body: JSON.stringify({
      address: addressValue,
      postcode: postcodeValue,
      state: stateValue,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        const newAddressId = data.address_id;
        const additionalAddresses = document.querySelector(
          "#additionalAddresses"
        );
        const newAddressDiv = document.createElement("div");
        newAddressDiv.id = `address-${newAddressId}`;
        newAddressDiv.classList =
          "bg-[#333] p-4 rounded-lg shadow flex flex-col gap-1";
        newAddressDiv.innerHTML = `
            <div><strong>Address:</strong>${addressValue}</div>
            <div><strong>Postcode:</strong>${postcodeValue}</div>
            <div><strong>State:</strong>${stateValue}</div>
            <button type="button" class="mt-2 w-fit px-3 py-1 bg-red-500      hover:bg-red-600 rounded-lg text-white font-medium transition" onclick="removeAddress(${newAddressId})"
            >
              Remove Address
            </button>
            `;
        additionalAddresses.appendChild(newAddressDiv);
        alert("Successfully saved the new address");
        closeModal();
      } else {
        alert(data.message);
      }
    })
    .catch((err) => {
      alert("Error saving new address");
      console.log(err);
    });
};

const removeAddress = (addressId) => {
  console.log(addressId);
  fetch(`remove-address.php?address_id=${addressId}`, {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        document.getElementById(`address-${addressId}`).remove();
        alert("Address is removed");
        closeModal();
      } else {
        alert(data.message);
      }
    })
    .catch((err) => {
      console.error(err);
      alert("Error occurred when deleting address");
    });
};
