import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import type { Voucher, PaymentMethod } from "@/types/voucher";

interface VoucherCardProps {
  voucher: Voucher;
  onSelect: (voucher: Voucher) => void;
  isSelected: boolean;
  paymentMethod: PaymentMethod;
}

const VoucherCard = ({ voucher, onSelect, isSelected, paymentMethod }: VoucherCardProps) => {
  const bgColorClass = paymentMethod === 'gcash' ? 'bg-gcash-blue' : 'bg-paymaya-green';
  const hoverColorClass = paymentMethod === 'gcash' ? 'hover:bg-gcash-secondary' : 'hover:bg-paymaya-secondary';
  const ringColorClass = paymentMethod === 'gcash' ? 'ring-gcash-blue' : 'ring-paymaya-green';

  return (
    <Card className={`p-6 transition-all duration-300 hover:shadow-lg ${
      isSelected ? `ring-2 ${ringColorClass}` : ''
    }`}>
      <div className="flex flex-col space-y-4">
        <div className="text-2xl font-bold text-gray-900">
          ₱{voucher.price}
        </div>
        <div className="text-lg font-medium">{voucher.duration}</div>
        <div className="text-sm text-gray-600">{voucher.description}</div>
        <Button
          onClick={() => onSelect(voucher)}
          variant={isSelected ? "default" : "outline"}
          className={`w-full ${
            isSelected ? `${bgColorClass} ${hoverColorClass}` : ''
          }`}
        >
          {isSelected ? 'Selected' : 'Select'}
        </Button>
      </div>
    </Card>
  );
};

export default VoucherCard;