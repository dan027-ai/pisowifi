import { useState } from "react";
import { useSearchParams } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { useToast } from "@/components/ui/use-toast";
import PaymentHeader from "@/components/PaymentHeader";
import PaymentMethodSelector from "@/components/PaymentMethodSelector";
import VoucherCard from "@/components/VoucherCard";
import PaymentForm from "@/components/PaymentForm";
import type { Voucher, PaymentMethod } from "@/types/voucher";

const Vouchers = () => {
  const [searchParams] = useSearchParams();
  const paymentMethod = (searchParams.get("method") || "gcash") as PaymentMethod;
  const [selectedVoucher, setSelectedVoucher] = useState<Voucher | null>(null);
  const { toast } = useToast();

  const { data: vouchers = [], isLoading } = useQuery({
    queryKey: ["vouchers"],
    queryFn: async () => {
      const response = await fetch("/vouchers.php");
      const result = await response.json();
      if (!result.success) {
        throw new Error(result.error || "Failed to fetch vouchers");
      }
      return result.data;
    },
  });

  const handlePaymentSubmit = async (formData: {
    phoneNumber: string;
    email: string;
  }) => {
    if (!selectedVoucher) return;

    try {
      const response = await fetch("/vouchers.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          voucherId: selectedVoucher.id,
          phoneNumber: formData.phoneNumber,
          email: formData.email,
          price: selectedVoucher.price,
          paymentMethod: paymentMethod,
        }),
      });

      const result = await response.json();
      if (result.success) {
        toast({
          title: "Payment successful!",
          description: "Your voucher has been activated.",
        });
        setSelectedVoucher(null);
      } else {
        toast({
          title: "Error",
          description: result.error || "Payment failed. Please try again.",
          variant: "destructive",
        });
      }
    } catch (error) {
      toast({
        title: "Error",
        description: "An error occurred. Please try again.",
        variant: "destructive",
      });
    }
  };

  if (isLoading) {
    return <div className="text-center py-12">Loading...</div>;
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="container mx-auto py-8">
        <PaymentHeader
          title={`${paymentMethod === 'gcash' ? 'GCash' : 'PayMaya'} Vouchers`}
          description={`Purchase vouchers quickly and securely using ${
            paymentMethod === 'gcash' ? 'GCash' : 'PayMaya'
          }. Select your preferred duration below.`}
        />

        <PaymentMethodSelector currentMethod={paymentMethod} />

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
          {vouchers.map((voucher) => (
            <VoucherCard
              key={voucher.id}
              voucher={voucher}
              onSelect={() => setSelectedVoucher(voucher)}
              isSelected={selectedVoucher?.id === voucher.id}
              paymentMethod={paymentMethod}
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
              paymentMethod={paymentMethod}
            />
          </div>
        )}
      </div>
    </div>
  );
};

export default Vouchers;