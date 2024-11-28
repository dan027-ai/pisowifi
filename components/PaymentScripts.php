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
            data: formData,
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