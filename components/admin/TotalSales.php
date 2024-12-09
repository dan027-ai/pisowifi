<?php
function renderTotalSales($conn) {
    $result = $conn->query("
        SELECT 
            DATE_FORMAT(date, '%Y-%m') as month,
            SUM(total_amount) as monthly_total,
            SUM(transactions_count) as monthly_transactions
        FROM total_sales
        GROUP BY DATE_FORMAT(date, '%Y-%m')
        ORDER BY month DESC
        LIMIT 12
    ");
    
    $sales = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Monthly Performance</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left">Month</th>
                        <th class="px-4 py-2 text-left">Total Sales</th>
                        <th class="px-4 py-2 text-left">Transactions</th>
                        <th class="px-4 py-2 text-left">Avg. per Transaction</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sales as $sale): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2"><?php echo date('F Y', strtotime($sale['month'] . '-01')); ?></td>
                        <td class="px-4 py-2 font-medium">₱<?php echo number_format($sale['monthly_total'], 2); ?></td>
                        <td class="px-4 py-2"><?php echo number_format($sale['monthly_transactions']); ?></td>
                        <td class="px-4 py-2">
                            ₱<?php 
                            $avg = $sale['monthly_transactions'] > 0 
                                ? $sale['monthly_total'] / $sale['monthly_transactions'] 
                                : 0;
                            echo number_format($avg, 2); 
                            ?>
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