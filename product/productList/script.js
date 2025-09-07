
    document.addEventListener("DOMContentLoaded", function () {
        const productsContainer = document.getElementById("products-container");
        const searchInput = document.getElementById("searchInput");
        const searchButton = document.getElementById("searchButton");

        let currentPage = 1;

        async function fetchProducts(searchQuery = '', page = 1) {
            try {
                const response = await fetch(`fetch_products.php?search=${encodeURIComponent(searchQuery)}&page=${page}`);

                if (!response.ok) {
                    productsContainer.innerHTML = '<p class="no-products-found">Error connecting to the database or no products found.</p>';
                    return;
                }

                const products = await response.json();
                productsContainer.innerHTML = ''; // Clear previous products

                if (products.length === 0) {
                    productsContainer.innerHTML = '<p class="no-products-found">No products found for your search.</p>';
                    return;
                }

                products.forEach((product) => {
                    const card = document.createElement("div");
                    card.className = "product-card";

                    const imageUrl = `https://placehold.co/400x400/1f2937/FFFFFF?text=${encodeURIComponent(
                        product.product_name
                    )}`;

                     const productUrl = `../selectedProduct/index.php?product_id=${product.product_id}&product_name=${product.product_name}&product_description=${product.product_desc}
                     &product_price=${product.product_price}&product_quantity=${product.stock_qty}`;


                    card.innerHTML = `
                        <img src="${imageUrl}" alt="${product.product_name}" class="product-image" />
                        <h2 class="product-name">${product.product_name}</h2>
                        <p class="product-description">${product.product_desc}</p>
                        
                        <div class="product-details-container">
                            <div class="price-stock-container">
                                <span class="product-price">$${parseFloat(product.product_price).toFixed(2)}</span>
                                <span class="product-stock">Stock: ${product.stock_qty}</span>
                            </div>
                            <button class="wishlist-button">
                                <span>&#x2764;</span>
                            </button>
                        </div>
                        <a href="${productUrl}" class="check-product-btn">
                        Check Product
                        </a>                    `;

                    productsContainer.appendChild(card);
                });

                // Add pagination buttons
                addPaginationButtons(products.length);

            } catch (error) {
                productsContainer.innerHTML = `
                    <div class="error-message">
                        <p>An error occurred: ${error.message}</p>
                        <p>Please check your database connection and the 'fetch_products.php' file.</p>
                    </div>
                `;
                console.error("Error fetching products:", error);
            }
        }

        function addPaginationButtons(productCount) {
            const paginationContainer = document.createElement("div");
            paginationContainer.className = "pagination-container";

            if (currentPage > 1) {
                const prevButton = document.createElement("button");
                prevButton.textContent = "Previous";
                prevButton.addEventListener("click", () => {
                    currentPage--;
                    fetchProducts(searchInput.value, currentPage);
                });
                paginationContainer.appendChild(prevButton);
            }

            // Only show next button if there are products on the current page (meaning there might be more)
            if (productCount === 10) {
                const nextButton = document.createElement("button");
                nextButton.textContent = "Next";
                nextButton.addEventListener("click", () => {
                    currentPage++;
                    fetchProducts(searchInput.value, currentPage);
                });
                paginationContainer.appendChild(nextButton);
            }

            productsContainer.appendChild(paginationContainer);
        }

        // Add event listener for search input
        let searchTimeout;
        searchInput.addEventListener("input", (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1; // Reset to first page on new search
                fetchProducts(e.target.value);
            }, 500); // Debounce search
        });

        // Initial call to fetch and display products
        fetchProducts();
    });
