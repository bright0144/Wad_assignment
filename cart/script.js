window.onload = function () {
  //fetch cart item from DB
  fetchCartFromDB();

  //load user address from db
  loadAddressesFromDB();
};

const cartContainer = document.getElementById("cartContainer");
const totalPriceDisplay = document.getElementById("totalPrice");
const checkoutButton = document.getElementById("checkoutButton");

//Modify cart item quantity
cartContainer.addEventListener("click", function (e) {
  const target = e.target;
  const cartItemId = target.dataset.id;

  //increase quantity and update to database
  if (target.classList.contains("increaseButton")) {
    const quantityInput = document.getElementById(`${cartItemId}`);
    let currentQuantity = parseInt(quantityInput.value);
    const newQuantity = currentQuantity + 1;
    updateCartItem(cartItemId, newQuantity);
  }

  //decrease quantity and update to database
  if (target.classList.contains("decreaseButton")) {
    const quantityInput = document.getElementById(`${cartItemId}`);
    let currentQty = parseInt(quantityInput.value);
    if (currentQty > 1) {
      const newQuantity = currentQty - 1;
      updateCartItem(cartItemId, newQuantity);
    } else {
      //quantity is less than 1 , delete
      if (confirm("Are you sure you want to remove this item?")) {
        deleteCartItem(cartItemId);
      }
    }
  }

  //delete cart item
  if (target.classList.contains("deleteButton")) {
    if (confirm("Are you sure you want to remove this item?")) {
      deleteCartItem(cartItemId);
    }
  }
});

// check checkbox and update the price
cartContainer.addEventListener("change", (e) => {
  if (e.target.classList.contains("itemSelectCheckbox")) {
    updateTotalPrice();
  }
});

// Place Order (checkout button)
checkoutButton.addEventListener("click", function () {
  //place order item
  const selectedItems = [];
  const checkboxes = document.querySelectorAll(".itemSelectCheckbox:checked");

  checkboxes.forEach((checkbox) => {
    selectedItems.push({
      cart_item_id: checkbox.dataset.cartId,
      product_id: checkbox.dataset.productId,
      cart_item_qty: parseInt(checkbox.dataset.quantity),
      cart_item_price: parseFloat(checkbox.dataset.price),
    });
  });

  if (selectedItems.length === 0) {
    alert("Please select at least one item to checkout.");
    return;
  }

  const selectedAddressId = document.getElementById("addressSelection").value;

  if (!selectedAddressId) {
    alert("Please select a delivery address.");
    return;
  }

  fetch("cart_api.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      items: selectedItems,
      address_id: selectedAddressId,
    }),
  })
    .then((responseJson) => responseJson.json())
    .then((response) => {
      alert(response.message);

      if (response.status === "success") {
        fetchCartFromDB();
      }
    })
    .catch((error) => {
      console.error("Error loading addresses from database.", error);
    });
});

//fetch cart from database function
function fetchCartFromDB() {
  fetch("cart_api.php", {
    method: "GET",
  })
    .then((responseJson) => responseJson.json())
    .then((response) => {
      cartContainer.innerHTML = "";

      //no cart fetched from database
      if (response.status === "error" || response.length === 0) {
        cartContainer.innerHTML = `<p>${
          response.message || "Your cart is empty."
        }</p>`;
        return;
      }
      // Create table and header row
      const table = document.createElement("table");
      table.classList.add("cartTable");

      table.innerHTML = `
          <thead>
            <tr>
              <th>Select</th>
              <th>Product Name</th>
              <th>Unit Price (RM)</th>
              <th>Quantity</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
      `;

      const tbody = table.querySelector("tbody");

      //display cart item
      response.items.forEach((cartItem) => {
        const row = document.createElement("tr");

        row.innerHTML = `
          <td>
            <input type="checkbox" class="itemSelectCheckbox" name="itemSelectCheckbox" 
              data-cart-id="${cartItem.cart_item_id}" 
              data-product-id="${cartItem.product_id}"
              data-price="${cartItem.cart_item_price}" 
              data-quantity="${cartItem.cart_item_qty}" />
          </td>
          <td>${cartItem.product_name}</td>
          <td> RM ${cartItem.cart_item_price}</td>
          <td>
            <div class="quantityContainer">
              <button class="decreaseButton" data-id="${cartItem.cart_item_id}">–</button>
              <input type="number" id="${cartItem.cart_item_id}" value="${cartItem.cart_item_qty}" min="1" disabled style="width: 50px; text-align: center;" />
              <button class="increaseButton" data-id="${cartItem.cart_item_id}">+</button>
            </div>
          </td>
          <td>
            <button class="deleteButton" data-id="${cartItem.cart_item_id}">Remove</button>
          </td>
        `;

        tbody.appendChild(row);
      });
      cartContainer.appendChild(table);

      updateTotalPrice();
    })
    .catch((error) => {
      console.error("Error loading addresses from database.", error);
      cartContainer.innerHTML = `<p>Failed to load cart from database. Please try again later or refresh the page.</p>`;
    });
}

//Calculate total price based on checked cart item
function updateTotalPrice() {
  let totalPrice = 0;
  const checkboxes = document.querySelectorAll(".itemSelectCheckbox");
  checkboxes.forEach((checkbox) => {
    if (checkbox.checked) {
      const price = parseFloat(checkbox.dataset.price);
      const quantity = parseInt(checkbox.dataset.quantity);
      totalPrice += price * quantity;
    }
  });
  totalPriceDisplay.textContent = `RM ${totalPrice.toFixed(2)}`;
}

//load address from database function
function loadAddressesFromDB() {
  fetch("fetch_address.php", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((responseJson) => responseJson.json())
    .then((response) => {
      const addressSelection = document.getElementById("addressSelection");
      addressSelection.innerHTML = "";

      const addresses = response.addresses;

      //no address is fetched
      if (addresses.length === 0 || response.status === "error") {
        addressSelection.innerHTML = `<option value="">No addresses available</option>`;
        alert("Please navigate to profile to add address!");
        return;
      }

      //display address
      addresses.forEach((address) => {
        const option = document.createElement("option");
        option.value = address.address_id;
        option.textContent = address.address;
        addressSelection.appendChild(option);
      });
    })
    .catch((error) => {
      console.error("Error loading addresses from database.", error);
      document.getElementById(
        "addressSelection"
      ).innerHTML = `<option value="">Failed to load</option>`;
      alert("Please try again later or refresh page!");
    });
}

//Update cart item quantity to database
function updateCartItem(cartItemId, newQuantity) {
  fetch("cart_api.php", {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      cart_item_id: cartItemId,
      cart_item_qty: newQuantity,
    }),
  })
    .then((responseJson) => responseJson.json())
    .then((response) => {
      if (response.status === "success") {
        const checkbox = document.querySelector(
          `[data-cart-id="${cartItemId}"]`
        );

        // Update to check value
        checkbox.dataset.quantity = newQuantity;

        //update to quantity input
        const quantityInput = document.getElementById(`${cartItemId}`);
        if (quantityInput) {
          quantityInput.value = newQuantity;
        }

        //recalculate price
        updateTotalPrice();
      } else {
        console.error(
          "failed to update quantity to database , ",
          response.error
        );
      }
    })
    .catch((error) => {
      console.error("Error connecting to database", error);
    });
}

//Delete cart item and update database
function deleteCartItem(cartItemId) {
  fetch("cart_api.php", {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ cart_item_id: cartItemId }),
  })
    .then((responseJson) => responseJson.json())
    .then((response) => {
      if (response.status === "success") {
        //update the page
        fetchCartFromDB();
      } else {
        console.error("Failed to delete cart item:", response.message);
      }
    })
    .catch((error) => {
      console.error("Error connecting to database", error);
    });
}
