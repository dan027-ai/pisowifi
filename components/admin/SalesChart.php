<?php
function renderSalesChart($conn) {
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
    
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 dark:text-white">Sales Overview</h2>
        <div class="relative h-[300px]">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const isDark = document.documentElement.classList.contains('dark');
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
                    backgroundColor: isDark ? 'rgba(79, 70, 229, 0.2)' : 'rgba(79, 70, 229, 0.1)'
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
                            },
                            color: isDark ? '#e2e8f0' : '#1f2937'
                        },
                        grid: {
                            color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: isDark ? '#e2e8f0' : '#1f2937'
                        },
                        grid: {
                            color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                }
            }
        });
    </script>
    <?php
}
?>