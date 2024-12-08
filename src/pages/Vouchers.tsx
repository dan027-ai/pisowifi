import { useQuery } from "@tanstack/react-query";
import { Voucher, PaymentMethod } from "../types/voucher";
import VoucherCard from "../components/VoucherCard";
import PaymentMethodSelector from "../components/PaymentMethodSelector";
import PaymentHeader from "../components/PaymentHeader";
import { useToast } from "../hooks/use-toast";
import { useSearchParams } from "react-router-dom";

export default function Vouchers() {
  const { toast } = useToast();
  const [searchParams] = useSearchParams();
  const paymentMethod = (searchParams.get("method") || "gcash") as PaymentMethod;

  const { data: vouchers, isLoading } = useQuery({
    queryKey: ["vouchers"],
    queryFn: async () => {
      const response = await fetch("/pisowifi/vouchers.php", {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      });

      if (!response.ok) {
        console.error("API Error:", response.status, response.statusText);
        const errorText = await response.text();
        console.error("Error details:", errorText);
        throw new Error(`API error: ${response.status} ${response.statusText}`);
      }

      const data = await response.json();
      if (!data.success) {
        throw new Error(data.error || "Failed to fetch vouchers");
      }

      return data.data as Voucher[];
    },
    meta: {
      onError: (error: Error) => {
        console.error("Query error:", error);
        toast({
          variant: "destructive",
          title: "Error",
          description: "Failed to load vouchers. Please try again later.",
        });
      },
    },
  });

  if (isLoading) {
    return <div>Loading...</div>;
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <PaymentHeader
        title={`${paymentMethod.toUpperCase()} Vouchers`}
        description={`Purchase vouchers quickly and securely using ${paymentMethod.toUpperCase()}. Select your preferred duration below.`}
      />
      <PaymentMethodSelector currentMethod={paymentMethod} />
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        {vouchers?.map((voucher) => (
          <VoucherCard
            key={voucher.id}
            voucher={voucher}
            paymentMethod={paymentMethod}
          />
        ))}
      </div>
    </div>
  );
}