import { Button } from "@/components/ui/button";
import { useNavigate } from "react-router-dom";

const Home = () => {
  const navigate = useNavigate();

  return (
    <div className="min-h-screen bg-gradient-to-b from-blue-50 to-white dark:from-blue-950 dark:to-gray-900">
      <div className="container mx-auto px-4 py-16">
        <div className="text-center space-y-8">
          <h1 className="text-5xl font-bold text-gcash-blue dark:text-blue-400">
            Piso WiFi Connect
          </h1>
          
          <p className="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
            Fast and affordable internet access. Purchase your WiFi voucher now and stay connected!
          </p>

          <div className="grid gap-8 md:grid-cols-3 max-w-4xl mx-auto mt-12">
            <div className="bg-white/90 backdrop-blur-sm p-8 rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group dark:bg-gray-800/80 dark:border-gray-700">
              <div className="text-4xl mb-4 bg-gcash-blue/10 dark:bg-yellow-400/90 w-12 h-12 rounded-full flex items-center justify-center mx-auto">⚡</div>
              <h3 className="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-100">Fast Connection</h3>
              <p className="text-gray-600 dark:text-gray-400">High-speed internet access for all your needs</p>
            </div>

            <div className="bg-white/90 backdrop-blur-sm p-8 rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group dark:bg-gray-800/80 dark:border-gray-700">
              <div className="text-4xl mb-4 bg-gcash-blue/10 dark:bg-yellow-400/90 w-12 h-12 rounded-full flex items-center justify-center mx-auto">💰</div>
              <h3 className="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-100">Affordable Rates</h3>
              <p className="text-gray-600 dark:text-gray-400">Starting from just ₱5 for 3 hours of access</p>
            </div>

            <div className="bg-white/90 backdrop-blur-sm p-8 rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group dark:bg-gray-800/80 dark:border-gray-700">
              <div className="text-4xl mb-4 bg-gcash-blue/10 dark:bg-yellow-400/90 w-12 h-12 rounded-full flex items-center justify-center mx-auto">🔒</div>
              <h3 className="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-100">Secure Network</h3>
              <p className="text-gray-600 dark:text-gray-400">Safe and encrypted connection for your privacy</p>
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