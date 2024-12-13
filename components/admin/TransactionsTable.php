<?php
function renderTransactionsTable($transactions) {
    ?>
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur-sm p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4 dark:text-white">Recent Transactions</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-4 py-2 text-left dark:text-gray-200">Date</th>
                        <th class="px-4 py-2 text-left dark:text-gray-200">Phone Number</th>
                        <th class="px-4 py-2 text-left dark:text-gray-200">Email</th>
                        <th class="px-4 py-2 text-left dark:text-gray-200">Amount</th>
                        <th class="px-4 py-2 text-left dark:text-gray-200">Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($transaction = $transactions->fetch_assoc()): ?>
                    <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-2 dark:text-gray-200"><?php echo date('Y-m-d H:i', strtotime($transaction['created_at'])); ?></td>
                        <td class="px-4 py-2 dark:text-gray-200"><?php echo $transaction['phone_number']; ?></td>
                        <td class="px-4 py-2 dark:text-gray-200"><?php echo $transaction['email']; ?></td>
                        <td class="px-4 py-2 dark:text-gray-200">₱<?php echo $transaction['amount']; ?></td>
                        <td class="px-4 py-2 dark:text-gray-200"><?php echo $transaction['duration']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>