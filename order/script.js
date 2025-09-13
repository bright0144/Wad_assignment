// On window load
window.onload = function () {
  // Fetch all user order
  fetch("order_data.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        renderOrders(data.orders);
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      alert("Error fetching order");
      console.error("Error fetching orders:", error);
    });

  // Displays all user order
  function renderOrders(orders) {
    const container = document.getElementById("orderContainer");
    if (!orders.length) {
      // Display no order message
      const noOrders = document.createElement("p");
      noOrders.className = "noOrders";
      noOrders.textContent = "You have no orders.";
      container.appendChild(noOrders);
      return;
    }

    const currentDate = new Date();
    const oneDay = 24 * 60 * 60 * 1000;

    orders.forEach(order => {
      const orderDiv = document.createElement('div');
      orderDiv.className = 'orderCard';

      const orderInfo = document.createElement('p');
      orderInfo.className = 'orderInfo';
      orderInfo.textContent = `Order #${order.order_id} | Date: ${order.order_date} | Status: ${order.order_status} | Total: $${order.total_amt}`;
      orderDiv.appendChild(orderInfo);

      const address = document.createElement('p');
      address.className = 'orderAddress';
      address.textContent = `Shipping Address: ${order.address}`;
      orderDiv.appendChild(address);

      const itemList = document.createElement('ul');
      itemList.className = 'orderItems';
      order.items.forEach(item => {
        const itemLi = document.createElement('li');
        itemLi.className = 'orderItem';
        itemLi.textContent = `Product Name: ${item.product_name}, Quantity: ${item.order_item_qty}, Price: $${item.order_item_price}`;
        itemList.appendChild(itemLi);
      });

      orderDiv.appendChild(orderItemList);

      const buttonContainer = document.createElement("div");
      buttonContainer.className = "orderButtonContainer";

      // === Remove Button ===
      const removeBtn = document.createElement('button');
      removeBtn.textContent = "Remove Order";
      removeBtn.className = "removeOrderBtn";
      removeBtn.onclick = function () {
        if (confirm(`Are you sure you want to remove Order #${order.order_id}?`)) {
          fetch(`order_data.php?orderId=${order.order_id}`, {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
          })
          .then(res => res.json())
          .then(result => {
            if (result.status === 'success') {
              alert(result.message);
              orderDiv.remove();
            } else {
              alert(result.message);
            }
          })
          .catch(err => {
            console.error('Delete failed:', err);
            alert("Error deleting order.");
          });
        }
      };

      buttonContainer.appendChild(removeBtn);

      // === Review Button ===
      const reviewBtn = document.createElement('button');
      reviewBtn.textContent = "Review Order";
      reviewBtn.className = 'reviewOrderBtn';
      reviewBtn.onclick = function () {
        // This can be expanded to show a modal or redirect to a form
        const comment = prompt("Enter your review:");
        if (comment) {
          const firstItem = order.items[0]; // Assume reviewing first item
          const productId = firstItem.product_id;

          fetch('order_data.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              action: 'addReview',
              comment: comment,
              productId: productId
            })
          })
          .then(res => res.json())
          .then(result => {
            if(result.status === 'success'){
                alert(result.message);
            } else {
                alert(result.message);
            }
          })
          .catch(err => {
            console.error("Error submitting review:", err);
            alert("Review failed.");
          });
        }
      };
      buttonContainer.appendChild(reviewBtn);

      // === Cancel Button ===
      const orderDate = new Date(order.order_date);
      const timeDiff = currentDate - orderDate;

      if (timeDiff <= oneDay && order.order_status === 'pending') {
        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = "Cancel Order";
        cancelBtn.className = "cancelOrderBtn";
        cancelBtn.onclick = function () {
          // Prompt user to confirm the action
          if (confirm(`Do you want to cancel Order #${order.order_id}?`)) {
            fetch("order_data.php", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({
                action: "cancelOrder",
                orderId: order.order_id,
              }),
            })
              .then((res) => res.json())
              .then((result) => {
                alert(result.message);
                window.location.href();
              })
              .catch((err) => {
                console.error("Cancel failed:", err);
                alert("Error canceling order.");
              });
          }
        };
        buttonContainer.appendChild(cancelBtn);
      }

      orderDiv.appendChild(buttonContainer);
      container.appendChild(orderDiv);
    });
  }
};
