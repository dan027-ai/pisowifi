<?php
function renderVoucherForm() {
    ?>
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4">Add/Edit Voucher</h2>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="voucher_id" id="edit_voucher_id">
            <div>
                <label class="block text-gray-700 mb-2">Price (₱)</label>
                <input type="number" name="price" step="0.01" required 
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Duration</label>
                <input type="text" name="duration" required 
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Description</label>
                <input type="text" name="description" required 
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Promo Settings</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_promo" class="mr-2">
                        Is Promotional Voucher
                    </label>
                    <input type="datetime-local" name="promo_end_time" 
                           class="w-full border border-gray-300 rounded px-3 py-2"
                           placeholder="Promo End Time">
                    <input type="number" name="quantity_limit" 
                           class="w-full border border-gray-300 rounded px-3 py-2"
                           placeholder="Quantity Limit (optional)">
                </div>
            </div>
            <div class="md:col-span-2">
                <button type="submit" name="save_voucher" 
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Save Voucher
                </button>
            </div>
        </form>
    </div>
    <?php
}
?>
