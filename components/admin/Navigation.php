<?php
function renderNavigation() {
    ?>
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="switchTab('overview')" id="overview-tab" 
                        class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Overview
                </button>
                <button onclick="switchTab('vouchers')" id="vouchers-tab"
                        class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Vouchers Management
                </button>
                <button onclick="switchTab('transactions')" id="transactions-tab"
                        class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Transactions
                </button>
            </nav>
        </div>
    </div>
    <?php
}
?>