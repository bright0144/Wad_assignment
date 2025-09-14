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

    orders.forEach((order) => {
      const orderDiv = document.createElement("div");
      orderDiv.setAttribute("id", order.order_id);
      orderDiv.className = "orderCard";

      orderDiv.innerHTML = `
        <p class="orderInfo">Order #${order.order_id} | Date: ${order.order_date} | Status: ${order.order_status} | Total: $${order.total_amt}</p>
        <p class="orderAddress">Shipping Address ${order.address}</p>
      `;

      // Display all order item
      const orderItemList = document.createElement("ul");
      orderItemList.className = "orderItems";
      order.items.forEach((item) => {
        const itemLi = document.createElement("li");
        itemLi.className = "orderItem";

        // Order details
        const orderDetailsSpan = document.createElement("span");
        orderDetailsSpan.textContent = `Product Name: ${item.product_name}, Quantity: ${item.order_item_qty}, Unit Price: ${item.order_item_price}`;

        orderItemList.appendChild(itemLi);
        itemLi.appendChild(orderDetailsSpan);

        // Order item review btn
        const reviewBtn = document.createElement("button");
        reviewBtn.textContent = "Review Order Item";
        reviewBtn.className = "reviewOrderBtn";
        reviewBtn.onclick = function () {
          // Prompt user to enter review
          const comment = prompt("Enter your review:");
          if (comment && comment != "") {
            const productId = item.product_id;

            fetch("order_data.php", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({
                action: "addReview",
                comment: comment,
                productId: productId,
              }),
            })
              .then((res) => res.json())
              .then((result) => {
                if (result.status === "success") {
                  alert(result.message);
                } else {
                  console.log(result.message);
                  alert(result.message);
                }
              })
              .catch((error) => {
                console.error("Error submitting review:", error);
              });
          } else {
            //avoid empty string issue
            alert("invalid comment. Please try again.");
          }
        };
        itemLi.appendChild(reviewBtn);
      });

      orderDiv.appendChild(orderItemList);

      const buttonContainer = document.createElement("div");
      buttonContainer.className = "orderButtonContainer";

      // Cancel order btn
      const currentDate = new Date();
      const oneDay = 24 * 60 * 60 * 1000;

      const orderDate = new Date(order.order_date);
      const timeDiff = currentDate - orderDate;

      // Only allow user to cancel the order if the order date is within one day and the status is pending
      if (timeDiff <= oneDay && order.order_status === "pending") {
        const cancelBtn = document.createElement("button");
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
                if (result.status === "success") {
                  alert(result.message);
                  window.location.reload();
                } else {
                  alert(result.message);
                }
              })
              .catch((error) => {
                console.error("Cancel failed, ", error);
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
