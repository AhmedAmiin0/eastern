import React from 'react';

interface InputProps {
  label: string;
  type?: string;
  placeholder?: string;
  error?: string;
  className?: string;
  [key: string]: any; 
}

export default function Input({ 
  label, 
  type = 'text', 
  placeholder, 
  error, 
  className = '',
  ...props 
}: InputProps) {
  return (
    <div className={className}>
      <label className="block text-sm font-medium  mb-2">
        {label}
      </label>
      <input
        type={type}
        placeholder={placeholder}
        className={`w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 ${
          error ? 'border-red-500' : ''
        }`}
        {...props}
      />
      {error && (
        <p className="text-red-500 text-sm mt-1">
          {error}
        </p>
      )}
    </div>
  );
} 