// Get all labels
const inputLabels = document.querySelectorAll("label");

inputLabels.forEach((label) => {
  const inputField = label.querySelector("input");
  const errSpan = label.querySelector("span.err-msg");

  // Clear each input field's error message when user start typing
  inputField.addEventListener("input", () => (errSpan.innerHTML = ""));
});

// Clear even listener when unload
window.addEventListener("unload", () => {
  inputLabels.forEach((label) => {
    const inputField = label.querySelector("input");

    // Clear each input field's error message when user start typing
    removeEventListener(inputField);
  });
});
