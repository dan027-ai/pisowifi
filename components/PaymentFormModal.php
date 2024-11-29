<div id="paymentForm" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 max-w-md w-full bg-white p-6 rounded-lg shadow-xl hidden z-50">
    <button onclick="closePaymentForm()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <h2 class="text-2xl font-bold mb-6 text-center">Complete Your Purchase</h2>
    <form id="purchaseForm" class="space-y-6">
        <input type="hidden" id="selectedVoucherId" name="voucherId">
        <input type="hidden" id="selectedPrice" name="price">
        <input type="hidden" id="paymentMethod" name="paymentMethod" value="<?php echo $paymentMethod; ?>">
        
        <div class="space-y-2">
            <label class="block text-gray-700"><?php echo ucfirst($paymentMethod); ?> Phone Number</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="09XX XXX XXXX" 
                class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div class="space-y-2">
            <label class="block text-gray-700">Email Address</label>
            <input type="email" id="email" name="email" placeholder="your@email.com" 
                class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div id="otpSection" class="hidden space-y-2">
            <label class="block text-gray-700">Enter OTP</label>
            <div class="flex justify-center gap-2">
                <input type="text" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" data-otp-input>
                <input type="text" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" data-otp-input>
                <input type="text" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" data-otp-input>
                <input type="text" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" data-otp-input>
            </div>
            <p class="text-sm text-gray-500 text-center mt-2">
                Didn't receive the code? 
                <button type="button" id="resendOTP" class="text-blue-600 hover:text-blue-800">Resend</button>
            </p>
        </div>

        <button type="submit" id="submitButton"
            class="w-full <?php echo $paymentMethod === 'gcash' ? 'bg-gcash-blue hover:bg-gcash-secondary' : 'bg-paymaya-green hover:bg-paymaya-secondary'; ?> text-white py-2 px-4 rounded">
            Pay ₱<span id="paymentAmount">0</span>
        </button>
    </form>
</div>