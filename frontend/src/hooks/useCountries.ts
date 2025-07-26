import { useState, useEffect } from 'react';
import { useApi } from './useApi';
import { 
  getCountries, 
  createCountry, 
  updateCountry, 
  deleteCountry, 
  Country,
  CountryForm
} from '../services/api';
import { useAuthStore } from '../store/authStore';

export const useCountries = () => {
  const [countries, setCountries] = useState<Country[]>([]);
  const [showModal, setShowModal] = useState(false);
  const [editingCountry, setEditingCountry] = useState<Country | null>(null);
  const { isAuthenticated } = useAuthStore();

  const countriesApi = useApi<{ data: Country[] }>(getCountries);
  const createCountryApi = useApi<Country>(createCountry);
  const updateCountryApi = useApi<Country>(updateCountry);
  const deleteCountryApi = useApi<void>(deleteCountry);

  useEffect(() => {
    fetchCountries();
  }, []);

  const fetchCountries = async () => {
    const data = await countriesApi.request();
    setCountries(data?.data || []);
    console.log('Countries:', countries);
  };

  const openCreateModal = () => {
    if (!isAuthenticated) return;
    setEditingCountry(null);
    setShowModal(true);
  };

  const openEditModal = (country: Country) => {
    if (!isAuthenticated) return;
    setEditingCountry(country);
    setShowModal(true);
  };

  const closeModal = () => {
    setShowModal(false);
    setEditingCountry(null);
  };

  const handleSubmit = async (data: CountryForm) => {
    try {
      if (editingCountry) {
        await updateCountryApi.request({ data: { id: editingCountry.id, formData: data } });
      } else {
        await createCountryApi.request({ data });
      }
      fetchCountries();
      closeModal();
    } catch (error) {
      console.error('Failed to save country:', error);
    }
  };

  const handleDeleteCountry = async (id: number) => {
    if (!isAuthenticated) { 
      return;
    }
    
    try {
      await deleteCountryApi.request({ data: id });
      fetchCountries();
    } catch (error) {
      console.error('Failed to delete country:', error);
    }
  };

  const isLoading = countriesApi.loading || createCountryApi.loading || updateCountryApi.loading || deleteCountryApi.loading;
  const error = countriesApi.error || createCountryApi.error || updateCountryApi.error || deleteCountryApi.error;


  return {
    // Data
    countries,
    isLoading,
    error,
    
    // Modal state
    showModal,
    editingCountry,
    
    // Actions
    openCreateModal,
    openEditModal,
    closeModal,
    handleSubmit,
    handleDeleteCountry,
    
    // Refresh
    refresh: fetchCountries,
  };
}; 