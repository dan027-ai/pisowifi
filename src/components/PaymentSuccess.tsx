import { useEffect } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { useToast } from "@/components/ui/use-toast";
import { Button } from "@/components/ui/button";

const PaymentSuccess = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const { toast } = useToast();

  useEffect(() => {
    const queryParams = new URLSearchParams(window.location.search);
    const data = queryParams.get("data");
    if (data) {
      const voucherData = JSON.parse(decodeURIComponent(data));
      
      toast({
        title: "Payment successful!",
        description: "Your voucher has been activated.",
      });

      // Redirect to VoucherTimer page with state
      navigate("/voucher-timer", { state: { voucherData } });
    }
  }, [navigate, toast]);

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-50">
      <h1 className="text-4xl font-bold text-gcash-blue">Payment Successful</h1>
      <p className="mt-4 text-lg">Your voucher is now active!</p>
      <Button onClick={() => navigate("/vouchers")} className="mt-6">
        Buy Another Voucher
      </Button>
    </div>
  );
};

export default PaymentSuccess;
