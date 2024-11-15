import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import type { PaymentFormData } from "@/types/voucher";

interface ReceiptModalProps {
  isOpen: boolean;
  onClose: () => void;
  paymentData?: PaymentFormData;
}

const ReceiptModal = ({ isOpen, onClose, paymentData }: ReceiptModalProps) => {
  if (!paymentData) return null;

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent className="sm:max-w-md">
        <DialogHeader>
          <DialogTitle>Payment Receipt</DialogTitle>
        </DialogHeader>
        <div className="space-y-4">
          <div className="p-4 bg-gray-50 rounded-lg">
            <div className="grid grid-cols-2 gap-4">
              <div className="text-sm text-gray-600">Amount Paid:</div>
              <div className="text-sm font-medium">₱{paymentData.selectedVoucher}</div>
              <div className="text-sm text-gray-600">Phone Number:</div>
              <div className="text-sm font-medium">{paymentData.phoneNumber}</div>
              <div className="text-sm text-gray-600">Email:</div>
              <div className="text-sm font-medium">{paymentData.email}</div>
            </div>
          </div>
          <Button
            onClick={onClose}
            className="w-full bg-gcash-blue hover:bg-gcash-secondary"
          >
            Close
          </Button>
        </div>
      </DialogContent>
    </Dialog>
  );
};

export default ReceiptModal;