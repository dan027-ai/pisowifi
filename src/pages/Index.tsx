import { useState } from "react";
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

const Index = () => {
  const [selectedVoucher, setSelectedVoucher] = useState<Voucher | null>(null);
  const [showReceipt, setShowReceipt] = useState(false);
  const [paymentData, setPaymentData] = useState<PaymentFormData | undefined>();
  const { toast } = useToast();

  const activateVoucher = (voucherId: number) => {
    // Here you would typically make an API call to your Piso WiFi system
    // to activate the voucher and start the timer
    toast({
      title: "WiFi Connected!",
      description: "Your device is now connected to the network.",
    });
  };

  const handlePaymentSubmit = (data: PaymentFormData) => {
    toast({
      title: "Processing payment...",
      description: "Please wait while we process your payment.",
    });

    // Simulate payment processing
    setTimeout(() => {
      setPaymentData(data);
      setShowReceipt(true);
      
      // After successful payment, automatically activate the voucher
      if (selectedVoucher) {
        activateVoucher(selectedVoucher.id);
      }

      toast({
        title: "Payment successful!",
        description: "Your voucher has been activated and your device is now connected.",
      });
    }, 2000);
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
};

export default Index;