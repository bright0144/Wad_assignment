document.addEventListener("DOMContentLoaded", () => {
    const ordersContainer = document.getElementById("orders-container");

    // Message modal elements
    const messageModal = document.getElementById("message-modal");
    const messageText = document.getElementById("message-text");
    const modalOkBtn = document.getElementById("modal-ok-btn");

    // Confirmation modal elements
    const confirmationModal = document.getElementById("confirmation-modal");
    const confirmText = document.getElementById("confirm-text");
    const confirmYesBtn = document.getElementById("confirm-yes-btn");
    const confirmNoBtn = document.getElementById("confirm-no-btn");

    // Function to show a custom message or confirmation modal
    const showMessage = (text, isConfirmation, onConfirm) => {
        if (isConfirmation) {
            confirmText.textContent = text;
            confirmationModal.style.display = 'flex';

            // Set up button listeners for confirmation
            confirmYesBtn.onclick = () => {
                confirmationModal.style.display = 'none';
                if (onConfirm) onConfirm(true);
            };
            confirmNoBtn.onclick = () => {
                confirmationModal.style.display = 'none';
                if (onConfirm) onConfirm(false);
            };
        } else {
            messageText.textContent = text;
            messageModal.style.display = 'flex';
            modalOkBtn.onclick = () => {
                messageModal.style.display = 'none';
            };
        }
    };

    // Function to fetch orders from the PHP backend
    const fetchOrders = async () => {
        ordersContainer.innerHTML = `<p>Loading orders...</p>`;
        try {
            const response = await fetch('order_index.php', { method: 'GET' });
            const data = await response.json();

            if (data.status === "success") {
                renderOrders(data.orders);
            } else {
                ordersContainer.innerHTML = `<p style="color: red;">Error: ${data.message}</p>`;
            }
        } catch (error) {
            console.error("Error fetching orders:", error);
            ordersContainer.innerHTML = `<p style="color: red;">An error occurred while loading orders. Please try again.</p>`;
        }
    };

    // Function to render the orders on the page
    const renderOrders = (orders) => {
        if (orders.length === 0) {
            ordersContainer.innerHTML = "<p>You have not placed any orders yet.</p>";
            return;
        }

        ordersContainer.innerHTML = ''; // Clear the loading message

        orders.forEach(order => {
            const orderDiv = document.createElement('div');
            orderDiv.style.border = '1px solid #ccc';
            orderDiv.style.padding = '15px';
            orderDiv.style.marginBottom = '10px';

            orderDiv.innerHTML = `
                <h3>Order #${order.order_id}</h3>
                <p><strong>Order Date:</strong> ${new Date(order.order_date).toLocaleString()}</p>
                <p><strong>Order Status:</strong> ${order.order_status}</p>
                <p><strong>Total Amount:</strong> $${parseFloat(order.total_amt).toFixed(2)}</p>
                <p><strong>Shipping Address:</strong> ${order.address}</p>
            `;

            // Check if the order is cancellable and add the button
            if (order.cancellable) {
                const cancelButton = document.createElement('button');
                cancelButton.textContent = "Cancel Order";
                cancelButton.style.marginTop = '10px';
                cancelButton.onclick = () => {
                    showMessage("Are you sure you want to cancel this order?", true, (confirmed) => {
                        if (confirmed) {
                            cancelOrder(order.order_id);
                        }
                    });
                };
                orderDiv.appendChild(cancelButton);
            }

            ordersContainer.appendChild(orderDiv);
        });
    };

    // Function to cancel an order
    const cancelOrder = async (orderId) => {
        try {
            const response = await fetch('order_index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId })
            });
            const data = await response.json();

            if (data.status === "success") {
                showMessage(data.message, false);
                fetchOrders(); // Refresh the list to show the updated status
            } else {
                showMessage(`Cancellation failed: ${data.message}`, false);
            }
        } catch (error) {
            console.error("Error canceling order:", error);
            showMessage("An error occurred during cancellation. Please try again.", false);
        }
    };

    // Initial fetch of orders when the page loads
    fetchOrders();
});
