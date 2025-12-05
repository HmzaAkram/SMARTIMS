

<?php $__env->startSection('title', isset($item) ? 'Edit Item - SmartIMS' : 'Add Item - SmartIMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <a href="<?php echo e(route('company.dashboard', $tenant)); ?>" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    <a href="<?php echo e(route('company.items.index', $tenant)); ?>" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Inventory</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-900"><?php echo e(isset($item) ? 'Edit Item' : 'Add Item'); ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div>
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            <?php echo e(isset($item) ? 'Edit Item' : 'Add New Item'); ?>

        </h2>
        <p class="mt-1 text-sm text-gray-500"><?php echo e(isset($item) ? 'Update item information and stock details' : 'Fill in the information below to add a new inventory item'); ?></p>
    </div>

    <!-- Form -->
    <form method="POST" action="<?php echo e(isset($item) ? route('company.items.update', [$tenant, $item]) : route('company.items.store', $tenant)); ?>" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php if(isset($item)): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <!-- Basic Information -->
        <div class="rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Item Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-900">Item Name *</label>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name', $item->name ?? '')); ?>" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-900">SKU *</label>
                        <input type="text" name="sku" id="sku" value="<?php echo e(old('sku', $item->sku ?? '')); ?>" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-mono">
                        <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Barcode -->
                    <div>
                        <label for="barcode" class="block text-sm font-medium text-gray-900">Barcode</label>
                        <div class="mt-2 flex rounded-md shadow-sm">
                            <input type="text" name="barcode" id="barcode" value="<?php echo e(old('barcode', $item->barcode ?? '')); ?>" class="block w-full rounded-l-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-mono">
                            <button type="button" onclick="generateBarcode()" class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500 hover:bg-gray-100">
                                Generate
                            </button>
                        </div>
                        <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-900">Category *</label>
                        <select name="category_id" id="category_id" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option value="">Select Category</option>
                            <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $item->category_id ?? '') == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Unit of Measure -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-900">Unit of Measure *</label>
                        <select name="unit" id="unit" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option value="">Select Unit</option>
                            <option value="pcs" <?php echo e(old('unit', $item->unit ?? '') == 'pcs' ? 'selected' : ''); ?>>Pieces (pcs)</option>
                            <option value="box" <?php echo e(old('unit', $item->unit ?? '') == 'box' ? 'selected' : ''); ?>>Box</option>
                            <option value="kg" <?php echo e(old('unit', $item->unit ?? '') == 'kg' ? 'selected' : ''); ?>>Kilogram (kg)</option>
                            <option value="ltr" <?php echo e(old('unit', $item->unit ?? '') == 'ltr' ? 'selected' : ''); ?>>Liter (ltr)</option>
                            <option value="m" <?php echo e(old('unit', $item->unit ?? '') == 'm' ? 'selected' : ''); ?>>Meter (m)</option>
                        </select>
                        <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"><?php echo e(old('description', $item->description ?? '')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Item Image -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-900">Item Image</label>
                        <div class="mt-2 flex items-center gap-x-3">
                            <?php if(isset($item) && $item->image): ?>
                                <img src="<?php echo e($item->image); ?>" alt="<?php echo e($item->name); ?>" class="h-20 w-20 rounded-lg object-cover">
                            <?php endif; ?>
                            <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Stock -->
        <div class="rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">Pricing & Stock</h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Unit Price -->
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-900">Unit Price *</label>
                        <div class="relative mt-2 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="unit_price" id="unit_price" value="<?php echo e(old('unit_price', $item->unit_price ?? '')); ?>" step="0.01" required class="block w-full rounded-md border-0 py-2 pl-7 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>
                        <?php $__errorArgs = ['unit_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Current Stock -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-900">Current Stock *</label>
                        <input type="number" name="quantity" id="quantity" value="<?php echo e(old('quantity', $item->quantity ?? 0)); ?>" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Reorder Level -->
                    <div>
                        <label for="reorder_level" class="block text-sm font-medium text-gray-900">Reorder Level *</label>
                        <input type="number" name="reorder_level" id="reorder_level" value="<?php echo e(old('reorder_level', $item->reorder_level ?? 10)); ?>" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Alert when stock falls below this level</p>
                        <?php $__errorArgs = ['reorder_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                        <!-- Warehouse -->
                    <div>
                        <label for="warehouse_id" class="block text-sm font-medium text-gray-900">Warehouse *</label>
                        <select name="warehouse_id" id="warehouse_id" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            <option value="">Select Warehouse</option>
                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($warehouse->id); ?>" <?php echo e(old('warehouse_id') == $warehouse->id ? 'selected' : ''); ?>><?php echo e($warehouse->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Details -->
        <div class="rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">Additional Details</h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Expiry Date -->
                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-900">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" value="<?php echo e(old('expiry_date', isset($item) && $item->expiry_date ? $item->expiry_date->format('Y-m-d') : '')); ?>" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        <?php $__errorArgs = ['expiry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Batch Number -->
                    <div>
                        <label for="batch_number" class="block text-sm font-medium text-gray-900">Batch Number</label>
                        <input type="text" name="batch_number" id="batch_number" value="<?php echo e(old('batch_number', $item->batch_number ?? '')); ?>" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        <?php $__errorArgs = ['batch_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active', $item->is_active ?? true) ? 'checked' : ''); ?> class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            <label for="is_active" class="ml-3 block text-sm font-medium text-gray-900">Item is active</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-x-3">
            <a href="<?php echo e(route('company.items.index', $tenant)); ?>" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <?php echo e(isset($item) ? 'Update Item' : 'Add Item'); ?>

            </button>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function generateBarcode() {
    const timestamp = Date.now();
    const random = Math.floor(Math.random() * 10000);
    const barcode = `${timestamp}${random}`.substring(0, 13);
    document.getElementById('barcode').value = barcode;
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.company', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/inventory/items/create.blade.php ENDPATH**/ ?>