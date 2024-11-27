import { useState } from "react";
import { useNavigate } from "react-router-dom";
import VoucherCard from "@/components/VoucherCard";
import PaymentForm from "@/components/PaymentForm";
import ReceiptModal from "@/components/ReceiptModal";
import { useToast } from "@/components/ui/use-toast";
import type { Voucher, PaymentFormData } from "@/types/voucher";

const VOUCHERS: Voucher[] = [
  { id: 1, price: 5, duration: "3 hours", description: "Perfect for quick sessions" },
  { id: 2, price: 10, duration: "8 hours", description: "Great for all-day use" },
  { id: 3, price: 15, duration: "1 day", description: "24 hours of unlimited access" },
  { id: 4, price: 25, duration: "2 days", description: "Weekend package" },
  { id: 5, price: 50, duration: "5 days", description: "Best value for longer periods" },
];

export default function Index() {
  const navigate = useNavigate();
  const [selectedVoucher, setSelectedVoucher] = useState<Voucher | null>(null);
  const [showReceipt, setShowReceipt] = useState(false);
  const [paymentData, setPaymentData] = useState<PaymentFormData>();
  const { toast } = useToast();

  const handlePaymentSubmit = async (data: PaymentFormData) => {
    if (!selectedVoucher) return;

    toast({
      title: "Processing payment...",
      description: "Please wait while we process your payment.",
    });

    try {
      const response = await fetch('vouchers.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          voucherId: selectedVoucher.id.toString(),
          phoneNumber: data.phoneNumber,
          email: data.email,
          price: selectedVoucher.price.toString(),
          paymentMethod: 'gcash'
        })
      });

      const result = await response.json();

      if (result.success) {
        toast({
          title: "Payment successful!",
          description: "Your voucher has been activated.",
        });

        const voucherData = {
          duration: selectedVoucher.duration,
          amount: result.amount,
          expiryTime: result.expiryTime,
        };

        navigate("/voucher-timer", { state: { voucherData } });
      } else {
        throw new Error(result.error || 'Payment failed');
      }
    } catch (error) {
      toast({
        title: "Error",
        description: error instanceof Error ? error.message : 'An error occurred',
        variant: "destructive",
      });
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="container py-8">
        <div className="text-center mb-12">
          <h1 className="text-4xl font-bold text-gcash-blue mb-4">
            GCash Vouchers
          </h1>
          <p className="text-gray-600 max-w-2xl mx-auto">
            Purchase vouchers quickly and securely using GCash. Select your preferred duration below.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
          {VOUCHERS.map((voucher) => (
            <VoucherCard
              key={voucher.id}
              voucher={voucher}
              onSelect={setSelectedVoucher}
              isSelected={selectedVoucher?.id === voucher.id}
            />
          ))}
        </div>

        {selectedVoucher && (
          <div className="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h2 className="text-2xl font-bold mb-6 text-center">
              Complete Your Purchase
            </h2>
            <PaymentForm
              selectedPrice={selectedVoucher.price}
              onSubmit={handlePaymentSubmit}
            />
          </div>
        )}

        <ReceiptModal
          isOpen={showReceipt}
          onClose={() => setShowReceipt(false)}
          paymentData={paymentData}
        />
      </div>
    </div>
  );
}