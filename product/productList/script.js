// Load the cards when initializing dom
window.onload = function () {
  //Initial function call to fetch cards
  fetchCardFromDB();
  // fetchCategoryFromDB();

  //cards
  const productsContainer = document.getElementById("products-container");
  //Pagnination Page
  let currentPage = 1;

  //Search bar input
  const searchInput = document.getElementById("searchInput");

  // When user stop enter for 1 second, the specific cards  will be fetched
  //according to input given in search bar
  let searchTimeOutTime;
  searchInput.addEventListener("input", (e) => {
    //Clear initial timer
    clearTimeout(searchTimeOutTime);
    //Set a new timer
    searchTimeOutTime = setTimeout(() => {
      currentPage = 1;
      //Fetch cards after 1 seconds
      fetchCardFromDB(e.target.value);
    }, 1000);
  });

  //Fetch card function(search bar input, pagination page  default 1)
  async function fetchCardFromDB(searchQuery = "", page = 1) {
    try {
      //fetch card url
      const response = await fetch(
        `fetch_products.php?search=${encodeURIComponent(
          searchQuery
        )}&page=${page}`
      );

      const cards = await response.json();
      productsContainer.innerHTML = "";

      //no cards found in database
      if (cards.length === 0) {
        productsContainer.innerHTML =
          '<p  class="no-products-found">No products found for your search.</p>';
        return;
      }

      //Render cards
      cards.forEach((card) => {
        const product = document.createElement("div");
        //Single card container
        product.className = "card";
        //Card image
        const imageUrl = `https://placehold.co/400x400/1f2937/FFFFFF?text=${encodeURIComponent(
          card.product_name
        )}`;
        //Navigate to card details
        const cardURL = `../selectedProduct/index.php?product_id=${card.product_id}&product_name=${card.product_name}&product_description=${card.product_desc}
                     &product_price=${card.product_price}&product_quantity=${card.stock_qty}`;

        product.innerHTML = `
                        <img class="card-image" src="${imageUrl}" alt="${card.product_name}"  />
                        <h2 class="card-name">${card.product_name}</h2>
                        
                            <div class="card-bottom-container">
                                <span class="card-price">RM ${card.product_price}</span> 
                                <span class="card-stock">Stock: ${card.stock_qty} left</span>
                            </div>

                        <a href="${cardURL}" class="check-card-btn">
                        Check Product
                        </a>                    `;

        productsContainer.appendChild(product);
      });

      pagination(cards.length);
    } catch (error) {
      console.error("Error : ", error);
      productsContainer.innerHTML =
        '<p  class="no-products-found">Error connecting to the database</p>';
    }
  }

  //Pagination button and features
  function pagination(cardCount) {
    const paginationContainer = document.createElement("div");
    paginationContainer.className = "pagination-container";

    //Current Page not first page display previous button
    if (currentPage > 1) {
      const prevButton = document.createElement("button");
      prevButton.textContent = "Previous";
      prevButton.addEventListener("click", function () {
        currentPage--;
        fetchCardFromDB(searchInput.value, currentPage);
      });
      paginationContainer.appendChild(prevButton);
    }

    if (cardCount === 10) {
      const nextButton = document.createElement("button");
      nextButton.textContent = "Next";
      nextButton.addEventListener("click", () => {
        currentPage++;
        fetchCardFromDB(searchInput.value, currentPage);
      });
      paginationContainer.appendChild(nextButton);
    }

    productsContainer.appendChild(paginationContainer);
  }
};
