export interface Voucher {
  id: number;
  price: number;
  duration: string;
  description: string;
}

export interface PaymentFormData {
  phoneNumber: string;
  email: string;
  selectedVoucher: number;
}