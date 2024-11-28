<?php
function renderTransactionsTable($transactions) {
    ?>
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4">Recent Transactions</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Phone Number</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Amount</th>
                        <th class="px-4 py-2 text-left">Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($transaction = $transactions->fetch_assoc()): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo date('Y-m-d H:i', strtotime($transaction['created_at'])); ?></td>
                        <td class="px-4 py-2"><?php echo $transaction['phone_number']; ?></td>
                        <td class="px-4 py-2"><?php echo $transaction['email']; ?></td>
                        <td class="px-4 py-2">₱<?php echo $transaction['amount']; ?></td>
                        <td class="px-4 py-2"><?php echo $transaction['duration']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>
