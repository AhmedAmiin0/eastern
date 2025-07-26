// API base URL from environment variable
export const API_BASE_URL = process.env.NEXT_PUBLIC_API_BASE_URL

// API endpoint configuration interface
export interface EndpointConfig {
  url: string;
  method: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';
  data?: any;
}

// API response interface
export interface ApiResponse<T> {
  message: string;
  data: T;
} 