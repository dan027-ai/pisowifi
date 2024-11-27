import { useState } from "react";
import { useSearchParams } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { useToast } from "@/components/ui/use-toast";
import { supabase } from "@/integrations/supabase/client";
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
      const { data, error } = await supabase
        .from("vouchers")
        .select("*")
        .order("price");
      
      if (error) throw error;
      return data;
    },
  });

  const handlePaymentSubmit = async (formData: {
    phoneNumber: string;
    email: string;
  }) => {
    if (!selectedVoucher) return;

    try {
      const { error } = await supabase.from("transactions").insert({
        voucher_id: selectedVoucher.id,
        phone_number: formData.phoneNumber,
        email: formData.email,
        amount: selectedVoucher.price,
        payment_method: paymentMethod,
      });

      if (error) throw error;

      toast({
        title: "Payment successful!",
        description: "Your voucher has been activated.",
      });

      setSelectedVoucher(null);
    } catch (error) {
      toast({
        title: "Error",
        description: "Payment failed. Please try again.",
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