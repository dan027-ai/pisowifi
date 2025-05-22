
import React, { useEffect } from 'react';
import './App.css';

function App() {
  useEffect(() => {
    // Redirect to the PHP implementation
    window.location.href = '/pisowifi/index.php';
  }, []);

  return (
    <div className="App">
      <div className="container mx-auto px-4 py-16">
        <h1 className="text-4xl font-bold text-center text-blue-600 mb-8">
          JM PisoWifi Plus System
        </h1>
        <div className="text-center mb-8">
          <p className="text-lg text-gray-600">
            Redirecting to PisoWifi homepage...
          </p>
        </div>
      </div>
    </div>
  );
}

export default App;
