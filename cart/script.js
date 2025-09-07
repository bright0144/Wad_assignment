document.addEventListener("DOMContentLoaded", () => {
    const cartContainer = document.getElementById("cart-items-container");
    const totalPriceDisplay = document.getElementById("total-price");
    const checkoutBtn = document.getElementById("checkout-btn");

    // Fetch and render all cart items
    fetchCartItems();
    loadAddresses()


    async function fetchCartItems() {
        const response = await fetch("cart_api.php", {
            method: "GET",
        });

        const items = await response.json();
        cartContainer.innerHTML = "";

        if (items.status === "error" || items.length === 0) {
            cartContainer.innerHTML = `<p>${items.message || "Your cart is empty."}</p>`;
            return;
        }

        items.forEach(item => {
            const itemDiv = document.createElement("div");

            itemDiv.innerHTML = `
                <input type="checkbox" class="item-select-checkbox" 
                       data-cart-id="${item.cart_item_id}" 
                       data-product-id="${item.product_id}"
                       data-price="${item.cart_item_price}" 
                       data-qty="${item.cart_item_qty}" />
                <p><strong>Product Name:</strong> ${item.product_name}</p>
                <p><strong>Price:</strong> $${item.cart_item_price}</p>
                <div>
                    <strong>Quantity:</strong>
                    <button class="decrease-btn" data-id="${item.cart_item_id}">–</button>
                    <input type="number" id="qty-${item.cart_item_id}" value="${item.cart_item_qty}" min="1" style="width: 50px; text-align: center;" disabled />
                    <button class="increase-btn" data-id="${item.cart_item_id}">+</button>
                </div>
                <button class="delete-btn" data-id="${item.cart_item_id}">Remove</button>
                <hr>
            `;

            cartContainer.appendChild(itemDiv);
        });

        updateTotalPrice();
    }

    // Function to calculate and update the total price
    function updateTotalPrice() {
        let total = 0;
        const checkboxes = document.querySelectorAll('.item-select-checkbox');
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const price = parseFloat(checkbox.dataset.price);
                const qty = parseInt(checkbox.dataset.qty);
                total += price * qty;
            }
        });
        totalPriceDisplay.textContent = `$${total.toFixed(2)}`;
    }

    // Handle increase, decrease, delete, and checkbox change
    cartContainer.addEventListener("click", async (e) => {
        const target = e.target;
        const cartItemId = target.dataset.id;
        
        if (target.classList.contains("increase-btn")) {
            const qtyInput = document.getElementById(`qty-${cartItemId}`);
            let currentQty = parseInt(qtyInput.value);
            const newQty = currentQty + 1;
            await updateCartItem(cartItemId, newQty);
        }

        if (target.classList.contains("decrease-btn")) {
            const qtyInput = document.getElementById(`qty-${cartItemId}`);
            let currentQty = parseInt(qtyInput.value);
            if (currentQty > 1) {
                const newQty = currentQty - 1;
                await updateCartItem(cartItemId, newQty);
            } else {
                if (confirm("Are you sure you want to remove this item?")) {
                    await deleteCartItem(cartItemId);
                }
            }
        }

        if (target.classList.contains("delete-btn")) {
            if (confirm("Are you sure you want to remove this item?")) {
                await deleteCartItem(cartItemId);
            }
        }
    });

    cartContainer.addEventListener("change", (e) => {
        if (e.target.classList.contains("item-select-checkbox")) {
            updateTotalPrice();
        }
    });

 async function loadAddresses() {
    try {
        const response = await fetch("fetch_address.php", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        });

        const data = await response.json();

        const addressSelect = document.getElementById("addressSelect");
        addressSelect.innerHTML = ""; // Clear previous options

        // ✅ Fix: Access the correct property
        const addresses = data.addresses;

        if (!Array.isArray(addresses) || addresses.length === 0) {
            addressSelect.innerHTML = `<option value="">No addresses available</option>`;
            return;
        }

        addresses.forEach((address) => {
            const option = document.createElement("option");
            option.value = address.address_id;
            option.textContent = address.address;
            addressSelect.appendChild(option);
        });

    } catch (error) {
        console.error("Error loading addresses:", error);
        document.getElementById("addressSelect").innerHTML = `<option value="">Failed to load</option>`;
    }
}



    // Event listener for the Checkout button
    checkoutBtn.addEventListener("click", async () => {
        const selectedItems = [];
        const checkboxes = document.querySelectorAll('.item-select-checkbox:checked');
        
        checkboxes.forEach(checkbox => {
            selectedItems.push({
                cart_item_id: checkbox.dataset.cartId,
                product_id: checkbox.dataset.productId,
                cart_item_qty: parseInt(checkbox.dataset.qty),
                cart_item_price: parseFloat(checkbox.dataset.price)
            });
        });

        if (selectedItems.length === 0) {
            alert("Please select at least one item to checkout.");
            return;
        }

       const selectedAddressId = document.getElementById("addressSelect").value;

if (!selectedAddressId) {
    alert("Please select a delivery address.");
    return;
}

    console.log(selectedAddressId);
    
     const response = await fetch("cart_api.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
    },
    body: JSON.stringify({ 
        items: selectedItems, 
        address_id: selectedAddressId
    }),
});


        const result = await response.json();
        alert(result.message);
        
        if (result.status === "success") {
            fetchCartItems(); // Refresh the cart to show it's now empty
        }
    });


    // Update item quantity in DB and re-render cart
    async function updateCartItem(cartItemId, newQuantity) {
        const response = await fetch("cart_api.php", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ cart_item_id: cartItemId, cart_item_qty: newQuantity }),
        });

        const result = await response.json();

        if (result.status === "success") {
            // Update the data attributes and input value for the updated item
            const checkbox = document.querySelector(`[data-cart-id="${cartItemId}"]`);
            if (checkbox) {
                checkbox.dataset.qty = newQuantity;
            }
            const qtyInput = document.getElementById(`qty-${cartItemId}`);
            if (qtyInput) {
                qtyInput.value = newQuantity;
            }
            updateTotalPrice();
        } else {
            console.error("Failed to update:", result.message);
        }
    }

    // Delete item from cart and re-render cart
    async function deleteCartItem(cartItemId) {
        const response = await fetch("cart_api.php", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ cart_item_id: cartItemId }),
        });

        const result = await response.json();

        if (result.status === "success") {
            fetchCartItems(); // Refresh the cart
        } else {
            console.error("Failed to delete:", result.message);
        }
    }
});
