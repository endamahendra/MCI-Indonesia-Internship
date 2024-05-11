<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
</head>
<body>
    <h2>Order Form</h2>
    <form id="orderForm">
        <div id="productInputs">
            <div class="productInput">
                <label for="product1">Product ID:</label>
                <input type="text" class="productId" name="products[0][id]" value="1">
                <label for="quantity1">Quantity:</label>
                <input type="number" class="productQuantity" name="products[0][quantity]" value="2">
            </div>
        </div>
        <button type="button" id="addProduct">Tambah Produk</button>
        <button type="submit">Submit Order</button>
    </form>

    <div id="response"></div>

    <script>
        document.getElementById("addProduct").addEventListener("click", function() {
            const productInputs = document.getElementById("productInputs");
            const productCount = document.querySelectorAll(".productInput").length;
            const newProductInput = document.createElement("div");
            newProductInput.innerHTML = `
                <div class="productInput">
                    <label for="product${productCount + 1}">Product ID:</label>
                    <input type="text" class="productId" name="products[${productCount}][id]">
                    <label for="quantity${productCount + 1}">Quantity:</label>
                    <input type="number" class="productQuantity" name="products[${productCount}][quantity]">
                    <button type="button" class="removeProduct">Hapus</button>
                </div>
            `;
            productInputs.appendChild(newProductInput);
        });

        document.getElementById("orderForm").addEventListener("click", function(event) {
            if (event.target.classList.contains("removeProduct")) {
                event.target.parentElement.remove();
            }
        });

        document.getElementById("orderForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            fetch('http://127.0.0.1:8000/orders', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("response").innerHTML = JSON.stringify(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
