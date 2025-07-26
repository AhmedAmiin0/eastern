import { 
  Plus, 
  Globe
} from 'lucide-react';
import Button from './Button';
import CountryModal from './CountryModal';
import CountryCard from './CountryCard';
import { Country } from '../services/api';
import { useAuthStore } from '../store/authStore';
import { useCountries } from '../hooks/useCountries';


export default function CountryList() {
  const { isAuthenticated } = useAuthStore();
  const {
    countries,
    isLoading,
    error,
    openCreateModal,
    openEditModal,
    handleDeleteCountry,
    showModal,
    editingCountry,
    closeModal,
    handleSubmit,
  } = useCountries();

  if (isLoading) {
    return (
      <div className="text-center py-12">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
        <p className="mt-4 text-gray-600">Loading countries...</p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6">
        {typeof error === 'string' ? error : 'An error occurred'}
      </div>
    );
  }

  return (
    <>
      <div className="flex justify-end mb-6">
        {isAuthenticated && ( 
          <Button
            onClick={openCreateModal}
            className="flex items-center space-x-2"
          >
            <Plus className="w-4 h-4" />
            <span>Add Country</span>
          </Button>
        )}
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {countries.map((country) => (
          <CountryCard
            key={country.id}
            country={country}
            onEdit={openEditModal}
            onDelete={handleDeleteCountry}
          />
        ))}
      </div>

      {countries.length === 0 && !isLoading && (
        <div className="text-center py-12">
          <Globe className="w-12 h-12 text-gray-400 mx-auto mb-4" />
          <p className="text-gray-600">No countries found</p>
        </div>
      )}

      <CountryModal
        isOpen={showModal}
        onClose={closeModal}
        onSubmit={handleSubmit}
        editingCountry={editingCountry}
        loading={isLoading}
      />
    </>
  );
} 