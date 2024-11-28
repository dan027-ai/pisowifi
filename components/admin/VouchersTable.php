<?php
function renderVouchersTable($vouchers) {
    ?>
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4">Current Vouchers</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left">Price</th>
                        <th class="px-4 py-2 text-left">Duration</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-left">Promo Status</th>
                        <th class="px-4 py-2 text-left">Remaining/Limit</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vouchers as $voucher): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2">₱<?php echo $voucher['price']; ?></td>
                        <td class="px-4 py-2"><?php echo $voucher['duration']; ?></td>
                        <td class="px-4 py-2"><?php echo $voucher['description']; ?></td>
                        <td class="px-4 py-2">
                            <?php if ($voucher['is_promo']): ?>
                                <span class="text-green-600">Active Promo</span>
                                <?php if ($voucher['promo_end_time']): ?>
                                    <br>
                                    <span class="text-sm text-gray-500">
                                        Until: <?php echo date('Y-m-d H:i', strtotime($voucher['promo_end_time'])); ?>
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-gray-500">Regular</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2">
                            <?php if ($voucher['quantity_limit']): ?>
                                <?php echo $voucher['remaining_quantity'] ?? 0; ?>/<?php echo $voucher['quantity_limit']; ?>
                            <?php else: ?>
                                Unlimited
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2">
                            <button onclick='editVoucher(<?php echo json_encode($voucher); ?>)'
                                    class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                            <form method="POST" class="inline">
                                <input type="hidden" name="voucher_id" value="<?php echo $voucher['id']; ?>">
                                <button type="submit" name="delete_voucher" 
                                        class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>
