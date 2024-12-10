<?php
function renderDashboardStyles() {
    ?>
    <style>
        .dark {
            color-scheme: dark;
        }
        .dark body {
            background: linear-gradient(to bottom, #1a1b26, #24283b);
            color: #ffffff;
        }
        .dark .bg-white {
            background-color: rgba(30, 41, 59, 0.8);
        }
        .dark .text-gray-700 {
            color: #e2e8f0;
        }
        .dark input, .dark select {
            background-color: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border-color: #4b5563;
        }
        .dark .border-gray-200 {
            border-color: rgba(75, 85, 99, 0.3);
        }
    </style>
    <?php
}
?>