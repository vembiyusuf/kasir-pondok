@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 min-h-screen">
        <!-- Product Selection (Scrollable with min-height) -->
        <div class="bg-white rounded-lg shadow-sm min-h-screen flex flex-col">
            <div class="p-6 border-b flex-shrink-0">
                <h2 class="text-lg font-semibold">Pilih Produk</h2>
            </div>
            <div class="p-6 flex-grow overflow-y-auto" style="max-height: calc(100vh - 120px);">
                <!-- Search -->
                <div class="mb-4">
                    <input type="text" id="search-product" placeholder="Cari produk..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Categories -->
                <div class="space-y-4">
                    @foreach ($categories as $category)
                        <div class="category-section">
                            <h3 class="font-medium text-gray-800 mb-2">{{ $category->name }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($category->products as $product)
                                    <div class="product-card border rounded-lg p-3 cursor-pointer hover:bg-gray-50 transition duration-200"
                                        data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                                        data-product-price="{{ $product->price }}"
                                        data-product-stock="{{ $product->stock }}" data-category="{{ $category->name }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-sm">{{ $product->name }}</h4>
                                                <p class="text-green-600 font-semibold">Rp
                                                    {{ number_format($product->price, 0, ',', '.') }}</p>
                                                @if ($product->servings)
                                                    @foreach (json_decode($product->servings, true) as $serving)
                                                        <p class="text-xs text-gray-500">{{ $serving['name'] }}: Rp
                                                            {{ number_format($serving['price'], 0, ',', '.') }}</p>
                                                    @endforeach
                                                @endif
                                                <p class="text-xs text-gray-500">Stok: {{ $product->stock }}</p>
                                            </div>
                                            <button
                                                class="add-to-cart bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700">
                                                Tambah
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Cart & Checkout (Fixed 100vh) -->
        <div class="bg-white rounded-lg shadow-sm h-screen flex flex-col">
            <div class="p-6 border-b flex-shrink-0">
                <h2 class="text-lg font-semibold">Keranjang Belanja</h2>
            </div>
            <div class="p-6 flex flex-col flex-grow overflow-hidden">
                <!-- Cart Items (Scrollable) -->
                <div class="flex-grow overflow-y-auto mb-4" style="max-height: calc(100vh - 400px);">
                    <div id="cart-items" class="space-y-3">
                        <div id="empty-cart" class="text-gray-500 text-center py-8">
                            Keranjang masih kosong
                        </div>
                    </div>
                </div>

                <!-- Fixed bottom section -->
                <div class="flex-shrink-0 space-y-4">
                    <!-- Total -->
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center text-lg font-semibold">
                            <span>Total:</span>
                            <span id="total-amount">Rp 0</span>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                            <select id="payment-method"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="cash">Tunai</option>
                                <option value="card">Kartu</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                            <input type="number" id="amount-paid" min="0" step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="0">
                        </div>

                        <div id="change-display" class="hidden">
                            <div class="bg-green-50 border border-green-200 rounded-md p-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-green-800">Kembalian:</span>
                                    <span id="change-amount" class="font-semibold text-green-800">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <button id="process-transaction" disabled
                            class="w-full bg-green-600 text-white py-3 px-4 rounded-md font-medium hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition duration-200">
                            Proses Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Serving Type Modal -->
    <div id="serving-type-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96 max-w-90vw">
            <h3 class="text-lg font-semibold mb-4">Pilih Jenis Penyajian</h3>
            <div id="serving-type-options" class="space-y-2 mb-4">
                <!-- Options will be inserted here -->
            </div>
            <div class="flex space-x-2">
                <button id="confirm-serving-type"
                    class="flex-1 bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700">
                    Konfirmasi
                </button>
                <button id="cancel-serving-type"
                    class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96 max-w-90vw">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Transaksi Berhasil!</h3>
                <p class="text-gray-600 mb-4">
                    <span id="success-change-text"></span>
                </p>
                <button id="new-transaction" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700">
                    Transaksi Baru
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let currentProduct = null;

        document.addEventListener('DOMContentLoaded', function() {
            initializeEventListeners();
        });

        function initializeEventListeners() {
            // Product search
            document.getElementById('search-product')?.addEventListener('input', handleProductSearch);

            // Payment amount change
            document.getElementById('amount-paid')?.addEventListener('input', calculateChange);

            // Process transaction button
            document.getElementById('process-transaction')?.addEventListener('click', processTransaction);

            // Modal buttons
            document.getElementById('confirm-serving-type')?.addEventListener('click', confirmServingType);
            document.getElementById('cancel-serving-type')?.addEventListener('click', closeServingTypeModal);
            document.getElementById('new-transaction')?.addEventListener('click', newTransaction);

            // Event delegation for add to cart buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-to-cart')) {
                    e.stopPropagation();
                    handleAddToCart(e.target);
                }
            });
        }

        function handleProductSearch(e) {
            const searchTerm = e.target.value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                const productName = card.dataset.productName.toLowerCase();
                const categorySection = card.closest('.category-section');

                if (productName.includes(searchTerm)) {
                    card.style.display = 'block';
                    categorySection.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Hide empty categories
            document.querySelectorAll('.category-section').forEach(section => {
                const visibleCards = section.querySelectorAll(
                    '.product-card[style*="block"], .product-card:not([style])');
                if (visibleCards.length === 0 && searchTerm !== '') {
                    section.style.display = 'none';
                } else if (searchTerm === '') {
                    section.style.display = 'block';
                }
            });
        }

        function handleAddToCart(button) {
            const card = button.closest('.product-card');
            if (!card) return;

            const productId = card.dataset.productId;
            const productName = card.dataset.productName;
            const productPrice = parseFloat(card.dataset.productPrice);
            const productStock = parseInt(card.dataset.productStock);
            const category = card.dataset.category;

            // Parse servings from data attributes or fetch from server
            const servings = [];
            const servingElements = card.querySelectorAll('.text-xs.text-gray-500');
            servingElements.forEach(el => {
                const text = el.textContent.trim();
                if (text.includes(':') && !text.includes('Stok:')) { // Exclude stock info
                    const [name, priceStr] = text.split(':');
                    const price = parseFloat(priceStr.replace('Rp', '').replace(/\./g, '').trim());
                    if (name && !isNaN(price)) {
                        servings.push({
                            name: name.trim(),
                            price: price
                        });
                    }
                }
            });

            if (productStock <= 0) {
                alert('Stok habis!');
                return;
            }

            currentProduct = {
                id: productId,
                name: productName,
                basePrice: productPrice, // Simpan harga dasar
                price: productPrice, // Harga yang bisa berubah berdasarkan penyajian
                stock: productStock,
                category: category,
                servings: servings
            };

            // Show serving modal if product has servings
            if (servings.length > 0) {
                showServingTypeModal();
            } else {
                addToCart(currentProduct, null);
            }
        }

        function showServingTypeModal() {
            const modal = document.getElementById('serving-type-modal');
            const optionsContainer = document.getElementById('serving-type-options');

            if (!modal || !optionsContainer) return;

            optionsContainer.innerHTML = '';

            // Add default option (base price) - REMOVED STOCK INFO
            const defaultDiv = document.createElement('div');
            defaultDiv.innerHTML = `
        <label class="flex items-center">
            <input type="radio" name="serving_type" value="" class="mr-2" checked>
            <span>Standar - Rp ${currentProduct.basePrice.toLocaleString('id-ID')}</span>
        </label>
    `;
            optionsContainer.appendChild(defaultDiv);

            // Add serving options - REMOVED STOCK INFO
            currentProduct.servings.forEach(serving => {
                const div = document.createElement('div');
                div.innerHTML = `
            <label class="flex items-center">
                <input type="radio" name="serving_type" value="${serving.name}" class="mr-2">
                <span>${serving.name} - Rp ${serving.price.toLocaleString('id-ID')}</span>
            </label>
        `;
                optionsContainer.appendChild(div);
            });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function confirmServingType() {
            const selectedOption = document.querySelector('input[name="serving_type"]:checked');
            if (!selectedOption) {
                alert('Silakan pilih jenis penyajian!');
                return;
            }

            const servingName = selectedOption.value;
            let price = currentProduct.basePrice;

            // Find the selected serving price if not default
            if (servingName) {
                const selectedServing = currentProduct.servings.find(s => s.name === servingName);
                if (selectedServing) {
                    price = selectedServing.price;
                }
            }

            const productToAdd = {
                ...currentProduct,
                price: price,
                servingName: servingName || null
            };

            addToCart(productToAdd, servingName || null);
            closeServingTypeModal();
        }


        function closeServingTypeModal() {
            const modal = document.getElementById('serving-type-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function addToCart(product, servingName) {
            if (!product) return;

            // Check total quantity for this product across all serving types
            const totalQuantityInCart = cart
                .filter(item => item.id === product.id)
                .reduce((total, item) => total + item.quantity, 0);

            if (totalQuantityInCart >= product.stock) {
                alert('Tidak dapat menambah, stok tidak mencukupi!');
                return;
            }

            // Check if item already exists in cart with same serving name
            const existingItemIndex = cart.findIndex(item =>
                item.id === product.id && item.servingName === servingName
            );

            if (existingItemIndex > -1) {
                if (totalQuantityInCart >= product.stock) {
                    alert('Tidak dapat menambah, stok tidak mencukupi!');
                    return;
                }
                cart[existingItemIndex].quantity++;
            } else {
                const newItem = {
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1,
                    servingName: servingName,
                    stock: product.stock
                };
                cart.push(newItem);
            }

            updateCartDisplay();
            updateTotal();
        }

        function updateCartDisplay() {
            const cartContainer = document.getElementById('cart-items');
            if (!cartContainer) return;

            if (cart.length === 0) {
                cartContainer.innerHTML =
                    '<div id="empty-cart" class="text-gray-500 text-center py-8">Keranjang masih kosong</div>';
                return;
            }

            const cartHTML = cart.map((item, index) => `
        <div class="flex items-center justify-between p-3 border rounded-md">
            <div class="flex-1">
                <h4 class="font-medium text-sm">${item.name}</h4>
                ${item.servingName ? `<p class="text-xs text-gray-500">${item.servingName}</p>` : ''}
                <p class="text-xs text-gray-500">Rp ${item.price.toLocaleString('id-ID')}</p>
                <p class="text-xs text-gray-500">Stok tersedia: ${item.stock}</p>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="updateQuantity(${index}, -1)" class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-300">-</button>
                <span class="px-2 text-sm">${item.quantity}</span>
                <button onclick="updateQuantity(${index}, 1)" class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-300">+</button>
                <button onclick="removeFromCart(${index})" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 ml-2">×</button>
            </div>
        </div>
    `).join('');

            cartContainer.innerHTML = cartHTML;
        }

        function updateQuantity(index, change) {
            if (index < 0 || index >= cart.length) return;

            const item = cart[index];
            const newQuantity = item.quantity + change;

            if (newQuantity <= 0) {
                removeFromCart(index);
                return;
            }

            const totalQuantityInCart = cart
                .filter((cartItem, cartIndex) => cartItem.id === item.id && cartIndex !== index)
                .reduce((total, cartItem) => total + cartItem.quantity, 0);

            if (totalQuantityInCart + newQuantity > item.stock) {
                alert('Tidak dapat menambah, stok tidak mencukupi!');
                return;
            }

            cart[index].quantity = newQuantity;
            updateCartDisplay();
            updateTotal();
        }

        function removeFromCart(index) {
            if (index < 0 || index >= cart.length) return;
            cart.splice(index, 1);
            updateCartDisplay();
            updateTotal();
        }

        function updateTotal() {
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const totalElement = document.getElementById('total-amount');
            if (totalElement) {
                totalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
            }

            calculateChange();

            const checkoutBtn = document.getElementById('process-transaction');
            if (checkoutBtn) {
                checkoutBtn.disabled = cart.length === 0;
            }
        }

        function calculateChange() {
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const amountPaidInput = document.getElementById('amount-paid');
            const amountPaid = amountPaidInput ? parseFloat(amountPaidInput.value) || 0 : 0;
            const change = amountPaid - total;

            const changeDisplay = document.getElementById('change-display');
            const changeAmount = document.getElementById('change-amount');

            if (changeDisplay && changeAmount) {
                if (amountPaid > 0 && change >= 0) {
                    changeDisplay.classList.remove('hidden');
                    changeAmount.textContent = `Rp ${change.toLocaleString('id-ID')}`;
                } else {
                    changeDisplay.classList.add('hidden');
                }
            }
        }

        function processTransaction() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const amountPaidInput = document.getElementById('amount-paid');
            const amountPaid = amountPaidInput ? parseFloat(amountPaidInput.value) || 0 : 0;

            if (amountPaid < total) {
                alert('Jumlah pembayaran tidak mencukupi!');
                return;
            }

            const paymentMethodSelect = document.getElementById('payment-method');
            const paymentMethod = paymentMethodSelect ? paymentMethodSelect.value : 'cash';

            const data = {
                items: cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    serving_name: item.servingName || null,
                    price: item.price
                })),
                payment_method: paymentMethod,
                amount_paid: amountPaid,
                total_amount: total
            };

            const processBtn = document.getElementById('process-transaction');
            if (processBtn) {
                processBtn.textContent = 'Memproses...';
                processBtn.disabled = true;
            }

            fetch('{{ route('transactions.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showSuccessModal(data.change || 0);
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses transaksi: ' + error.message);
                })
                .finally(() => {
                    if (processBtn) {
                        processBtn.textContent = 'Proses Transaksi';
                        processBtn.disabled = cart.length === 0;
                    }
                });
        }

        function showSuccessModal(change) {
            const modal = document.getElementById('success-modal');
            const changeText = document.getElementById('success-change-text');

            if (modal && changeText) {
                changeText.textContent = `Kembalian: Rp ${change.toLocaleString('id-ID')}`;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function newTransaction() {
            // Reset cart
            cart = [];
            updateCartDisplay();
            updateTotal();

            // Reset form
            const amountPaidInput = document.getElementById('amount-paid');
            if (amountPaidInput) amountPaidInput.value = '';

            const paymentMethodSelect = document.getElementById('payment-method');
            if (paymentMethodSelect) paymentMethodSelect.value = 'cash';

            const changeDisplay = document.getElementById('change-display');
            if (changeDisplay) changeDisplay.classList.add('hidden');

            // Close modal
            const modal = document.getElementById('success-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Global functions for onclick handlers
        window.updateQuantity = updateQuantity;
        window.removeFromCart = removeFromCart;
    </script>
@endsection
