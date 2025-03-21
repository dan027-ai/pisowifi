
import React, { useEffect } from 'react';
import mermaid from 'mermaid';

const MermaidClassDiagram = () => {
  useEffect(() => {
    mermaid.initialize({
      startOnLoad: true,
      theme: 'default',
      securityLevel: 'loose',
    });
    mermaid.contentLoaded();
  }, []);

  // Mermaid syntax for the PisoWifi System Class Diagram
  const diagram = `
  classDiagram
    class SystemAdmin {
      -adminID: String
      -username: String
      +createVoucher()
      +manageVouchers()
      +viewTransactions()
    }
    
    class Customer {
      -phoneNumber: String
      -email: String
      -voucherCode: String
      +purchaseVoucher()
      +useWifi()
    }
    
    class PaymentGateway {
      -gatewayID: String
      -paymentMethod: String
      -transactionID: String
      +processPayment()
      +verifyTransaction()
    }
    
    class Voucher {
      -voucherID: String
      -price: Double
      -duration: String
      +generateCode()
      +activateVoucher()
    }
    
    class PisoWifiDevice {
      -deviceID: String
      -status: String
      -ipAddress: String
      +validateCode()
      +manageConnection()
    }
    
    class Transaction {
      -transactionID: String
      -amount: Double
      -date: DateTime
      -status: String
      +recordTransaction()
    }
    
    SystemAdmin --> Voucher : Creates
    SystemAdmin --> PisoWifiDevice : Manages
    Customer --> PaymentGateway : Uses
    Customer --> PisoWifiDevice : Connects to
    PaymentGateway --> Voucher : Processes payment for
    PaymentGateway --> Transaction : Creates
    Voucher --> Transaction : Associated with
    SystemAdmin --> Transaction : Views
  `;

  return (
    <div className="max-w-4xl mx-auto p-8">
      <h2 className="text-2xl font-semibold mb-6 text-center">PisoWifi System Class Diagram (Mermaid)</h2>
      <div className="bg-white p-6 rounded-lg shadow-lg overflow-auto">
        <pre className="mermaid">
          {diagram}
        </pre>
      </div>
      
      <div className="mt-8 bg-gray-100 p-4 rounded-lg">
        <h3 className="text-lg font-medium mb-2">Mermaid Script</h3>
        <p className="text-sm mb-2">Copy this script to use in any Mermaid-compatible editor (like draw.io with Mermaid plugin):</p>
        <pre className="bg-gray-800 text-white p-4 rounded overflow-x-auto text-sm">
          {diagram}
        </pre>
      </div>
    </div>
  );
};

export default MermaidClassDiagram;
