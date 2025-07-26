import React from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { X } from 'lucide-react';
import Input from './Input';
import Button from './Button';

// Country schema
const countrySchema = z.object({
  name: z.string().min(1, 'Name is required'),
  region: z.string().min(1, 'Region is required'),
  subRegion: z.string().min(1, 'Sub-region is required'),
  demonym: z.string().min(1, 'Demonym is required'),
  population: z.number().min(1, 'Population must be positive'),
  independant: z.boolean(),
  flag: z.string().url('Must be a valid URL'),
  currency: z.object({
    name: z.string().min(1, 'Currency name is required'),
    symbol: z.string().min(1, 'Currency symbol is required'),
  }).optional(),
});

type CountryForm = z.infer<typeof countrySchema>;

interface Country {
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

interface CountryModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSubmit: (data: CountryForm) => Promise<void>;
  editingCountry: Country | null;
  loading?: boolean;
}

export default function CountryModal({ 
  isOpen, 
  onClose, 
  onSubmit, 
  editingCountry, 
  loading = false 
}: CountryModalProps) {
  const countryForm = useForm<CountryForm>({
    resolver: zodResolver(countrySchema),
  });

  // Reset form when editingCountry changes
  React.useEffect(() => {
    if (editingCountry) {
      countryForm.reset({
        name: editingCountry.name,
        region: editingCountry.region,
        subRegion: editingCountry.subRegion,
        demonym: editingCountry.demonym,
        population: editingCountry.population,
        independant: editingCountry.independant,
        flag: editingCountry.flag,
        currency: editingCountry.currency,
      });
    } else {
      countryForm.reset();
    }
  }, [editingCountry, countryForm]);

  const handleSubmit = async (data: CountryForm) => {
    await onSubmit(data);
    onClose();
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div className="bg-white rounded-lg shadow-xl p-8 w-full max-w-2xl max-h-[90vh] overflow-y-auto relative">
        {/* Close button */}
        <button
          onClick={onClose}
          className="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
        >
          <X size={24} />
        </button>

        <h2 className="text-2xl font-bold text-gray-900 mb-6">
          {editingCountry ? 'Edit Country' : 'Add New Country'}
        </h2>

        <form onSubmit={countryForm.handleSubmit(handleSubmit)} className="space-y-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <Input
              label="Name"
              placeholder="Enter country name"
              error={countryForm.formState.errors.name?.message}
              {...countryForm.register('name')}
            />

            <Input
              label="Region"
              placeholder="Enter region"
              error={countryForm.formState.errors.region?.message}
              {...countryForm.register('region')}
            />

            <Input
              label="Sub-Region"
              placeholder="Enter sub-region"
              error={countryForm.formState.errors.subRegion?.message}
              {...countryForm.register('subRegion')}
            />

            <Input
              label="Demonym"
              placeholder="Enter demonym"
              error={countryForm.formState.errors.demonym?.message}
              {...countryForm.register('demonym')}
            />

            <Input
              label="Population"
              type="number"
              placeholder="Enter population"
              error={countryForm.formState.errors.population?.message}
              {...countryForm.register('population', { valueAsNumber: true })}
            />

            <Input
              label="Flag URL"
              type="url"
              placeholder="Enter flag URL"
              error={countryForm.formState.errors.flag?.message}
              {...countryForm.register('flag')}
            />
          </div>

          <div className="flex items-center space-x-2">
            <input
              type="checkbox"
              id="independant"
              {...countryForm.register('independant')}
              className="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            />
            <label htmlFor="independant" className="text-sm font-medium text-gray-700">
              Independent
            </label>
          </div>

          <div className="border-t pt-6">
            <h3 className="text-lg font-medium text-gray-900 mb-4">Currency (Optional)</h3>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <Input
                label="Currency Name"
                placeholder="Enter currency name"
                error={countryForm.formState.errors.currency?.name?.message}
                {...countryForm.register('currency.name')}
              />

              <Input
                label="Currency Symbol"
                placeholder="Enter currency symbol"
                error={countryForm.formState.errors.currency?.symbol?.message}
                {...countryForm.register('currency.symbol')}
              />
            </div>
          </div>

          <div className="flex justify-end space-x-4 pt-6">
            <Button
              type="button"
              variant="secondary"
              onClick={onClose}
              disabled={loading}
            >
              Cancel
            </Button>
            <Button
              type="submit"
              loading={loading || countryForm.formState.isSubmitting}
              disabled={loading || countryForm.formState.isSubmitting}
            >
              {editingCountry ? 'Update Country' : 'Create Country'}
            </Button>
          </div>
        </form>
      </div>
    </div>
  );
} 