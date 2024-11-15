import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import type { Voucher } from "@/types/voucher";

interface VoucherCardProps {
  voucher: Voucher;
  onSelect: (voucher: Voucher) => void;
  isSelected: boolean;
}

const VoucherCard = ({ voucher, onSelect, isSelected }: VoucherCardProps) => {
  return (
    <Card className={`p-6 transition-all duration-300 hover:shadow-lg ${
      isSelected ? 'ring-2 ring-gcash-blue' : ''
    }`}>
      <div className="flex flex-col space-y-4">
        <div className="text-2xl font-bold text-gcash-blue">
          ₱{voucher.price}
        </div>
        <div className="text-lg font-medium">{voucher.duration}</div>
        <div className="text-sm text-gray-600">{voucher.description}</div>
        <Button
          onClick={() => onSelect(voucher)}
          variant={isSelected ? "default" : "outline"}
          className={`w-full ${
            isSelected ? 'bg-gcash-blue hover:bg-gcash-secondary' : ''
          }`}
        >
          {isSelected ? 'Selected' : 'Select'}
        </Button>
      </div>
    </Card>
  );
};

export default VoucherCard;