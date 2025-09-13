// Load the cards when initializing dom
window.onload = function () {
  //Initial function call to fetch cards
  fetchCardFromDB();
  fetchCategoryFromDB();
};

// Function to fetch all category
function fetchCategoryFromDB() {
  fetch("./fetch_category.php", {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        console.log("Error fetching category");
      } else {
        return response.json();
      }
    })
    .then((result) => {
      const allCategory = result.all_category;
      const categorySelectElement = document.querySelector(".category-select");
      allCategory.forEach((category) => {
        const categoryOption = document.createElement("option");
        categoryOption.setAttribute("value", category.category_id);
        categoryOption.textContent = category.category_name;
        categorySelectElement.appendChild(categoryOption);
      });
    })
    .catch((err) => {
      console.log(err);
      alert("Error fetching category from database");
    });
}

//cards
const productsContainer = document.getElementById("products-container");
//Pagnination Page
let currentPage = 1;

//Search bar input
const searchInput = document.getElementById("searchInput");

// Category select input
const categorySelect = document.getElementById("categorySelect");

// Min and max price input
const minPriceInput = document.getElementById("minPriceInput");
const maxPriceInput = document.getElementById("maxPriceInput");

// Sort item and sort type select
const sortItemSelect = document.getElementById("sortItemSelect");
const sortTypeSelect = document.getElementById("sortTypeSelect");

// When user stop enter for 1 second, the specific cards  will be fetched
//according to input given
const debouceFunction = (fn, ms) => {
  let searchTimeOut;

  return (...args) => {
    clearTimeout(searchTimeOut);
    searchTimeOut = setTimeout(() => {
      fn(...args);
    }, ms);
  };
};

// Debounce the fetch for 1second
const debouncedFetchCard = debouceFunction(fetchCardFromDB, 1000);

// Add the required event listener to input and select field
searchInput.addEventListener("input", () => {
  currentPage = 1; // reset to first page on new search
  debouncedFetchCard(
    categorySelect.value,
    minPriceInput.value,
    maxPriceInput.value,
    sortItemSelect.value,
    sortTypeSelect.value,
    searchInput.value.trim()
  );
});

categorySelect.addEventListener("change", () => {
  currentPage = 1;
  debouncedFetchCard(
    categorySelect.value,
    minPriceInput.value,
    maxPriceInput.value,
    sortItemSelect.value,
    sortTypeSelect.value,
    searchInput.value.trim()
  );
});

minPriceInput.addEventListener("input", () => {
  currentPage = 1;
  debouncedFetchCard(
    categorySelect.value,
    minPriceInput.value,
    maxPriceInput.value,
    sortItemSelect.value,
    sortTypeSelect.value,
    searchInput.value.trim()
  );
});

maxPriceInput.addEventListener("input", () => {
  currentPage = 1;
  debouncedFetchCard(
    categorySelect.value,
    minPriceInput.value,
    maxPriceInput.value,
    sortItemSelect.value,
    sortTypeSelect.value,
    searchInput.value.trim()
  );
});

sortItemSelect.addEventListener("change", () => {
  currentPage = 1;
  debouncedFetchCard(
    categorySelect.value,
    minPriceInput.value,
    maxPriceInput.value,
    sortItemSelect.value,
    sortTypeSelect.value,
    searchInput.value.trim()
  );
});

sortTypeSelect.addEventListener("change", () => {
  currentPage = 1;
  debouncedFetchCard(
    categorySelect.value,
    minPriceInput.value,
    maxPriceInput.value,
    sortItemSelect.value,
    sortTypeSelect.value,
    searchInput.value.trim()
  );
});

//Fetch card function(search bar input, pagination page  default 1)
async function fetchCardFromDB(
  category,
  minPrice,
  maxPrice,
  sortItem,
  sortType,
  searchQuery = "",
  page = 1
) {
  try {
    // Create the search query
    const params = new URLSearchParams();

    // append the necessary params
    params.append("page", page);

    // Initial render input might be empty
    searchQuery && params.append("search", searchQuery);
    category && params.append("category", category);
    sortItem && params.append("sortItem", sortItem);
    sortType && params.append("sortType", sortType);

    if (minPrice !== null && minPrice !== undefined && minPrice.length > 0) {
      params.append("minPrice", minPrice);
    }
    if (maxPrice !== null && maxPrice !== undefined && maxPrice.length > 0) {
      params.append("maxPrice", maxPrice);
    }
    //fetch card url
    const response = await fetch(`fetch_products.php?${params.toString()}`);

    const responseJson = await response.json();
    // Access the cards array
    const cards = responseJson.cards;

    // Access the total row count
    const totalRows = responseJson.total_rows;

    console.log("Cards:", cards);
    console.log("Total Rows:", totalRows);
    productsContainer.innerHTML = "";

    //no cards found in database
    if (cards.length === 0) {
      productsContainer.classList.add("not-found");
      productsContainer.innerHTML =
        '<span class="no-products-found">No products found for your search.</span>';
      return;
    } else {
      productsContainer.classList.remove("not-found");
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
      const params = new URLSearchParams({
        product_id: card.product_id,
        product_name: card.product_name,
        product_description: card.product_desc,
        product_price: card.product_price,
        product_quantity: card.stock_qty,
        product_img: card.product_img,
      });

      const cardURL = `../selectedProduct/index.php?${params.toString()}`;

      product.innerHTML = `
                        <div class="card-img-container">
                          <img class="card-image" src="/Wad_assignment/image/product-image/${card.product_img}" alt="${card.product_name}"  />
                        </div>
                        <div class="card-details">
                          <span class="card-name">${card.product_name}</span>
                          <span class="card-category">${card.category_name}</span>
                          <span class="card-price">RM ${card.product_price}</span> 
                          <span class="card-stock">Stock: ${card.stock_qty} left</span>

                          <a href="${cardURL}" class="check-card-btn">
                          Check Product
                          </a>                
                        </div>
                        `;

      productsContainer.appendChild(product);
    });

    pagination(totalRows);
    // http://localhost:8081/Wad_assignment/product/productList/fetch_products.php?page=1&search=po&category=1&sortItem=product_name&sortType=asc&minPrice=&maxPrice=
  } catch (error) {
    console.error("Error : ", error);
    productsContainer.innerHTML =
      '<p  class="no-products-found">Error connecting to the database</p>';
  } finally {
    window.scrollTo(0, 0);
  }
}

//Pagination button and feature
function pagination(totalRows) {
  const paginationContainer = document.getElementById("pagination-container");

  paginationContainer.innerHTML = "";

  if (currentPage > 1) {
    const prevButton = document.createElement("button");
    prevButton.textContent = "Previous";
    prevButton.addEventListener("click", function () {
      currentPage--;
      debouncedFetchCard(
        categorySelect.value,
        minPriceInput.value,
        maxPriceInput.value,
        sortItemSelect.value,
        sortTypeSelect.value,
        searchInput.value,
        currentPage
      );
    });
    paginationContainer.appendChild(prevButton);
  }

  if (totalRows > currentPage * 9) {
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.addEventListener("click", () => {
      currentPage++;
      debouncedFetchCard(
        categorySelect.value,
        minPriceInput.value,
        maxPriceInput.value,
        sortItemSelect.value,
        sortTypeSelect.value,
        searchInput.value,
        currentPage
      );
    });
    paginationContainer.appendChild(nextButton);
  }
}
