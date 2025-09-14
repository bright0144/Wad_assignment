// Clear all input when user is typing
document.querySelectorAll("input, textarea").forEach((ele) => {
  ele.addEventListener("keyup", (e) => {
    e.target.parentElement.querySelector(".err-msg").textContent = "";
  });
});

function handleSubmitContact(e) {
  e.preventDefault();
  const contactForm = document.getElementById("contactForm");

  // Get all input field
  const nameField = contactForm.querySelector(".nameField");
  const emailField = contactForm.querySelector(".emailField");
  const messageField = contactForm.querySelector(".messageField");

  // Retrieve all values
  const nameInput = nameField.querySelector("input[name=name]").value.trim();
  const emailInput = emailField.querySelector("input[name=email]").value.trim();
  const messageInput = messageField
    .querySelector("textarea[name=message]")
    .value.trim();

  let hasErr = false;

  // Validate them
  if (!nameInput.length) {
    nameField.querySelector(".err-msg").textContent = "Name cannot be empty";
    hasErr = true;
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailInput.length) {
    emailField.querySelector(".err-msg").textContent = "Email cannot be empty";
    hasErr = true;
  } else if (!emailRegex.test(emailInput)) {
    console.log(emailField);
    emailField.querySelector(".err-msg").textContent = "Invalid email format";
    hasErr = true;
  }

  if (!messageInput.length) {
    messageField.querySelector(".err-msg").textContent =
      "Message cannot be empty";
    hasErr = true;
  }

  if (!hasErr) {
    alert("Your contact information has been sent!");
  }
}
