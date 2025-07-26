'use client';

import { Globe, ArrowRight } from 'lucide-react';
import Button from '../components/Button';
import Link from 'next/link';

export default function Home() {
  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
      <div className="text-center max-w-2xl mx-auto px-6">
        <div className="mb-8">
          <Globe className="w-24 h-24 text-blue-600 mx-auto mb-6" />
          <h1 className="text-4xl font-bold text-gray-900 mb-4">
            Country Manager
          </h1>
          <p className="text-xl text-gray-600 mb-8">
            Explore and manage countries from around the world. View detailed information about each country including population, region, currency, and more.
          </p>
        </div>

        <div className="space-y-4">
          <Link href="/countries">
            <Button className="flex items-center space-x-2 mx-auto">
              <span>View Countries</span>
              <ArrowRight className="w-4 h-4" />
            </Button>
          </Link>
          
          <p className="text-sm text-gray-500">
            Sign in to add, edit, or delete countries
          </p>
        </div>  
      </div>
    </div>
  );
}
