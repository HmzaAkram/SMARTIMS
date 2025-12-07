

<?php $__env->startSection('title', 'Create Sales Order - SMARTIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Create Sales Order
            </h2>
            <p class="mt-1 text-sm text-gray-500">Create a new sales order for your customer</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="<?php echo e(route('company.sales.index', $tenant)); ?>" class="inline-flex items-center rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Sales
            </a>
        </div>
    </div>

    <!-- Sales Order Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="<?php echo e(route('company.sales.store', $tenant)); ?>" method="POST" id="salesOrderForm">
            <?php echo csrf_field(); ?>
            
            <div class="p-6 space-y-6">
                <!-- Customer & Warehouse Info -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer *</label>
                        <select name="customer_id" id="customer_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Customer</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>" <?php echo e(old('customer_id') == $customer->id ? 'selected' : ''); ?>>
                                    <?php echo e($customer->name); ?> - <?php echo e($customer->email); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Warehouse *</label>
                        <select name="warehouse_id" id="warehouse_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Warehouse</option>
                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($warehouse->id); ?>" <?php echo e(old('warehouse_id') == $warehouse->id ? 'selected' : ''); ?>>
                                    <?php echo e($warehouse->name); ?> - <?php echo e($warehouse->location); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="order_date" class="block text-sm font-medium text-gray-700">Order Date *</label>
                        <input type="date" name="order_date" id="order_date" value="<?php echo e(old('order_date', date('Y-m-d'))); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <?php $__errorArgs = ['order_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700">Delivery Date</label>
                        <input type="date" name="delivery_date" id="delivery_date" value="<?php echo e(old('delivery_date')); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <?php $__errorArgs = ['delivery_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Payment Method</option>
                            <option value="cash" <?php echo e(old('payment_method') == 'cash' ? 'selected' : ''); ?>>Cash</option>
                            <option value="credit_card" <?php echo e(old('payment_method') == 'credit_card' ? 'selected' : ''); ?>>Credit Card</option>
                            <option value="bank_transfer" <?php echo e(old('payment_method') == 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                            <option value="cheque" <?php echo e(old('payment_method') == 'cheque' ? 'selected' : ''); ?>>Cheque</option>
                            <option value="online" <?php echo e(old('payment_method') == 'online' ? 'selected' : ''); ?>>Online Payment</option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_terms" class="block text-sm font-medium text-gray-700">Payment Terms</label>
                        <input type="text" name="payment_terms" id="payment_terms" value="<?php echo e(old('payment_terms')); ?>" placeholder="e.g., Net 30" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Order Items Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                    
                    <div class="mb-4">
                        <div class="grid grid-cols-12 gap-4 mb-2 text-sm font-medium text-gray-500">
                            <div class="col-span-5">Item</div>
                            <div class="col-span-2">Quantity</div>
                            <div class="col-span-2">Unit Price</div>
                            <div class="col-span-2">Total</div>
                            <div class="col-span-1"></div>
                        </div>
                        
                        <div id="items-container">
                            <!-- Items will be added here dynamically -->
                            <div class="item-row grid grid-cols-12 gap-4 mb-3" data-index="0">
                                <div class="col-span-5">
                                    <select name="items[0][item_id]" class="item-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        <option value="">Select Item</option>
                                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>" data-price="<?php echo e($item->selling_price ?? $item->unit_price); ?>" data-stock="<?php echo e($item->inventory->quantity ?? 0); ?>">
                                                <?php echo e($item->name); ?> (<?php echo e($item->sku); ?>) - Stock: <?php echo e($item->inventory->quantity ?? 0); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="items[0][quantity]" min="1" value="1" class="quantity-input block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <span class="stock-info text-xs text-gray-500"></span>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="items[0][unit_price]" step="0.01" min="0" class="price-input block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div class="col-span-2">
                                    <input type="text" class="item-total block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm" readonly value="0.00">
                                </div>
                                <div class="col-span-1">
                                    <button type="button" class="remove-item text-red-600 hover:text-red-900">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="add-item" class="mt-3 inline-flex items-center rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                            </svg>
                            Add Item
                        </button>
                    </div>
                </div>

                <!-- Totals Section -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                <textarea name="shipping_address" id="shipping_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"><?php echo e(old('shipping_address')); ?></textarea>
                            </div>
                            
                            <div>
                                <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                                <textarea name="billing_address" id="billing_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"><?php echo e(old('billing_address')); ?></textarea>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"><?php echo e(old('notes')); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Order Summary</h4>
                                
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Subtotal:</span>
                                        <span class="text-sm font-medium" id="subtotal">$0.00</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-3 gap-2">
                                        <div>
                                            <label for="discount" class="block text-xs text-gray-600">Discount</label>
                                            <input type="number" name="discount" id="discount" step="0.01" min="0" value="<?php echo e(old('discount', 0)); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="tax" class="block text-xs text-gray-600">Tax</label>
                                            <input type="number" name="tax" id="tax" step="0.01" min="0" value="<?php echo e(old('tax', 0)); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="shipping_cost" class="block text-xs text-gray-600">Shipping</label>
                                            <input type="number" name="shipping_cost" id="shipping_cost" step="0.01" min="0" value="<?php echo e(old('shipping_cost', 0)); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between border-t border-gray-200 pt-2">
                                        <span class="text-base font-medium text-gray-900">Total Amount:</span>
                                        <span class="text-base font-bold text-gray-900" id="total">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="<?php echo e(route('company.sales.index', $tenant)); ?>" class="rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">
                            Cancel
                        </a>
                        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Create Sales Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemIndex = 1;
        const itemsContainer = document.getElementById('items-container');
        const addItemBtn = document.getElementById('add-item');
        const warehouseSelect = document.getElementById('warehouse_id');
        
        // Load items when warehouse changes
        warehouseSelect.addEventListener('change', function() {
            loadItems();
        });
        
        // Add new item row
        addItemBtn.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'item-row grid grid-cols-12 gap-4 mb-3';
            newRow.setAttribute('data-index', itemIndex);
            
            newRow.innerHTML = `
                <div class="col-span-5">
                    <select name="items[${itemIndex}][item_id]" class="item-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="">Select Item</option>
                        <!-- Items will be loaded dynamically -->
                    </select>
                </div>
                <div class="col-span-2">
                    <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" class="quantity-input block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    <span class="stock-info text-xs text-gray-500"></span>
                </div>
                <div class="col-span-2">
                    <input type="number" name="items[${itemIndex}][unit_price]" step="0.01" min="0" class="price-input block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
                <div class="col-span-2">
                    <input type="text" class="item-total block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm" readonly value="0.00">
                </div>
                <div class="col-span-1">
                    <button type="button" class="remove-item text-red-600 hover:text-red-900">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            `;
            
            itemsContainer.appendChild(newRow);
            itemIndex++;
            
            // Load items for new row
            if (warehouseSelect.value) {
                loadItemsForRow(newRow);
            }
        });
        
        // Remove item row
        itemsContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const row = e.target.closest('.item-row');
                if (document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                    calculateTotals();
                } else {
                    alert('At least one item is required');
                }
            }
        });
        
        // Calculate totals when inputs change
        itemsContainer.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity-input') || 
                e.target.classList.contains('price-input') ||
                e.target.classList.contains('item-select')) {
                
                const row = e.target.closest('.item-row');
                calculateItemTotal(row);
                calculateTotals();
                
                // Update stock info when item is selected
                if (e.target.classList.contains('item-select')) {
                    updateStockInfo(row);
                }
            }
        });
        
        // Calculate discount, tax, shipping
        ['discount', 'tax', 'shipping_cost'].forEach(id => {
            document.getElementById(id).addEventListener('input', calculateTotals);
        });
        
        // Initial calculations
        calculateTotals();
        
        // Load items for initial row
        if (warehouseSelect.value) {
            loadItems();
        }
        
        function loadItems() {
            const warehouseId = warehouseSelect.value;
            if (!warehouseId) return;
            
            fetch(`/company/<?php echo e($tenant); ?>/sales/items?warehouse_id=${warehouseId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update all item select dropdowns
                document.querySelectorAll('.item-select').forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = '<option value="">Select Item</option>';
                    
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = `${item.name} (${item.sku}) - Stock: ${item.current_stock}`;
                        option.dataset.price = item.unit_price;
                        option.dataset.stock = item.current_stock;
                        
                        if (item.id == currentValue) {
                            option.selected = true;
                        }
                        
                        select.appendChild(option);
                    });
                    
                    // Update price and stock info
                    if (currentValue) {
                        const selectedOption = select.options[select.selectedIndex];
                        if (selectedOption) {
                            const row = select.closest('.item-row');
                            const priceInput = row.querySelector('.price-input');
                            priceInput.value = selectedOption.dataset.price || 0;
                            calculateItemTotal(row);
                            updateStockInfo(row);
                        }
                    }
                });
                
                calculateTotals();
            })
            .catch(error => console.error('Error loading items:', error));
        }
        
        function loadItemsForRow(row) {
            const warehouseId = warehouseSelect.value;
            if (!warehouseId) return;
            
            const select = row.querySelector('.item-select');
            
            fetch(`/company/<?php echo e($tenant); ?>/sales/items?warehouse_id=${warehouseId}`)
            .then(response => response.json())
            .then(data => {
                select.innerHTML = '<option value="">Select Item</option>';
                
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.name} (${item.sku}) - Stock: ${item.current_stock}`;
                    option.dataset.price = item.unit_price;
                    option.dataset.stock = item.current_stock;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading items:', error));
        }
        
        function calculateItemTotal(row) {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = quantity * price;
            
            row.querySelector('.item-total').value = total.toFixed(2);
        }
        
        function updateStockInfo(row) {
            const select = row.querySelector('.item-select');
            const selectedOption = select.options[select.selectedIndex];
            const stockInfo = row.querySelector('.stock-info');
            const quantityInput = row.querySelector('.quantity-input');
            
            if (selectedOption && selectedOption.value) {
                const stock = parseInt(selectedOption.dataset.stock) || 0;
                stockInfo.textContent = `Stock: ${stock}`;
                
                // Validate quantity doesn't exceed stock
                const quantity = parseInt(quantityInput.value) || 0;
                if (quantity > stock) {
                    stockInfo.classList.remove('text-gray-500');
                    stockInfo.classList.add('text-red-500');
                    stockInfo.textContent += ` (Insufficient stock)`;
                } else {
                    stockInfo.classList.remove('text-red-500');
                    stockInfo.classList.add('text-gray-500');
                }
            } else {
                stockInfo.textContent = '';
            }
        }
        
        function calculateTotals() {
            let subtotal = 0;
            
            document.querySelectorAll('.item-row').forEach(row => {
                const total = parseFloat(row.querySelector('.item-total').value) || 0;
                subtotal += total;
            });
            
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const tax = parseFloat(document.getElementById('tax').value) || 0;
            const shipping = parseFloat(document.getElementById('shipping_cost').value) || 0;
            
            const total = subtotal - discount + tax + shipping;
            
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.company', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/sales/create.blade.php ENDPATH**/ ?>