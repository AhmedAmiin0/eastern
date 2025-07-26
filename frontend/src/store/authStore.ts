import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import axios from 'axios';

interface AuthState {
  isAuthenticated: boolean;
  login: (username: string, password: string) => void;
  logout: () => void;
  checkAuth: () => void;
}

export const useAuthStore = create<AuthState>()(
  persist(
    (set, get) => ({
      isAuthenticated: false,
      
      login: (username: string, password: string) => {
        localStorage.setItem('apiCredentials', JSON.stringify({ username, password }));
        axios.defaults.auth = { username, password };
        set({ isAuthenticated: true });
      },
      
      logout: () => {
        localStorage.removeItem('apiCredentials');
        delete axios.defaults.auth;
        set({ isAuthenticated: false });
      },
      
      checkAuth: () => {
        const credentials = localStorage.getItem('apiCredentials');
        if (credentials) {
          const { username, password } = JSON.parse(credentials);
          axios.defaults.auth = { username, password };
          set({ isAuthenticated: true });
        } else {
          set({ isAuthenticated: false });
        }
      },
    }),
    {
      name: 'auth-storage',
      partialize: (state) => ({ isAuthenticated: state.isAuthenticated }),
    }
  )
); 