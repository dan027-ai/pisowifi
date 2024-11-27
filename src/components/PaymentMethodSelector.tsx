import { Link } from "react-router-dom";
import type { PaymentMethod } from "@/types/voucher";

interface PaymentMethodSelectorProps {
  currentMethod: PaymentMethod;
}

const PaymentMethodSelector = ({ currentMethod }: PaymentMethodSelectorProps) => {
  return (
    <div className="flex justify-center gap-4 mb-8">
      <Link
        to="/vouchers?method=gcash"
        className={`px-6 py-3 rounded-lg transition-colors ${
          currentMethod === 'gcash'
            ? 'bg-gcash-blue text-white'
            : 'bg-gray-100 hover:bg-gray-200'
        }`}
      >
        GCash
      </Link>
      <Link
        to="/vouchers?method=paymaya"
        className={`px-6 py-3 rounded-lg transition-colors ${
          currentMethod === 'paymaya'
            ? 'bg-paymaya-green text-white'
            : 'bg-gray-100 hover:bg-gray-200'
        }`}
      >
        PayMaya
      </Link>
    </div>
  );
};

export default PaymentMethodSelector;