'use client';

import { Geist, Geist_Mono } from "next/font/google";
import "./globals.css";
import Button from "@/components/Button";
import { Globe, LogIn, User, LogOut } from "lucide-react";
import { useState, useEffect } from 'react';
import LoginModal from "@/components/LoginModal";
import { useAuthStore } from '../store/authStore';

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const { isAuthenticated, logout, checkAuth } = useAuthStore();
  const [showLoginModal, setShowLoginModal] = useState(false);

  useEffect(() => {
    checkAuth();
  }, [checkAuth]);

  const handleLoginSuccess = () => {
    setShowLoginModal(false);
  };

  const handleLogout = () => {
    logout();
  };

  return (
    <html lang="en">
      <body
        className={`${geistSans.variable} ${geistMono.variable} antialiased`}
      >
        <div className="min-h-screen bg-gray-50">
          <header className="bg-white shadow-sm border-b">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
              <div className="flex justify-between items-center h-16">
                <div className="flex items-center space-x-3">
                  <Globe className="w-8 h-8 text-blue-600" />
                  <h1 className="text-xl font-semibold text-gray-900">Country Manager</h1>
                </div>
                <div className="flex items-center space-x-4">
                  {isAuthenticated ? (
                    <>
                      <span className="text-sm text-gray-600 flex items-center">
                        <User className="w-4 h-4 mr-1" />
                        Admin
                      </span>
                      <Button
                        variant="secondary"
                        size="sm"
                        onClick={handleLogout}
                        className="flex items-center space-x-2"
                      >
                        <LogOut className="w-4 h-4" />
                        <span>Logout</span>
                      </Button>
                    </>
                  ) : (
                    <Button
                      variant="primary"
                      size="sm"
                      onClick={() => setShowLoginModal(true)}
                      className="flex items-center space-x-2"
                    >
                      <LogIn className="w-4 h-4" />
                      <span>Login</span>
                    </Button>
                  )}
                </div>
              </div>
            </div>
          </header>

          <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {children}
          </main>

          <LoginModal
            isOpen={showLoginModal}
            onClose={() => setShowLoginModal(false)}
            onLoginSuccess={handleLoginSuccess}
          />
        </div>
      </body>
    </html>
  );
}
