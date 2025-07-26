import { useState, useCallback, useRef } from 'react';
import axios, { AxiosRequestConfig, AxiosResponse, AxiosError } from 'axios';
import { API_BASE_URL } from '../config/api';

export interface ApiFunction<T = any> {
  (data?: T): AxiosRequestConfig;
}

export interface ApiRequestArgs<T = any, V = any> {
  data?: T;
  options?: ApiOptions<V>;
}

export interface ApiOptions<V = any> {
  handleValidationErrors?: (errors: V) => void;
}

export interface ApiResponse<T = any> {
  data: T | null;
  loading: boolean;
  error: any;
  isSuccess: boolean;
  request: (args?: ApiRequestArgs) => Promise<T | undefined>;
  abort: () => void;
  abortLatest: (data?: any) => Promise<T | undefined>;
}

export const useApi = <T = any, RequestData = any, ValidationErrors = any>(
  apiFunction: ApiFunction<RequestData>
): ApiResponse<T> => {
  const [data, setData] = useState<T | null>(null);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<any>(null);
  const [isSuccess, setIsSuccess] = useState<boolean>(false);
  const controllerRef = useRef<AbortController | null>(null);


  const request = useCallback(async (args?: ApiRequestArgs<RequestData, ValidationErrors>): Promise<T | undefined> => {
    setLoading(true);
    setError(null);
    setIsSuccess(false);

    if (controllerRef.current) {
      controllerRef.current.abort();
    }

    controllerRef.current = new AbortController();

    try {
      const config = apiFunction(args?.data);
      const response: AxiosResponse<T> = await axios({
        ...config,
        url: `${API_BASE_URL}${config.url}`,
        signal: controllerRef.current.signal,
      });


      setIsSuccess(true);
      return response.data;
    } catch (e: any) {
      setIsSuccess(false);
      handleErrors(e, args?.options);
    } finally {
      setLoading(false);
    }
  }, [apiFunction]);

  const abort = useCallback(() => {
    if (controllerRef.current) {
      controllerRef.current.abort();
    }
  }, []);

  const abortLatest = useCallback((data?: RequestData) => {
    if (loading) {
      abort();
    }
    console.log(data);
    return request({ data });
  }, [loading, abort, request]);

  const handleErrors = useCallback((e: AxiosError, options?: ApiOptions<ValidationErrors>) => {
    if (e?.code === 'ERR_CANCELED') {
      return undefined;
    }

    if (e) {
      if (e.response?.data) {
        setError(e.response.data);
        
        if (e.response.data && typeof e.response.data === 'object' && 'message' in e.response.data) {
          console.error((e.response.data as any).message);
        }

        if (options?.handleValidationErrors && e.response.data && typeof e.response.data === 'object' && 'errors' in e.response.data) {
          const errors = (e.response.data as any).errors;
          options.handleValidationErrors(errors);
        } else if (e.response.data && typeof e.response.data === 'object' && 'errors' in e.response.data && !options) {
          console.error((e.response.data as any).message);
        }
      } else {
        console.error('Something went wrong');
      }

      if (e.response?.status === 401) {
        localStorage.removeItem('apiCredentials');
        window.location.href = '/';
      }

      throw e;
    }
  }, []);

  return {
    data,
    loading,
    error,
    isSuccess,
    request,
    abort,
    abortLatest,
  };
}; 