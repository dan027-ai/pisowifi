
import React from 'react';

const ClassDiagram = () => {
  return (
    <div className="max-w-4xl mx-auto p-8">
      <h2 className="text-2xl font-semibold mb-6 text-center">System Class Diagram</h2>
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

          {/* Business Owner Class */}
          <g transform="translate(700, 100)">
            <rect width="200" height="150" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="80" x2="200" y2="80" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">Business Owner</text>
            <text x="10" y="50" className="text-sm">ownerID: String()</text>
            <text x="10" y="70" className="text-sm">businessName: String()</text>
            <text x="10" y="100" className="text-sm">Create Invoice()</text>
            <text x="10" y="120" className="text-sm">Manage Invoice()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* Client Class */}
          <g transform="translate(250, 250)">
            <rect width="200" height="150" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="100" x2="200" y2="100" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">Client</text>
            <text x="10" y="50" className="text-sm">clientID: String()</text>
            <text x="10" y="70" className="text-sm">name: String()</text>
            <text x="10" y="90" className="text-sm">email: String()</text>
            <text x="10" y="120" className="text-sm">makePayment()</text>
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
            <text x="10" y="70" className="text-sm">transactionID: String()</text>
            <text x="10" y="90" className="text-sm">status: String()</text>
            <text x="10" y="120" className="text-sm">authorizeTransaction()</text>
            <text x="10" y="140" className="text-sm">updateStatus()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* External Funds Class */}
          <g transform="translate(700, 500)">
            <rect width="200" height="150" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="80" x2="200" y2="80" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">External Funds</text>
            <text x="10" y="50" className="text-sm">fundID: String()</text>
            <text x="10" y="70" className="text-sm">amount: Double()</text>
            <text x="10" y="100" className="text-sm">transferFunds()</text>
            <text x="10" y="120" className="text-sm">sendConfirmation()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* Admin Class */}
          <g transform="translate(250, 700)">
            <rect width="200" height="100" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="60" x2="200" y2="60" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">Admin</text>
            <text x="10" y="50" className="text-sm">adminID: String()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* Banking System Class */}
          <g transform="translate(700, 700)">
            <rect width="200" height="100" fill="white" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="30" x2="200" y2="30" stroke="#333" strokeWidth="2" />
            <line x1="0" y1="60" x2="200" y2="60" stroke="#333" strokeWidth="2" />
            <text x="100" y="20" textAnchor="middle" fontWeight="bold">Banking System</text>
            <text x="10" y="50" className="text-sm">bankID: String()</text>
            {/* Class Icon */}
            <rect x="10" y="10" width="16" height="16" fill="white" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="15" x2="26" y2="15" stroke="#333" strokeWidth="1" />
            <line x1="10" y1="20" x2="26" y2="20" stroke="#333" strokeWidth="1" />
          </g>

          {/* Relationship: Business Owner to Client */}
          <line x1="700" y1="175" x2="450" y2="250" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />

          {/* Relationship: Client to Payment Gateway */}
          <line x1="350" y1="400" x2="350" y2="500" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />

          {/* Relationship: Payment Gateway to External Funds */}
          <line x1="450" y1="575" x2="700" y2="575" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />

          {/* Relationship: External Funds to Banking System */}
          <line x1="800" y1="650" x2="800" y2="700" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />

          {/* Relationship: Admin to Payment Gateway */}
          <line x1="350" y1="700" x2="350" y2="650" stroke="#333" strokeWidth="1.5" markerEnd="url(#arrowhead)" />

          {/* Relationship: Banking System to External Funds */}
          <line x1="900" y1="700" x2="900" y2="650" stroke="#333" strokeWidth="1.5" strokeDasharray="5,5" markerEnd="url(#arrowhead)" />

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
