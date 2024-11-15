import { Button } from "@/components/ui/button";
import { useNavigate } from "react-router-dom";

const Home = () => {
  const navigate = useNavigate();

  return (
    <div className="min-h-screen bg-gradient-to-b from-blue-50 to-white">
      <div className="container mx-auto px-4 py-16">
        <div className="text-center space-y-8">
          <h1 className="text-5xl font-bold text-gcash-blue">
            Piso WiFi Connect
          </h1>
          
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Fast and affordable internet access. Purchase your WiFi voucher now and stay connected!
          </p>

          <div className="grid gap-8 md:grid-cols-3 max-w-4xl mx-auto mt-12">
            <div className="bg-white p-6 rounded-xl shadow-md">
              <div className="text-4xl text-gcash-blue mb-4">⚡</div>
              <h3 className="text-xl font-semibold mb-2">Fast Connection</h3>
              <p className="text-gray-600">High-speed internet access for all your needs</p>
            </div>

            <div className="bg-white p-6 rounded-xl shadow-md">
              <div className="text-4xl text-gcash-blue mb-4">💰</div>
              <h3 className="text-xl font-semibold mb-2">Affordable Rates</h3>
              <p className="text-gray-600">Starting from just ₱5 for 3 hours of access</p>
            </div>

            <div className="bg-white p-6 rounded-xl shadow-md">
              <div className="text-4xl text-gcash-blue mb-4">🔒</div>
              <h3 className="text-xl font-semibold mb-2">Secure Network</h3>
              <p className="text-gray-600">Safe and encrypted connection for your privacy</p>
            </div>
          </div>

          <div className="mt-12">
            <Button 
              onClick={() => navigate("/vouchers")}
              className="bg-gcash-blue hover:bg-gcash-secondary text-xl px-8 py-6 h-auto"
            >
              Buy WiFi Voucher Now
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Home;