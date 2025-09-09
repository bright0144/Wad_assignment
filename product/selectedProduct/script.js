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

  //fetch review
  getReview(productId);

  const cardDetailsContainer = document.getElementById("cardDetailsContainer");

  if (
    productId &&
    productName &&
    productDescription &&
    productPrice &&
    productQuantity
  ) {
    cardDetailsContainer.innerHTML = `
            <h2 class ="productName">${productName}</h2>
            <p class ="productDescription">Description: ${productDescription}</p>
            <p class ="productPrice">Price: RM ${productPrice}</p>
            <p class ="productQuantity">Stock: ${productQuantity}</p>

            <div>
              Quantity:
              <button id="decreaseQuantity">–</button>
              <input type="number" id="quantityInput" value="1" min="1" max="${productQuantity}" />
              <button id="increaseQuantity">+</button>
            </div>

            <button id="addToCartButton">Add to Cart</button>
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
        reviewDiv.className = "review-item";
        reviewDiv.innerHTML = `
    <p class="reviewUsername">${review.username} on ${new Date(
          review.product_review_date
        ).toLocaleDateString()}</p>
    <p class="reviewActualComment">${review.comment}</p>
    <button
      class="likeButton"
      data-review-id="${review.product_review_id}"
      data-liked="${review.likedByUser}"
    >
      ${likeIcon} (${likeCount})
    </button>
    <hr>
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
    button.addEventListener("click", () => {
      const productReviewId = button.dataset.reviewId;
      const isLiked = button.dataset.liked === "true";

      //toogle like request
      fetch("toogleLike.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          productReviewId: productReviewId,
          isLiked: isLiked,
        }),
      })
        .then((res) => res.json())
        .then((result) => {
          if (result.status === "success") {
            //fetch review again to update like status
            getReview(productId);
          }
        })
        .catch((error) => {
          alert(
            "An error occurred while toggling like. The page will be refreshed."
          );
          console.error("Error toggling like:", error);
          window.location.reload();
        });
    });
  });
}
