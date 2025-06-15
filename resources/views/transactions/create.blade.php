@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 min-h-screen">
        <!-- Product Selection -->
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
                                        data-product-stock="{{ $product->stock }}" data-category="{{ $category->name }}"
                                        data-servings="{{ $product->servings }}">
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

        <!-- Cart & Checkout -->
        <div class="bg-white rounded-lg shadow-sm h-screen flex flex-col">
            <div class="p-6 border-b flex-shrink-0">
                <h2 class="text-lg font-semibold">Keranjang Belanja</h2>
            </div>

            <!-- Scrollable Cart Area with Minimum Height for 4 Items -->
            <div class="flex-1 overflow-y-auto border-b border-gray-200" style="min-height: 320px; max-height: 40vh;">
                <div id="cart-items" class="p-4 space-y-3">
                    <!-- Empty cart placeholder -->
                    <div id="empty-cart"
                        class="text-gray-500 text-center py-8 h-full flex flex-col justify-center items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Keranjang masih kosong</span>
                    </div>
                </div>
            </div>

            <!-- Scrollable Checkout Section -->
            <div class="flex-1 overflow-y-auto bg-white" style="min-height: 400px;">
                <div class="p-6">
                    <!-- Discount -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diskon (Rp)</label>
                        <input type="number" id="discount-amount" min="0" step="1000"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="0">
                    </div>

                    <!-- Payment Summary -->
                    <div class="space-y-2 mb-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotal-amount" class="font-medium">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Diskon:</span>
                            <span id="discount-display" class="font-medium">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-semibold border-t pt-2">
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
                            <input type="number" id="amount-paid" min="0" step="1000"
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

                        <!-- Process Transaction Button -->
                        <div class="pt-4 border-t">
                            <button id="process-transaction" disabled
                                class="w-full bg-green-600 text-white py-3 px-4 rounded-md font-medium hover:bg-green-700
                               disabled:bg-gray-300 disabled:cursor-not-allowed transition duration-200 shadow-lg">
                                PROSES TRANSAKSI
                            </button>
                        </div>
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
                <div class="text-left mb-4 space-y-1">
                    <p class="text-gray-600"><span class="font-medium">Subtotal:</span> <span id="success-subtotal">Rp
                            0</span></p>
                    <p class="text-gray-600"><span class="font-medium">Diskon:</span> <span id="success-discount">Rp
                            0</span></p>
                    <p class="text-gray-600"><span class="font-medium">Total:</span> <span id="success-total">Rp 0</span>
                    </p>
                    <p class="text-gray-600"><span class="font-medium">Bayar:</span> <span id="success-paid">Rp 0</span>
                    </p>
                    <p class="text-gray-600"><span class="font-medium">Kembalian:</span> <span id="success-change">Rp
                            0</span></p>
                </div>
                <button id="new-transaction" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700">
                    Transaksi Baru
                </button>
                <button id="print-receipt" class="ml-2 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                    Cetak Struk
                </button>
            </div>
        </div>
    </div>


    <script>
        let cart = [];
        let currentProduct = null;
        let lastTransactionId = null;

        document.addEventListener('DOMContentLoaded', function() {
            initializeEventListeners();
        });

        function initializeEventListeners() {
            // Product search
            document.getElementById('search-product')?.addEventListener('input', handleProductSearch);

            // Payment amount change
            document.getElementById('amount-paid')?.addEventListener('input', calculateChange);

            // Discount amount change
            document.getElementById('discount-amount')?.addEventListener('input', updateTotal);

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
            const servingsData = card.dataset.servings;

            // Parse servings from data attribute
            let servings = [];
            try {
                if (servingsData) {
                    servings = JSON.parse(servingsData);
                }
            } catch (e) {
                console.error('Error parsing servings data:', e);
            }

            if (productStock <= 0) {
                alert('Stok habis!');
                return;
            }

            currentProduct = {
                id: productId,
                name: productName,
                basePrice: productPrice,
                price: productPrice,
                stock: productStock,
                category: category,
                servings: servings
            };

            // Show serving modal if product has servings
            if (servings && servings.length > 0) {
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

            // Add default option (base price)
            const defaultDiv = document.createElement('div');
            defaultDiv.innerHTML = `
                <label class="flex items-center">
                    <input type="radio" name="serving_type" value="" class="mr-2" checked>
                    <span>Standar - Rp ${currentProduct.basePrice.toLocaleString('id-ID')}</span>
                </label>
            `;
            optionsContainer.appendChild(defaultDiv);

            // Add serving options
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
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const discountAmount = parseFloat(document.getElementById('discount-amount')?.value) || 0;
            const total = subtotal - discountAmount;

            // Update display
            document.getElementById('subtotal-amount').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
            document.getElementById('discount-display').textContent = `Rp ${discountAmount.toLocaleString('id-ID')}`;
            document.getElementById('total-amount').textContent = `Rp ${Math.max(total, 0).toLocaleString('id-ID')}`;

            calculateChange();

            const checkoutBtn = document.getElementById('process-transaction');
            if (checkoutBtn) {
                checkoutBtn.disabled = cart.length === 0;
            }
        }

        function calculateChange() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const discountAmount = parseFloat(document.getElementById('discount-amount')?.value) || 0;
            const total = Math.max(subtotal - discountAmount, 0);
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

            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const discountAmount = parseFloat(document.getElementById('discount-amount')?.value) || 0;
            const total = Math.max(subtotal - discountAmount, 0);
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
                    serving_name: item.servingName || null // Make sure this matches backend expectation
                })),
                payment_method: paymentMethod,
                amount_paid: amountPaid,
                discount_amount: discountAmount
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
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        lastTransactionId = data.transaction_id;
                        showSuccessModal(data);
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Terjadi kesalahan saat memproses transaksi');
                })
                .finally(() => {
                    if (processBtn) {
                        processBtn.textContent = 'Proses Transaksi';
                        processBtn.disabled = cart.length === 0;
                    }
                });
        }

        function showSuccessModal(data) {
            const modal = document.getElementById('success-modal');
            if (!modal) return;

            // Update success modal with transaction details
            document.getElementById('success-subtotal').textContent = `Rp ${data.subtotal.toLocaleString('id-ID')}`;
            document.getElementById('success-discount').textContent = `Rp ${data.discount.toLocaleString('id-ID')}`;
            document.getElementById('success-total').textContent = `Rp ${data.total.toLocaleString('id-ID')}`;
            document.getElementById('success-paid').textContent = `Rp ${data.amount_paid.toLocaleString('id-ID')}`;
            document.getElementById('success-change').textContent = `Rp ${data.change.toLocaleString('id-ID')}`;

            // Remove any existing print button event listener
            const oldPrintBtn = document.getElementById('print-receipt');
            if (oldPrintBtn) {
                oldPrintBtn.replaceWith(oldPrintBtn.cloneNode(true));
            }

            // Add event listener to new print button
            const printBtn = document.getElementById('print-receipt');
            if (printBtn) {
                printBtn.addEventListener('click', () => {
                    window.open(`/transactions/${data.transaction_id}/print`, '_blank');
                });
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function newTransaction() {
            // Reset cart
            cart = [];
            updateCartDisplay();
            updateTotal();

            // Reset form
            document.getElementById('discount-amount').value = '';
            document.getElementById('amount-paid').value = '';
            document.getElementById('payment-method').value = 'cash';
            document.getElementById('change-display').classList.add('hidden');

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
