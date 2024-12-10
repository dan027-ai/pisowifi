<?php
function renderDashboardScripts() {
    ?>
    <script>
        if (localStorage.getItem('darkMode') === 'dark') {
            document.documentElement.classList.add('dark');
        }

        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
        }

        function editVoucher(voucher) {
            document.querySelector('[name="voucher_id"]').value = voucher.id;
            document.querySelector('[name="price"]').value = voucher.price;
            document.querySelector('[name="duration"]').value = voucher.duration;
            document.querySelector('[name="description"]').value = voucher.description;
            document.querySelector('[name="is_promo"]').checked = voucher.is_promo == 1;
            if (voucher.promo_end_time) {
                document.querySelector('[name="promo_end_time"]').value = 
                    new Date(voucher.promo_end_time).toISOString().slice(0, 16);
            } else {
                document.querySelector('[name="promo_end_time"]').value = '';
            }
            document.querySelector('[name="quantity_limit"]').value = voucher.quantity_limit || '';
            document.querySelector('[name="save_voucher"]').textContent = 'Update Voucher';
        }

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            document.querySelectorAll('nav button').forEach(tab => {
                tab.classList.remove('border-indigo-500', 'text-indigo-600');
                tab.classList.add('border-transparent', 'text-gray-500');
            });

            document.getElementById(`${tabName}-content`).classList.remove('hidden');
            
            const activeTab = document.getElementById(`${tabName}-tab`);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-indigo-500', 'text-indigo-600');
        }

        document.addEventListener('DOMContentLoaded', () => {
            switchTab('overview');
        });
    </script>
    <?php
}
?>