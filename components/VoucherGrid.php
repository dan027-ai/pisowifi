<?php
function renderVoucherGrid($vouchers, $paymentMethod) {
    ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <?php foreach ($vouchers as $voucher): ?>
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="flex flex-col space-y-4">
                <div class="text-2xl font-bold <?php echo $paymentMethod === 'gcash' ? 'text-gcash-blue' : 'text-paymaya-green'; ?>">
                    ₱<?php echo $voucher['price']; ?>
                </div>
                <div class="text-lg font-medium"><?php echo $voucher['duration']; ?></div>
                <div class="text-sm text-gray-600"><?php echo $voucher['description']; ?></div>
                <button 
                    onclick="selectVoucher(<?php echo $voucher['id']; ?>, <?php echo $voucher['price']; ?>)"
                    class="w-full <?php echo $paymentMethod === 'gcash' ? 'bg-gcash-blue hover:bg-gcash-secondary' : 'bg-paymaya-green hover:bg-paymaya-secondary'; ?> text-white py-2 px-4 rounded transition-colors">
                    Select
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}
?>