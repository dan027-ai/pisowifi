
import React from 'react';

const ClassDiagram = () => {
  return (
    <div className="max-w-4xl mx-auto p-8">
      <h2 className="text-2xl font-semibold mb-6 text-center">PisoWifi System Class Diagram</h2>
      <div className="bg-white p-4 rounded-lg shadow-lg overflow-auto">
        <svg viewBox="0 0 1000 800" className="w-full">
          {/* Grid Background */}
          <defs>
            <pattern id="smallGrid" width="10" height="10" patternUnits="userSpaceOnUse">
              <path d="M 10 0 L 0 0 0 10" fill="none" stroke="gray" strokeWidth="0.5" opacity="0.2" />
            </pattern>
            <pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse">
              <rect width="100" height="100" fill="url(#smallGrid)" />
              <path d="M 100 0 L 0 0 0 100" fill="none" stroke="gray" strokeWidth="1" opacity="0.2" />
            </pattern>
          </defs>
          <rect width="1000" height="800" fill="url(#grid)" />

          {/* System Admin Class */}
          <g transform="translate(700, 100)">
            <rect width="200" height="150" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="80" x2="200" y2="80" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">System Admin</text>
            <text x="10" y="50" className="text-sm">adminID: String()</text>
            <text x="10" y="70" className="text-sm">username: String()</text>
            <text x="10" y="100" className="text-sm">createVoucher()</text>
            <text x="10" y="120" className="text-sm">manageVouchers()</text>
            <text x="10" y="140" className="text-sm">viewTransactions()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* Customer Class */}
          <g transform="translate(250, 250)">
            <rect width="200" height="150" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="100" x2="200" y2="100" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">Customer</text>
            <text x="10" y="50" className="text-sm">phoneNumber: String()</text>
            <text x="10" y="70" className="text-sm">email: String()</text>
            <text x="10" y="90" className="text-sm">voucherCode: String()</text>
            <text x="10" y="120" className="text-sm">purchaseVoucher()</text>
            <text x="10" y="140" className="text-sm">useWifi()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* Payment Gateway Class */}
          <g transform="translate(250, 500)">
            <rect width="200" height="150" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="100" x2="200" y2="100" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">Payment Gateway</text>
            <text x="10" y="50" className="text-sm">gatewayID: String()</text>
            <text x="10" y="70" className="text-sm">paymentMethod: String()</text>
            <text x="10" y="90" className="text-sm">transactionID: String()</text>
            <text x="10" y="120" className="text-sm">processPayment()</text>
            <text x="10" y="140" className="text-sm">verifyTransaction()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* Voucher Class */}
          <g transform="translate(700, 500)">
            <rect width="200" height="150" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="90" x2="200" y2="90" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">Voucher</text>
            <text x="10" y="50" className="text-sm">voucherID: String()</text>
            <text x="10" y="70" className="text-sm">price: Double()</text>
            <text x="10" y="90" className="text-sm">duration: String()</text>
            <text x="10" y="110" className="text-sm">generateCode()</text>
            <text x="10" y="130" className="text-sm">activateVoucher()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* PisoWifi Device Class */}
          <g transform="translate(700, 250)">
            <rect width="200" height="150" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="90" x2="200" y2="90" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">PisoWifi Device</text>
            <text x="10" y="50" className="text-sm">deviceID: String()</text>
            <text x="10" y="70" className="text-sm">status: String()</text>
            <text x="10" y="90" className="text-sm">ipAddress: String()</text>
            <text x="10" y="110" className="text-sm">validateCode()</text>
            <text x="10" y="130" className="text-sm">manageConnection()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* Transaction Class */}
          <g transform="translate(450, 700)">
            <rect width="200" height="150" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="110" x2="200" y2="110" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">Transaction</text>
            <text x="10" y="50" className="text-sm">transactionID: String()</text>
            <text x="10" y="70" className="text-sm">amount: Double()</text>
            <text x="10" y="90" className="text-sm">date: DateTime()</text>
            <text x="10" y="110" className="text-sm">status: String()</text>
            <text x="10" y="130" className="text-sm">recordTransaction()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* Relationship: Admin to Voucher */}
          <line x1="800" y1="250" x2="800" y2="500" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />
          <text x="810" y="370" className="text-xs">Creates</text>

          {/* Relationship: Admin to PisoWifi Device */}
          <line x1="750" y1="175" x2="750" y2="250" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />
          <text x="760" y="220" className="text-xs">Manages</text>

          {/* Relationship: Customer to Payment Gateway */}
          <line x1="350" y1="400" x2="350" y2="500" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />
          <text x="360" y="450" className="text-xs">Uses</text>

          {/* Relationship: Customer to PisoWifi Device */}
          <line x1="450" y1="325" x2="700" y2="325" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />
          <text x="550" y="315" className="text-xs">Connects to</text>

          {/* Relationship: Payment Gateway to Voucher */}
          <line x1="450" y1="575" x2="700" y2="575" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />
          <text x="550" y="565" className="text-xs">Processes payment for</text>

          {/* Relationship: Payment Gateway to Transaction */}
          <line x1="350" y1="650" x2="450" y2="700" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />
          <text x="370" y="680" className="text-xs">Creates</text>

          {/* Relationship: Voucher to Transaction */}
          <line x1="700" y1="650" x2="550" y2="700" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />
          <text x="600" y="680" className="text-xs">Associated with</text>

          {/* Relationship: Admin to Transaction */}
          <path d="M 900 150 L 950 150 L 950 700 L 650 700" fill="none" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />
          <text x="960" y="400" className="text-xs">Views</text>

          {/* Arrow marker definition */}
          <defs>
            <marker
              id="arrowhead"
              markerWidth="10"
              markerHeight="7"
              refX="10"
              refY="3.5"
              orient="auto"
            >
              <polygon points="0 0, 10 3.5, 0 7" fill="#333" />
            </marker>
          </defs>
        </svg>
      </div>
    </div>
  );
};

export default ClassDiagram;
