document.addEventListener("DOMContentLoaded", () => {
    const cartContainer = document.getElementById("cart-items-container");
    const totalPriceDisplay = document.getElementById("total-price");
    const checkoutBtn = document.getElementById("checkout-btn");

    // Fetch and render all cart items
    fetchCartItems();

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

        // Send a POST request to place the order
        const response = await fetch("cart_api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ items: selectedItems }),
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
