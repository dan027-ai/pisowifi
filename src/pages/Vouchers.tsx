import { useState } from "react";
import { useSearchParams } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { useToast } from "@/components/ui/use-toast";
import PaymentHeader from "@/components/PaymentHeader";
import PaymentMethodSelector from "@/components/PaymentMethodSelector";
import VoucherCard from "@/components/VoucherCard";
import PaymentForm from "@/components/PaymentForm";
import type { Voucher, PaymentMethod } from "@/types/voucher";

// Define the API base URL to always point to XAMPP htdocs location
const API_BASE_URL = 'http://localhost/pisowifi';

// Add console logs to help debug API calls
const fetchVouchers = async () => {
  console.log('Fetching vouchers from:', `${API_BASE_URL}/vouchers.php`);
  try {
    const response = await fetch(`${API_BASE_URL}/vouchers.php`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const text = await response.text();
    console.log('Raw response:', text);
    
    try {
      const data = JSON.parse(text);
      console.log('Parsed vouchers data:', data);
      if (data.success && Array.isArray(data.data)) {
        return data.data;
      } else {
        throw new Error('Invalid data format received from server');
      }
    } catch (parseError) {
      console.error('Error parsing JSON:', parseError);
      throw new Error('Invalid JSON response from server');
    }
  } catch (error) {
    console.error('Fetch error:', error);
    throw error;
  }
};

const Vouchers = () => {
  const [searchParams] = useSearchParams();
  const paymentMethod = (searchParams.get("method") || "gcash") as PaymentMethod;
  const [selectedVoucher, setSelectedVoucher] = useState<Voucher | null>(null);
  const { toast } = useToast();

  const { data: vouchers = [], isLoading, error } = useQuery({
    queryKey: ["vouchers"],
    queryFn: fetchVouchers,
    retry: 1,
    meta: {
      onError: (error: Error) => {
        console.error('Query error details:', error);
        toast({
          title: "Error loading vouchers",
          description: "Failed to load vouchers. Please try again later.",
          variant: "destructive",
        });
      }
    }
  });

  const handlePaymentSubmit = async (formData: {
    phoneNumber: string;
    email: string;
  }) => {
    if (!selectedVoucher) return;

    try {
      const response = await fetch(`${API_BASE_URL}/vouchers.php`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify({
          voucherId: selectedVoucher.id,
          phoneNumber: formData.phoneNumber,
          email: formData.email,
          price: selectedVoucher.price,
          paymentMethod: paymentMethod,
        }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

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
      console.error('Payment submission error:', error);
      toast({
        title: "Error",
        description: "An error occurred. Please try again.",
        variant: "destructive",
      });
    }
  };

  if (error) {
    console.error('Query error:', error);
  }

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