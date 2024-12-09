<?php
function renderSalesChart($conn) {
    // Fetch monthly sales data for the last 12 months
    $result = $conn->query("
        SELECT 
            DATE_FORMAT(date, '%Y-%m') as month,
            SUM(total_amount) as monthly_total
        FROM total_sales
        GROUP BY DATE_FORMAT(date, '%Y-%m')
        ORDER BY month DESC
        LIMIT 12
    ");
    
    $sales = array_reverse($result->fetch_all(MYSQLI_ASSOC));
    $labels = array_map(function($sale) {
        return date('M Y', strtotime($sale['month'] . '-01'));
    }, $sales);
    $data = array_map(function($sale) {
        return $sale['monthly_total'];
    }, $sales);
    ?>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Sales Overview</h2>
        <div class="relative h-[300px]">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Monthly Sales',
                    data: <?php echo json_encode($data); ?>,
                    borderColor: 'rgb(79, 70, 229)',
                    tension: 0.1,
                    fill: true,
                    backgroundColor: 'rgba(79, 70, 229, 0.1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
    <?php
}
?>