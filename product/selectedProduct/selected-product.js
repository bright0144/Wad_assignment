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
          <div class="flex flex-col md:flex-row w-full md:w-[70%] mx-auto gap-6 p-6 bg-[#222] rounded-xl shadow-lg max-w-full">
              <!-- Card image -->
          <div class="product-image-container md:aspect-3/4 flex justify-center max-w-[350px] w-full mx-auto">
            <img src="/Wad_assignment/image/product-image/${productImg}" alt="${productName}" class="rounded-lg shadow-md object-cover" />
          </div>
            <!-- Card details -->
            <div class="flex flex-col gap-4 justify-center flex-1">
              <h2 class="text-2xl font-bold text-white">${productName}</h2>
              <p class="text-gray-400">${productDescription}</p>
              <p class="text-2xl font-semibold text-white">RM ${productPrice}</p>
              <p class="productQuantity text-gray-400">Stock: ${productQuantity}</p>

              <!-- Select quantity -->
              <div class="flex items-center gap-3 text-white">
                <span class="font-medium">Quantity:</span>
                <button id="decreaseQuantity" class="px-3 py-1 bg-[#FFA550] hover:opacity-70 rounded-full">–</button>
                <input 
                  type="number" 
                  id="quantityInput" 
                  value="1" 
                  min="1" 
                  max="${productQuantity}" 
                  class="w-14 text-center border-3 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#FFA550] focus:outline-none focus:border-transparent h-[30px]"
                />
                <button id="increaseQuantity" class="px-3 py-1 bg-[#FFA550]  hover:opacity-70 rounded-full">+</button>
              </div>

              <!-- Add to Cart button -->
              <button 
                id="addToCartButton" 
                class="mt-6 w-full self-center px-6 py-3 bg-[#FFA550] text-white font-semibold rounded-lg shadow hover:opacity-70 transition">
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
      fetch("productApi.php", {
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
  fetch(`productApi.php?product_id=${productId}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((response) => {
      productReviewsContainer.innerHTML = "";

      //No review fetched form database
      if (response.reviews.length === 0) {
        productReviewsContainer.innerHTML = `<p class="text-white">No reviews yet. You can make the purchase and leave a Comment ! </p>`;
        return;
      }

      //console.log("Reviews: ", reviews);

      //display review
      response.reviews.forEach((review) => {
        const likeCount = review.like_count;

        const reviewDiv = document.createElement("div");
        reviewDiv.className =
          "review-item p-4 bg-[#222] rounded-lg shadow mb-4";

        reviewDiv.innerHTML = `
              <div class="font-semibold text-white mb-1 flex gap-3 items-center">
                <span>${review.username}</span>
                <div class="w-2 h-2">
                  <img src="/Wad_assignment/image/circle.png" alt="circle logo"/> 
                </div>
                <span>${new Date(
                  review.product_review_date
                ).toLocaleDateString()}</span>
              </div>

              <p class="text-white mb-3">${review.comment}</p>

              <button
                class="likeButton items-center gap-1 px-3 py-1 rounded-full hover:bg-red-200 transition border-3 border-red-500"
                data-review-id="${review.product_review_id}"
                data-liked="${review.liked_by_user}"
              >
              <div class="font-medium text-red-500 flex justify-center items-center gap-2">
              <!-- Love img wrapper -->
                <div class="w-5 h-5">
                  <img class="w-full h-full object-cover" src="/Wad_assignment/image/${
                    review.liked_by_user ? "unlike.png" : "like.png"
                  }" alt="heart icon"/>
                </div>
                <span>${likeCount}</span>
                <span>${review.liked_by_user ? "Unlike" : "Like"}</span>
              </button>
              <hr class="mt-4 border-gray-200">
            `;
        productReviewsContainer.appendChild(reviewDiv);
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
      fetch("productApi.php", {
        method: "PUT",
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
