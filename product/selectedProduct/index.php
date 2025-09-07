<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Details</title>
  </head>
  <body>
    <div class="main-container">
      <h1 class="page-title">Product Details</h1>
      <div id="product-details-container">
        <!-- Product details will be dynamically inserted here -->
      </div>
      
      <div class="reviews-section">
        <h3>Product Reviews</h3>
        <div id="product-reviews-container">
          <!-- Reviews will be dynamically inserted here -->
        </div>
      </div>
      
      <div id="status-message"></div>
    </div>

    <script>
      // This function fetches and displays product reviews.
      async function fetchAndRenderReviews(productId) {
        const reviewsContainer = document.getElementById("product-reviews-container");
        if (!reviewsContainer) return;

        try {
          const response = await fetch(`getComment.php?product_id=${productId}`);
          const reviews = await response.json();

          reviewsContainer.innerHTML = '';

          if (reviews.status === 'error' || reviews.length === 0) {
            reviewsContainer.innerHTML = `<p>No reviews yet. Be the first to leave a comment!</p>`;
            return;
          }

          reviews.forEach(review => {
            const reviewDiv = document.createElement('div');
            reviewDiv.className = 'review-item';
            reviewDiv.innerHTML = `
                <p><strong>${review.username}</strong> on ${new Date(review.product_review_date).toLocaleDateString()}</p>
                <p>${review.comment}</p>
                <hr>
            `;
            reviewsContainer.appendChild(reviewDiv);
          });
        } catch (error) {
          console.error("Failed to fetch reviews:", error);
          reviewsContainer.innerHTML = `<p>Failed to load reviews. Please try again later.</p>`;
        }
      }

      document.addEventListener("DOMContentLoaded", () => {
        // Get the URL parameters from the current page's URL
        const urlParams = new URLSearchParams(window.location.search);

        // Retrieve each piece of information by its parameter name
        const productId = urlParams.get("product_id");
        const productName = urlParams.get("product_name");
        const productDesc = urlParams.get("product_description");
        const productPrice = urlParams.get("product_price");
        const productQty = urlParams.get("product_quantity");

        const detailsContainer = document.getElementById("product-details-container");

        // Check if all necessary parameters exist
        if (productId && productName && productDesc) {
          detailsContainer.innerHTML = `
            <h2>${productName}</h2>
            <p><strong>Product ID:</strong> ${productId}</p>
            <p><strong>Description:</strong> ${productDesc}</p>
            <p><strong>Price:</strong> $${productPrice || "N/A"}</p>
            <p><strong>Stock:</strong> ${productQty || "N/A"}</p>

            <div>
              <strong>Quantity:</strong>
              <button id="decreaseQty">–</button>
              <input type="number" id="quantityInput" value="1" min="1" max="${productQty}" />
              <button id="increaseQty">+</button>
            </div>

            <button id="addToCart-btn">Add to Cart</button>
          `;

          // Quantity controls
          const quantityInput = document.getElementById("quantityInput");
          const increaseBtn = document.getElementById("increaseQty");
          const decreaseBtn = document.getElementById("decreaseQty");

          increaseBtn.addEventListener("click", () => {
            let currentQty = parseInt(quantityInput.value) || 1;
            const maxQty = parseInt(productQty) || 999;
            if (currentQty < maxQty) {
              quantityInput.value = currentQty + 1;
            }
          });

          decreaseBtn.addEventListener("click", () => {
            let currentQty = parseInt(quantityInput.value) || 1;
            if (currentQty > 1) {
              quantityInput.value = currentQty - 1;
            }
          });

          // Add event listener to the "Add to Cart" button
          const addToCartBtn = document.getElementById("addToCart-btn");
          const statusMessageDiv = document.getElementById("status-message");

          if (addToCartBtn) {
            addToCartBtn.addEventListener("click", () => {
              // Create a new FormData object
              const formData = new FormData();
              formData.append("product_id", productId);
              formData.append("product_price", productPrice);
              formData.append("product_quantity", quantityInput.value);
              
              // Use fetch API to send the data to the server
              fetch("addToCart.php", {
                method: "POST",
                body: formData,
              })
                .then((response) => {
                  if (!response.ok) {
                    throw new Error("Network response was not ok");
                  }
                  return response.json();
                })
                .then((data) => {
                  // Display the status message from the server
                  statusMessageDiv.textContent = data.message;
                  window.location.href = "../../cart/index.php";
                })
                .catch((error) => {
                  // Handle network errors
                  statusMessageDiv.textContent = "An error occurred. Please try again.";
                  console.error("Error:", error);
                });
            });
          }
          
          // Call the function to fetch and render reviews after product details are loaded
          fetchAndRenderReviews(productId);
          
        } else {
          detailsContainer.innerHTML = `<p>Product information not found. Please return to the product list.</p>`;
        }
      });
    </script>
  </body>
</html>
