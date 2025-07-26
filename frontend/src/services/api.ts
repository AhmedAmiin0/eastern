import { AxiosRequestConfig } from 'axios';
import axios from 'axios';
import { API_BASE_URL } from '../config/api';

export interface Country {
  id: number;
  name: string;
  region: string;
  subRegion: string;
  demonym: string;
  population: number;
  independant: boolean;
  flag: string;
  currency?: {
    name: string;
    symbol: string;
  };
}

export interface CountryForm {
  name: string;
  region: string;
  subRegion: string;
  demonym: string;
  population: number;
  independant: boolean;
  flag: string;
  currency?: {
    name: string;
    symbol: string;
  };
}

export const getCountries = (): AxiosRequestConfig => ({
  url: '/countries/list',
  method: 'GET'
});

export const getCountry = (id?: number): AxiosRequestConfig => ({
  url: `/countries/${id}`,
  method: 'GET'
});

export const createCountry = (data?: CountryForm): AxiosRequestConfig => ({
  url: '/countries',
  method: 'POST',
  data
});

export const updateCountry = (data?: { id: number; formData: CountryForm }): AxiosRequestConfig => ({
  url: `/countries/${data?.id}`,
  method: 'PATCH',
  data: data?.formData
});

export const deleteCountry = (data?: number): AxiosRequestConfig => ({
  url: `/countries/${data}`,
  method: 'DELETE'
});

export const testAuth = (data?: { username: string; password: string }): AxiosRequestConfig => ({
  url: '/countries',
  method: 'POST',
  data: {},
  auth: { username: data?.username || '', password: data?.password || '' }
}); 