import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import axios from 'axios';
import { Eye, EyeOff, Globe, X } from 'lucide-react';
import Input from './Input';
import Button from './Button';
import { useApi } from '../hooks/useApi';
import { testAuth } from '../services/api';
import { useAuthStore } from '../store/authStore';


// Basic login schema
const loginSchema = z.object({
  username: z.string().min(1, 'Username is required'),
  password: z.string().min(1, 'Password is required'),
});

type LoginForm = z.infer<typeof loginSchema>;

interface LoginModalProps {
  isOpen: boolean;
  onClose: () => void;
  onLoginSuccess: () => void;
}

export default function LoginModal({ isOpen, onClose, onLoginSuccess }: LoginModalProps) {
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState('');
  const { login } = useAuthStore();

  const loginForm = useForm<LoginForm>({
    resolver: zodResolver(loginSchema),
  });

  const testAuthApi = useApi(testAuth);

  const onLogin = async (data: LoginForm) => {
    try {
      await testAuthApi.request({ data });
    } catch (error: any) {
      if (error?.response?.status === 400) {
        login(data.username, data.password);
        onLoginSuccess();
        console.log('Login successful');
        onClose();
        loginForm.reset();
        return;
      }
      setError('Something went wrong. Please try again.');
    }
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div className="bg-white rounded-lg shadow-xl p-8 w-full max-w-md relative">
        {/* Close button */}
        <button
          onClick={onClose}
          className="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
        >
          <X size={24} />
        </button>

        <div className="text-center mb-8">
          <Globe className="w-12 h-12 text-blue-600 mx-auto mb-4" />
          <h2 className="text-2xl font-bold text-gray-900">Admin Login</h2>
          <p className="text-gray-600 mt-2">Sign in to manage countries</p>
        </div>

        {/* Error Message */}
        {error && (
          <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6">
            {error}
          </div>
        )}

        {/* Login Form */}
        <form onSubmit={loginForm.handleSubmit(onLogin)} className="space-y-6">
          <Input
            label="Username"
            placeholder="Enter username"
            error={loginForm.formState.errors.username?.message}
            {...loginForm.register('username')}
          />

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Password
            </label>
            <div className="relative">
              <input
                type={showPassword ? 'text' : 'password'}
                placeholder="Enter password"
                className={`w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10 ${loginForm.formState.errors.password ? 'border-red-500' : ''
                  }`}
                {...loginForm.register('password')}
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600"
              >
                {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
              </button>
            </div>
            {loginForm.formState.errors.password && (
              <p className="text-red-500 text-sm mt-1">
                {loginForm.formState.errors.password.message}
              </p>
            )}
          </div>

          <Button
            type="submit"
            loading={testAuthApi.loading}
            disabled={testAuthApi.loading}
            className="w-full"
          >
            {testAuthApi.loading ? 'Signing in...' : 'Sign In'}
          </Button>
        </form>

        <div className="mt-6 text-center text-sm text-gray-600">
          <p>Basic Auth credentials:</p>
          <p>Username: admin</p>
          <p>Password: admin123</p>
        </div>
      </div>
    </div>
  );
} 