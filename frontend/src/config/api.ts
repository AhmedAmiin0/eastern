// API base URL
export const API_BASE_URL = 'http://localhost:8084/api/v1';

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