let productId;
window.onload = function () {
  //Get parameter from url
  const urlParams = new URLSearchParams(window.location.search);

  //Get card details from url
  productId = urlParams.get("product_id");
  const productName = urlParams.get("product_name");
  const productDescription = urlParams.get("product_description");
  const productPrice = urlParams.get("product_price");
  const productQuantity = urlParams.get("product_quantity");
  const productImg = urlParams.get("product_img");

  //fetch review
  getReview(productId);

  const cardDetailsContainer = document.getElementById("cardDetailsContainer");

  if (
    productId &&
    productName &&
    productDescription &&
    productPrice &&
    productQuantity &&
    productImg
  ) {
    cardDetailsContainer.innerHTML = `
          <div class="flex flex-col md:flex-row gap-6 p-6 bg-white rounded-xl shadow-lg">
              <!-- Card image -->
          <div class="product-image-container md:w-1/2 flex justify-center">
            <img src="https://placehold.co/400x400/1f2937/FFFFFF?text=hello" alt="${productImg}" class="rounded-lg shadow-md object-cover" />
          </div>
            <!-- Card details -->
            <div class="md:w-1/2 flex flex-col gap-4 justify-center">
              <h2 class="text-2xl font-bold text-gray-800">${productName}</h2>
              <p class="text-gray-600">${productDescription}</p>
              <p class="text-2xl font-semibold">RM ${productPrice}</p>
              <p class="productQuantity text-gray-500">Stock: ${productQuantity}</p>

              <!-- Select quantity -->
              <div class="flex items-center gap-3">
                <span class="font-medium">Quantity:</span>
                <button id="decreaseQuantity" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-lg">–</button>
                <input 
                  type="number" 
                  id="quantityInput" 
                  value="1" 
                  min="1" 
                  max="${productQuantity}" 
                  class="w-16 text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                />
                <button id="increaseQuantity" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-lg">+</button>
              </div>

              <!-- Add to Cart button -->
              <button 
                id="addToCartButton" 
                class="mt-6 w-full md:w-auto px-6 py-3 bg-red-500 text-white font-semibold rounded-lg shadow hover:opacity-70 transition">
                Add to Cart
              </button>
            </div>
          </div>
          `;

    //Modify quantity
    const quantityInput = document.getElementById("quantityInput");
    const increaseButton = document.getElementById("increaseQuantity");
    const decreaseButton = document.getElementById("decreaseQuantity");

    //increase button event
    increaseButton.addEventListener("click", () => {
      let currentQuantity = parseInt(quantityInput.value) || 1;
      const maxQuantity = parseInt(productQuantity);
      if (currentQuantity < maxQuantity) {
        quantityInput.value = currentQuantity + 1;
      }
    });

    //decrease button event
    decreaseButton.addEventListener("click", () => {
      let currentQuantity = parseInt(quantityInput.value) || 1;
      if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
      }
    });

    //add to cart button event
    const addToCartButton = document.getElementById("addToCartButton");
    const addToCartStatus = document.getElementById("statusMessage");

    addToCartButton.addEventListener("click", () => {
      const card = {
        productId: productId,
        productPrice: productPrice,
        productQuantity: quantityInput.value,
      };

      //add to cart request
      fetch("addToCart.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(card),
      })
        .then((response) => response.json())
        .then((response) => {
          //display error message if error
          addToCartStatus.textContent = response.message;
          //Only display navigate button when successfully add to card
          if (response.status === "success") {
            alert("Card added to cart!");
            window.location.href = "../../cart/index.php";
          }
        })
        .catch((error) => {
          addToCartStatus.textContent =
            "An error occurred in database. Please try again.";
          console.error("Error:", error);
        });
    });
  } else {
    //insufficient parameter in url
    cardDetailsContainer.innerHTML = `<p>Product information not found. Please return to the product list.</p>`;
  }
};

//get review
function getReview(productId) {
  const productReviewsContainer = document.getElementById(
    "productReviewsContainer"
  );

  //fetch review request
  fetch(`getComment.php?product_id=${productId}`)
    .then((response) => response.json())
    .then((reviews) => {
      productReviewsContainer.innerHTML = "";

      //No review fetched form database
      if (reviews.status === "error" || reviews.length === 0) {
        productReviewsContainer.innerHTML = `<p>No reviews yet. You can make the purchase and leave a Comment ! </p>`;
        return;
      }

      //console.log("Reviews: ", reviews);

      //display review
      reviews.forEach((review) => {
        const likeIcon = "♥";
        const likeCount = review.like_count;

        const reviewDiv = document.createElement("div");
        reviewDiv.className = "review-item p-4 bg-white rounded-lg shadow mb-4";

        reviewDiv.innerHTML = `
              <p class="font-semibold text-gray-800 mb-1">
                ${review.username} 
                <span class="text-sm text-gray-500">on ${new Date(
                  review.product_review_date
                ).toLocaleDateString()}</span>
              </p>

              <p class="text-gray-700 mb-3">${review.comment}</p>

              <button
                class="likeButton inline-flex items-center gap-1 px-3 py-1 rounded-full border border-gray-300 text-gray-600 hover:bg-red-50 hover:text-red-500 transition"
                data-review-id="${review.product_review_id}"
                data-liked="${review.liked_by_user}"
              >
                ${likeIcon} <span class="font-medium">${likeCount}</span>
              </button>

              <hr class="mt-4 border-gray-200">
            `;
        productReviewsContainer.appendChild(reviewDiv);

        //The like icon is red when user is liked, else white
        const likeButton = reviewDiv.querySelector(".likeButton");
        likeButton.style.color = review.likedByUser ? "red" : "black";
      });
      //event listener for toogle like button after all button is displayed
      toogleLike();
    })

    .catch((error) => {
      console.error("Failed to fetch reviews:", error);
      productReviewsContainer.innerHTML = `<p>Failed to load reviews. Please try again later.</p>`;
    });
}

//toogle like function
function toogleLike() {
  //event listener to al buttons
  const allLikeButtons = document.querySelectorAll(".likeButton");
  allLikeButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      const productReviewId = button.dataset.reviewId;
      const isLiked = button.dataset.liked === "true";

      //toogle like request
      fetch("toogleLike.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          productReviewId,
          isLiked,
        }),
      })
        .then((responseJson) => responseJson.json())
        .then((response) => {
          if (response.status == "success") {
            //fetch review again to update like status
            getReview(productId);
          }
        })
        .catch((error) => {
          alert(
            "An error occurred while toggling like. The page will be refreshed."
          );
          console.error("Error toggling like:", error);
          // window.location.reload();
        });
    });
  });
}
