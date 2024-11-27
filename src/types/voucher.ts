export interface Voucher {
  id: number;
  price: number;
  duration: string;
  description: string | null;
  created_at?: string;
}

export interface Transaction {
  id: number;
  voucher_id: number;
  phone_number: string;
  email: string;
  amount: number;
  payment_method: string;
  status: string;
  created_at: string;
}

export type PaymentMethod = 'gcash' | 'paymaya';

export interface PaymentFormData {
  phoneNumber: string;
  email: string;
  selectedVoucher?: number;
}