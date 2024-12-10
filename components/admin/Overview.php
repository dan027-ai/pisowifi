<?php
function renderOverview($conn) {
    ?>
    <div id="overview-content" class="tab-content">
        <div class="grid md:grid-cols-2 gap-8">
            <?php 
            renderSalesChart($conn);
            renderTotalSales($conn);
            ?>
        </div>
    </div>
    <?php
}
?>