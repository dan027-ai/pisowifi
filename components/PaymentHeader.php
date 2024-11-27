<?php
function renderHeader($title, $description) {
    $paymentMethod = isset($_GET['method']) ? $_GET['method'] : 'gcash';
    $titleColorClass = $paymentMethod === 'gcash' ? 'text-gcash-blue' : 'text-paymaya-green';
    ?>
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold <?php echo $titleColorClass; ?> mb-4">
            <?php echo $title; ?>
        </h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            <?php echo $description; ?>
        </p>
    </div>
    <?php
}
?>