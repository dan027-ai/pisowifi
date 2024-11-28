<?php
function renderAdminHeader() {
    ?>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-blue-600">Admin Dashboard</h1>
        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </div>
    <?php
}
?>