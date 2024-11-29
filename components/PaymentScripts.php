<script>
    function selectVoucher(id, price) {
        $('#selectedVoucherId').val(id);
        $('#selectedPrice').val(price);
        $('#paymentAmount').text(price);
        $('#paymentForm').removeClass('hidden');
        $('#modalOverlay').removeClass('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePaymentForm() {
        $('#paymentForm').addClass('hidden');
        $('#modalOverlay').addClass('hidden');
        document.body.style.overflow = '';
        Swal.close();
        resetForm();
    }

    function resetForm() {
        $('#purchaseForm')[0].reset();
        $('#otpSection').addClass('hidden');
        $('[data-otp-input]').val('');
        $('#submitButton').text('Pay ₱' + $('#selectedPrice').val());
    }

    function sendOTP() {
        // Simulate sending OTP
        const processingModal = Swal.fire({
            title: 'Sending OTP...',
            text: 'Please wait while we send you a verification code.',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            timer: 2000
        });

        setTimeout(() => {
            processingModal.close();
            $('#otpSection').removeClass('hidden');
            $('#submitButton').text('Verify OTP');
            Swal.fire({
                title: 'OTP Sent!',
                text: 'Please check your phone for the verification code.',
                icon: 'success'
            });
        }, 2000);
    }

    $('#purchaseForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            voucherId: $('#selectedVoucherId').val(),
            phoneNumber: $('#phoneNumber').val(),
            email: $('#email').val(),
            price: $('#selectedPrice').val(),
            paymentMethod: $('#paymentMethod').val()
        };
        
        if (!formData.phoneNumber || !formData.email) {
            Swal.fire({
                title: 'Error',
                text: 'Please fill in all fields',
                icon: 'error'
            });
            return;
        }

        // If OTP section is hidden, send OTP first
        if ($('#otpSection').hasClass('hidden')) {
            sendOTP();
            return;
        }

        // Verify OTP
        const otp = Array.from($('[data-otp-input]')).map(input => $(input).val()).join('');
        if (otp.length !== 4) {
            Swal.fire({
                title: 'Error',
                text: 'Please enter the complete OTP',
                icon: 'error'
            });
            return;
        }

        // Show processing payment modal
        const processingModal = Swal.fire({
            title: 'Processing payment...',
            text: 'Please wait while we process your payment.',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            type: 'POST',
            url: 'vouchers.php',
            data: { ...formData, otp },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    processingModal.close();
                    closePaymentForm();
                    Swal.fire({
                        title: 'Payment successful!',
                        text: 'Your voucher has been activated.',
                        icon: 'success',
                        allowOutsideClick: false
                    }).then(() => {
                        Swal.fire({
                            title: 'WiFi Connected!',
                            text: 'Your device is now connected to the network.',
                            icon: 'success'
                        });
                    });
                } else {
                    processingModal.close();
                    Swal.fire({
                        title: 'Error',
                        text: result.error || 'Payment failed. Please try again.',
                        icon: 'error'
                    });
                }
            },
            error: function() {
                processingModal.close();
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    icon: 'error'
                });
            }
        });
    });

    // Handle OTP input behavior
    $('[data-otp-input]').on('input', function() {
        const maxLength = 1;
        if (this.value.length >= maxLength) {
            const nextInput = $(this).next('[data-otp-input]');
            if (nextInput.length) {
                nextInput.focus();
            }
        }
    });

    $('[data-otp-input]').on('keydown', function(e) {
        if (e.key === 'Backspace' && !this.value) {
            const prevInput = $(this).prev('[data-otp-input]');
            if (prevInput.length) {
                prevInput.focus();
            }
        }
    });

    // Handle resend OTP
    $('#resendOTP').click(function() {
        sendOTP();
    });

    // Close modal when clicking outside
    $('#modalOverlay').click(function() {
        closePaymentForm();
    });

    // Prevent modal from closing when clicking inside the form
    $('#paymentForm').click(function(e) {
        e.stopPropagation();
    });

    // Close modal when pressing ESC key
    $(document).keydown(function(e) {
        if (e.key === "Escape") {
            closePaymentForm();
        }
    });
</script>