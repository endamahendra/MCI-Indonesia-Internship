    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">

            <img src="{{ asset('assets/img/mci-logo.png') }}" alt="" width="80" height="40">
                <a class="navbar-brand logo h2 align-self-center">
                    MCI Indonesia
                </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/f/about">Tentang Kami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/f/products">Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/f/news">Artikel</a>
                        </li>
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ...">
                            <div class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </div>
                        </div>
                        </div>
                        @auth
                            @if(auth()->user()->role == 'user')
                                <a type="button" class="nav-icon position-relative text-decoration-none" data-bs-toggle="modal" data-bs-target="#cartModal">
                                    <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                                    <span id="cart-count" class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">0</span>
                                </a>
                                <a class="nav-icon d-none d-lg-inline" href="{{ asset('f/user/dashboard') }}"><i class="fa fa-fw fa-user text-dark mr-3"></i></a>

                                @endif
                                <form id="logoutForm" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">Logout</button>

                                <style>
                                    .logout-btn {
                                        background-color: #007BFF; /* Warna latar belakang */
                                        color: #ffffff; /* Warna teks */
                                        border: none;
                                        padding: 10px 20px;
                                        font-size: 14px;
                                        cursor: pointer;
                                        border-radius: 20px; /* Bulatan sudut */
                                        transition: background-color 0.3s ease; /* Transisi saat hover */
                                    }

                                    .logout-btn:hover {
                                        background-color: #0056b3; /* Warna latar belakang saat hover */
                                    }
                                </style>
                        @else
                        <a href="{{ route('login') }}" class="nav-icon d-none d-lg-inline" style="text-decoration: none;">
                            <i class="fa fa-fw fa-sign-in text-dark mr-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary text-white nav-icon d-none d-lg-inline rounded-pill">
                            Register
                        </a>
                        @endauth
                </div>
            </div>

        </div>
    </nav>
    <!-- Close Header -->
    <!-- Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="cartModalLabel">Keranjang Belanja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="cart"></div>
        </div>
    </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            if (isLoggedIn) {
                const productId = button.getAttribute('data-id');
                const productDescription = button.getAttribute('data-deskripsi');
                const productSKU = button.getAttribute('data-sku');
                const productHarga = button.getAttribute('data-harga');
                const productDiskon = button.getAttribute('data-diskon');
                const productPhoto = button.getAttribute('data-photo');
                const productData = {
                    id: productId,
                    deskripsi: productDescription,
                    harga: productHarga,
                    sku: productSKU,
                    diskon: productDiskon,
                    photo: productPhoto,
                };
                let cart = localStorage.getItem('cart');
                cart = cart ? JSON.parse(cart) : [];

                const existingProductIndex = cart.findIndex(item => item.id === productId);

                if (existingProductIndex !== -1) {
                    cart[existingProductIndex].quantity++;
                } else {
                    cart.push({
                        ...productData,
                        quantity: 1
                    });
                }

                localStorage.setItem('cart', JSON.stringify(cart));
                displayCart();

                        Swal.fire({
                            icon: 'success',
                            title: 'Produk berhasil ditambahkan ke keranjang!',
                            showConfirmButton: false,
                            timer: 2000
                        });
            } else {
                window.location.href = '/login';
            }
        });
    });

    function displayCart() {
        const cart = JSON.parse(localStorage.getItem('cart'));
        const cartDiv = document.getElementById('cart');
        const cartCount = document.getElementById('cart-count');

        if (cart && cart.length > 0) {
            cartDiv.innerHTML = '';

            const cartTable = document.createElement('table');
            cartTable.classList.add('table');

            const tableHead = document.createElement('thead');
            tableHead.innerHTML = `
                <tr>
                <th scope="col" style="border: 0; color: #666; font-size: 0.8rem;"></th>
                    <th scope="col" style="text-align: left; border: 0; font-size: 0.8em;">Nama Produk</th>
                    <th scope="col" style="text-align: center; border: 0; font-size: 0.8em;">Qty</th>
                    <th scope="col" style="text-align: right; border: 0; font-size: 0.8em;">Harga (*qty)</th>
                </tr>
            `;
            cartTable.appendChild(tableHead);

            const tableBody = document.createElement('tbody');
            cartTable.appendChild(tableBody);

            let grandTotal = 0;
            let totalDiscount = 0;
            let totalQuantity = 0;

            cart.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="text-align: center; border: 0; font-size: 0.8em;">
                        <img src="{{ asset('') }}${item.photo}" alt="${item.deskripsi}" style="max-width: 50px; max-height: 50px;">
                    </td>
                    <td style="text-align: left; border: 0; font-size: 0.8em;">
                        ${item.sku}
                        <span class="btn-remove" data-id="${item.id}" style="cursor: pointer; padding: 10px 20px; border: none; background-color: transparent; color: #000000; font-size: 14px; border-radius: 20px;">
                            <i class="fa fa-fw fa-trash" style="color: black;"></i>
                        </span>
                    </td>

                    <td style="text-align: center; border: 0; font-size: 0.8em;" >
                       <span class="btn-decrement" data-id="${item.id}" style="cursor: pointer; padding: 10px 20px; border: none; background-color: transparent; color: #000000; font-size: 14px; cursor: pointer; border-radius: 20px;">
                            <i class="fas fa-minus"></i>
                        </span>
                        ${item.quantity}
                        <span class="btn-increment" data-id="${item.id}" style="cursor: pointer; padding: 10px 20px; border: none; background-color: transparent; color: #000000; font-size: 14px; cursor: pointer; border-radius: 20px;">
                            <i class="fas fa-plus"></i>
                        </span>
                        
                        <style>
                            .btn-decrement:hover,
                            .btn-increment:hover {
                                background-color: transparent  
                                color: #000000 !important;
                            }
                        </style>

                      
                    </td>
                    <td style="text-align: right; border: 0; font-size: 0.8em;">
                    ${(item.harga * item.quantity).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}
                    </td>
                `;
                tableBody.appendChild(row);

                total = item.harga  * item.quantity;
                grandTotal += total;
                totalDiscount += (item.harga * item.diskon / 100) * item.quantity;
                grandTotalafterDiscount = grandTotal - totalDiscount;
                totalQuantity += item.quantity;
            });

            const grandTotalRow = document.createElement('tr');
            grandTotalRow.innerHTML = `
                <td colspan="3" style="; border: 0; font-size: 0.8em; text-align: right;"><strong>Total:</strong></td>
                <td style="text-align: right; border: 0; font-size: 0.8em;">${grandTotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            `;
            tableBody.appendChild(grandTotalRow);

            const totalDiscountRow = document.createElement('tr');
            totalDiscountRow.innerHTML = `
                <td colspan="3" style="; border: 0; font-size: 0.8em; text-align: right;"><strong>Diskon:</strong></td>
                <td style="text-align: right; border: 0; font-size: 0.8em;">${totalDiscount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            `;
            tableBody.appendChild(totalDiscountRow);

            const grandTotalafterDiscountRow = document.createElement('tr');
            grandTotalafterDiscountRow.innerHTML = `
                <td colspan="3" style="; border: 0; font-size: 0.8em; text-align: right;"><strong>Total setelah diskon:</strong></td>
                <td style="text-align: right; border: 0; font-size: 0.8em;"><strong>${grandTotalafterDiscount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</strong></td>
            `;
            tableBody.appendChild(grandTotalafterDiscountRow);

            const checkoutButtonRow = document.createElement('tr');
            checkoutButtonRow.innerHTML = `
                <td colspan="5" class="text-right" style="text-align: right; border: 0; font-size: 0.8em;">
                    <form id="checkout-form" action="/f/orders" method="POST">
                        @csrf
                        <input type="hidden" name="products" value='${JSON.stringify(cart)}'>
                        <button type="button" id="checkout-button" class="btn btn-success">Checkout</button>
                    </form>
                </td>
            `;
            tableBody.appendChild(checkoutButtonRow);

            cartDiv.appendChild(cartTable);
            const checkoutButton = document.getElementById('checkout-button');
                    checkoutButton.addEventListener('click', function() {
                        confirmCheckout().then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('checkout-form').submit();
                            }
                        });
                    });
            // Tambahkan event listener untuk tombol tambah dan kurang
            const incrementButtons = document.querySelectorAll('.btn-increment');
            const decrementButtons = document.querySelectorAll('.btn-decrement');
            const removeButtons = document.querySelectorAll('.btn-remove');
            incrementButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = button.getAttribute('data-id');
                    const cartIndex = cart.findIndex(item => item.id === productId);
                    if (cartIndex !== -1) {
                        cart[cartIndex].quantity++;
                        localStorage.setItem('cart', JSON.stringify(cart));
                        displayCart();
                    }
                });
            });

            decrementButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = button.getAttribute('data-id');
                    const cartIndex = cart.findIndex(item => item.id === productId);
                    if (cartIndex !== -1 && cart[cartIndex].quantity > 1) {
                        cart[cartIndex].quantity--;
                        localStorage.setItem('cart', JSON.stringify(cart));
                        displayCart();
                    }
                });
            });
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = button.getAttribute('data-id');
                    const cartIndex = cart.findIndex(item => item.id === productId);
                    if (cartIndex !== -1) {
                        cart.splice(cartIndex, 1);
                        localStorage.setItem('cart', JSON.stringify(cart));
                        displayCart();
                    }
                });
            });

            // Perbarui jumlah item di ikon keranjang
            cartCount.textContent = totalQuantity;
        } else {
            cartDiv.innerHTML = '<p>Keranjang kosong</p>';
            cartCount.textContent = 0;
        }
    }
    function confirmCheckout() {
                return Swal.fire({
                    title: 'Konfirmasi Checkout',
                    text: 'Apakah Anda yakin ingin checkout?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Checkout',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    return result;
                });
            }
    displayCart();
});
</script>

