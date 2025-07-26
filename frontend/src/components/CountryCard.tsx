import { 
  Edit, 
  Trash2, 
  Users,
  MapPin,
  Flag
} from 'lucide-react';
import Button from './Button';
import { Country } from '../services/api';
import { useAuthStore } from '../store/authStore';

interface CountryCardProps {
  country: Country;
  onEdit: (country: Country) => void;
  onDelete: (id: number) => void;
}

export default function CountryCard({ country, onEdit, onDelete }: CountryCardProps) {
  const { isAuthenticated } = useAuthStore();

  return (
    <div className="bg-white rounded-lg shadow-md overflow-hidden">
      <div className="h-48 bg-gray-200 flex items-center justify-center">
        <img
          src={country.flag}
          alt={`Flag of ${country.name}`}
          className="w-full h-full object-cover"
          onError={(e) => {
            e.currentTarget.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIwIiBoZWlnaHQ9IjE2MCIgdmlld0JveD0iMCAwIDMyMCAxNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMjAiIGhlaWdodD0iMTYwIiBmaWxsPSIjRjNGNEY2Ii8+Cjx0ZXh0IHg9IjE2MCIgeT0iODAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzY2NzM4NSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+RmxhZyBub3QgYXZhaWxhYmxlPC90ZXh0Pgo8L3N2Zz4K';
          }}
        />
      </div>
      <div className="p-6">
        <h3 className="text-lg font-semibold text-gray-900 mb-2">{country.name}</h3>
        <div className="space-y-2 text-sm text-gray-600">
          <div className="flex items-center">
            <MapPin className="w-4 h-4 mr-2" />
            <span>{country.region}, {country.subRegion}</span>
          </div>
          <div className="flex items-center">
            <Users className="w-4 h-4 mr-2" />
            <span>{country.population.toLocaleString()}</span>
          </div>
          <div className="flex items-center">
            <Flag className="w-4 h-4 mr-2" />
            <span>{country.demonym}</span>
          </div>
          {country.currency && (
            <div className="text-sm">
              <span className="font-medium">Currency:</span> {country.currency.name} ({country.currency.symbol})
            </div>
          )}
          <div className="text-sm">
            <span className="font-medium">Independent:</span> {country.independant ? 'Yes' : 'No'}
          </div>
        </div>
        {isAuthenticated && ( 
          <div className="flex space-x-2 mt-4">
            <Button
              variant="warning"
              size="sm"
              onClick={() => onEdit(country)}
              className="flex-1"
            >
              <Edit className="w-3 h-3 inline mr-1" />
              Edit
            </Button>
            <Button
              variant="danger"
              size="sm"
              onClick={() => onDelete(country.id)}
              className="flex-1"
            >
              <Trash2 className="w-3 h-3 inline mr-1" />
              Delete
            </Button>
          </div>
        )}
      </div>
    </div>
  );
} 