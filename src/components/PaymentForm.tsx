import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useToast } from "@/components/ui/use-toast";
import type { PaymentMethod } from "@/types/voucher";

interface PaymentFormProps {
  selectedPrice: number;
  onSubmit: (data: { phoneNumber: string; email: string }) => void;
  paymentMethod: PaymentMethod;
}

const PaymentForm = ({ selectedPrice, onSubmit, paymentMethod }: PaymentFormProps) => {
  const [phoneNumber, setPhoneNumber] = useState("");
  const [email, setEmail] = useState("");
  const { toast } = useToast();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (!phoneNumber || !email) {
      toast({
        title: "Error",
        description: "Please fill in all fields",
        variant: "destructive",
      });
      return;
    }

    onSubmit({
      phoneNumber,
      email,
    });
  };

  const bgColorClass = paymentMethod === 'gcash' ? 'bg-gcash-blue' : 'bg-paymaya-green';
  const hoverColorClass = paymentMethod === 'gcash' ? 'hover:bg-gcash-secondary' : 'hover:bg-paymaya-secondary';

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="space-y-2">
        <Label htmlFor="phone">{paymentMethod === 'gcash' ? 'GCash' : 'PayMaya'} Phone Number</Label>
        <Input
          id="phone"
          type="tel"
          placeholder="09XX XXX XXXX"
          value={phoneNumber}
          onChange={(e) => setPhoneNumber(e.target.value)}
          className="w-full"
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="email">Email Address</Label>
        <Input
          id="email"
          type="email"
          placeholder="your@email.com"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          className="w-full"
        />
      </div>

      <Button
        type="submit"
        className={`w-full ${bgColorClass} ${hoverColorClass}`}
      >
        Pay ₱{selectedPrice}
      </Button>
    </form>
  );
};

export default PaymentForm;