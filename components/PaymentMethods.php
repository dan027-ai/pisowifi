<?php
function renderPaymentMethods($selectedMethod) {
    ?>
    <div class="flex justify-center gap-4 mb-8">
        <a href="?method=gcash" 
           class="flex items-center gap-2 px-6 py-3 rounded-lg <?php echo $selectedMethod === 'gcash' ? 'bg-gcash-blue text-white' : 'bg-white border border-gray-200'; ?>">
            <img src="assets/gcash-logo.png" alt="GCash" class="h-8">
            <span class="font-medium">GCash</span>
        </a>
        <a href="?method=paymaya" 
           class="flex items-center gap-2 px-6 py-3 rounded-lg <?php echo $selectedMethod === 'paymaya' ? 'bg-paymaya-green text-white' : 'bg-white border border-gray-200'; ?>">
            <img src="assets/paymaya-logo.png" alt="PayMaya" class="h-8">
            <span class="font-medium">PayMaya</span>
        </a>
    </div>
    <?php
}
?>