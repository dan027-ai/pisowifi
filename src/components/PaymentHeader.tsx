interface PaymentHeaderProps {
  title: string;
  description: string;
}

const PaymentHeader = ({ title, description }: PaymentHeaderProps) => {
  return (
    <div className="text-center mb-12">
      <h1 className="text-4xl font-bold mb-4">{title}</h1>
      <p className="text-gray-600 max-w-2xl mx-auto">{description}</p>
    </div>
  );
};

export default PaymentHeader;