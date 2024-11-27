import { useState, useEffect } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Timer } from "lucide-react";

const VoucherTimer = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const [timeLeft, setTimeLeft] = useState<string>("");
  
  const voucherData = location.state?.voucherData;
  
  useEffect(() => {
    if (!voucherData) {
      navigate("/vouchers");
      return;
    }

    const endTime = new Date(voucherData.expiryTime).getTime();
    
    const timer = setInterval(() => {
      const now = new Date().getTime();
      const distance = endTime - now;
      
      if (distance < 0) {
        clearInterval(timer);
        setTimeLeft("EXPIRED");
        return;
      }
      
      const hours = Math.floor(distance / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);
      
      setTimeLeft(`${hours}h ${minutes}m ${seconds}s`);
    }, 1000);
    
    return () => clearInterval(timer);
  }, [voucherData, navigate]);

  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center p-4">
      <Card className="w-full max-w-md">
        <CardHeader>
          <CardTitle className="text-center flex items-center justify-center gap-2">
            <Timer className="w-6 h-6" />
            Voucher Active
          </CardTitle>
        </CardHeader>
        <CardContent className="space-y-6">
          <div className="text-center space-y-2">
            <p className="text-gray-600">Your voucher will expire in:</p>
            <p className="text-4xl font-bold text-primary">{timeLeft}</p>
          </div>
          <div className="space-y-2">
            <p className="text-sm text-gray-600">Voucher Details:</p>
            <div className="bg-gray-100 p-4 rounded-lg space-y-2">
              <p>Duration: {voucherData?.duration}</p>
              <p>Amount Paid: ₱{voucherData?.amount}</p>
            </div>
          </div>
          <Button 
            onClick={() => navigate("/vouchers")} 
            variant="outline" 
            className="w-full"
          >
            Buy Another Voucher
          </Button>
        </CardContent>
      </Card>
    </div>
  );
};

export default VoucherTimer;