import React from 'react';

const ContextDiagram = () => {
  return (
    <div className="max-w-4xl mx-auto p-8">
      <svg viewBox="0 0 800 600" className="w-full">
        {/* External Entity: Customer */}
        <rect x="50" y="100" width="120" height="60" fill="#4B5563" />
        <text x="110" y="135" textAnchor="middle" fill="white" className="text-sm">
          Customer
        </text>

        {/* Central Process: JM PisoWifi Plus */}
        <circle cx="400" cy="250" r="100" fill="none" stroke="#06B6D4" strokeWidth="2" />
        <text x="400" y="230" textAnchor="middle" className="text-lg">0</text>
        <text x="400" y="260" textAnchor="middle" className="text-sm">
          JM PisoWifi Plus
        </text>
        <text x="400" y="280" textAnchor="middle" className="text-sm">
          System
        </text>

        {/* External Entity: Admin */}
        <rect x="630" y="100" width="120" height="60" fill="#4B5563" />
        <text x="690" y="135" textAnchor="middle" fill="white" className="text-sm">
          Admin
        </text>

        {/* External Entity: Payment Gateway */}
        <rect x="630" y="400" width="120" height="60" fill="#4B5563" />
        <text x="690" y="435" textAnchor="middle" fill="white" className="text-sm">
          Payment Gateway
        </text>

        {/* Data Flows */}
        {/* Customer to System */}
        <path d="M170 130 L300 250" fill="none" stroke="black" markerEnd="url(#arrow)" />
        <text x="200" y="170" className="text-xs">Voucher Request</text>

        {/* System to Customer */}
        <path d="M300 230 L170 110" fill="none" stroke="black" markerEnd="url(#arrow)" />
        <text x="200" y="200" className="text-xs">Access Code</text>

        {/* System to Admin */}
        <path d="M500 250 L630 130" fill="none" stroke="black" markerEnd="url(#arrow)" />
        <text x="580" y="170" className="text-xs">Management Reports</text>

        {/* Admin to System */}
        <path d="M630 110 L500 230" fill="none" stroke="black" markerEnd="url(#arrow)" />
        <text x="580" y="200" className="text-xs">System Configuration</text>

        {/* System to Payment Gateway */}
        <path d="M470 330 L630 430" fill="none" stroke="black" markerEnd="url(#arrow)" />
        <text x="520" y="400" className="text-xs">Payment Request</text>

        {/* Payment Gateway to System */}
        <path d="M630 410 L470 310" fill="none" stroke="black" markerEnd="url(#arrow)" />
        <text x="520" y="370" className="text-xs">Payment Confirmation</text>

        {/* Arrow Marker Definition */}
        <defs>
          <marker
            id="arrow"
            viewBox="0 0 10 10"
            refX="9"
            refY="5"
            markerWidth="6"
            markerHeight="6"
            orient="auto"
          >
            <path d="M 0 0 L 10 5 L 0 10 z" fill="black" />
          </marker>
        </defs>
      </svg>
    </div>
  );
};

export default ContextDiagram;