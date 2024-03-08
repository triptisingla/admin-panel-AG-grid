    function confirmDelete(event, productId) {
        event.preventDefault();
        if (confirm("Are you sure you want to delete this product?")) {
            document.getElementById('delete-form-' + productId).submit();
        }
    }

    function sortByPriceLowToHigh(){

        const productsContainer = document.getElementById('products-container');
            const products = Array.from(productsContainer.querySelectorAll('.product'));

            products.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price'));
                const priceB = parseFloat(b.getAttribute('data-price'));
                return priceA - priceB;
            });

            // Clear products container
            productsContainer.innerHTML = '';

            // Append sorted products to container
            products.forEach(product => {
                productsContainer.appendChild(product);
            });


        // var products=document.querySelectorAll('.product-details');
        // var sortedProducts=Array.from(products).sort((a,b)=>{
        //     var priceA=parseFloat(a.querySelector('.price span:last-child').innerText);
        //     var priceB=parseFloat(b.querySelector('.price span:last-child').innerText);

        //     return priceA-priceB;
        // });

        // var container=document.querySelector('.product-grid-style');
        // container.innerHTML='';
        // sortedProducts.forEach(product=>{
        //     container.appendChild(
        //         `<div class="col-11 col-sm-6 col-lg-4 col-xl-3">

        //         <div class="product-details">

        //             <div class="product-img">
        //                 <img src="${product.productimage}" alt="${$product.name}">
        //                 <div class="product-cart">
        //                 <a href="#" onclick="confirmDelete(event, ${product.id})"><i class="fa fa-trash"></i></a>
        //                 <form id="delete-form-${product.id}" action="${ route('products.destroy', product) }" method="POST" style="display: none;">
        //                     @csrf
        //                     @method('DELETE')
        //                 </form>

        //                     <a href="{{route('products.edit',$product.id)}}"><i class="fa-solid fa-pen-to-square"></i></a>
        //                     <!-- <a href="#!"><i class="fa-solid fa-message"></i></a> -->
        //                     <a href="${route('products.show',product.id)}"><i class="fa-solid fa-eye"></i></a>
        //                 </div>

        //             </div>

        //             <div class="product-info">
        //                 <a href="#!">${product.name}</a>
        //                 <p class="price text-center m-0">
        //                     <span class="red line-through me-2">$600</span>
        //                     <span>${product.price}</span>
        //                 </p>
        //             </div>

        //         </div>

        //     </div>`
        //     );
        // });
    }

    function sortByPriceHighToLow(){

        const productsContainer = document.getElementById('products-container');
            const products = Array.from(productsContainer.querySelectorAll('.product'));

            products.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price'));
                const priceB = parseFloat(b.getAttribute('data-price'));
                return priceB - priceA;
            });

            // Clear products container
            productsContainer.innerHTML = '';

            // Append sorted products to container
            products.forEach(product => {
                productsContainer.appendChild(product);
            });
    }


    