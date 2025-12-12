import type { User } from '~/types/auth'

interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
  role?: 'admin' | 'author' | 'reader'
}

interface LoginData {
  email: string
  password: string
}

interface ForgotPasswordData {
  email: string
}

interface ResetPasswordData {
  token: string
  email: string
  password: string
  password_confirmation: string
}

interface AuthResponse {
  message: string
  user: User
  token: string
}

export const useAuth = () => {
  const config = useRuntimeConfig()
  const baseURL = config.public.apiBase

  // Reactive state for current user
  const user = useState<User | null>('user', () => null)
  const token = useState<string | null>('token', () => null)
  const isAuthenticated = computed(() => !!user.value && !!token.value)

  // Initialize auth state from localStorage
  const initAuth = () => {
    if (import.meta.client) {
      const storedToken = localStorage.getItem('token')
      const storedUser = localStorage.getItem('user')
      
      if (storedToken && storedUser) {
        token.value = storedToken
        user.value = JSON.parse(storedUser)
      }
    }
  }

  // Save auth state to localStorage
  const saveAuth = (authUser: User, authToken: string) => {
    if (import.meta.client) {
      localStorage.setItem('token', authToken)
      localStorage.setItem('user', JSON.stringify(authUser))
    }
    user.value = authUser
    token.value = authToken
  }

  // Clear auth state
  const clearAuth = () => {
    if (import.meta.client) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
    user.value = null
    token.value = null
  }

  // Get auth headers
  const getAuthHeaders = () => {
    return token.value
      ? {
          Authorization: `Bearer ${token.value}`,
          Accept: 'application/json',
        }
      : {
          Accept: 'application/json',
        }
  }

  // Register a new user
  const register = async (data: RegisterData): Promise<AuthResponse> => {
    const response = await $fetch<AuthResponse>(`${baseURL}/register`, {
      method: 'POST',
      body: data,
    })

    saveAuth(response.user, response.token)
    return response
  }

  // Login user
  const login = async (data: LoginData): Promise<AuthResponse> => {
    const response = await $fetch<AuthResponse>(`${baseURL}/login`, {
      method: 'POST',
      body: data,
    })

    saveAuth(response.user, response.token)
    return response
  }

  // Logout user
  const logout = async (): Promise<void> => {
    try {
      await $fetch(`${baseURL}/logout`, {
        method: 'POST',
        headers: getAuthHeaders(),
      })
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      clearAuth()
    }
  }

  // Get current authenticated user
  const fetchUser = async (): Promise<User> => {
    const response = await $fetch<{ user: User }>(`${baseURL}/user`, {
      headers: getAuthHeaders(),
    })

    user.value = response.user
    if (import.meta.client) {
      localStorage.setItem('user', JSON.stringify(response.user))
    }
    return response.user
  }

  // Send password reset link
  const forgotPassword = async (data: ForgotPasswordData): Promise<{ message: string }> => {
    return await $fetch(`${baseURL}/forgot-password`, {
      method: 'POST',
      body: data,
    })
  }

  // Reset password
  const resetPassword = async (data: ResetPasswordData): Promise<{ message: string }> => {
    const response = await $fetch<{ message: string }>(`${baseURL}/reset-password`, {
      method: 'POST',
      body: data,
    })

    clearAuth()
    return response
  }

  // Check if user has a specific role
  const hasRole = (role: string): boolean => {
    return user.value?.role === role
  }

  // Check if user is admin
  const isAdmin = computed(() => hasRole('admin'))

  // Check if user is author
  const isAuthor = computed(() => hasRole('author'))

  // Check if user is reader
  const isReader = computed(() => hasRole('reader'))

  // Check if user can create posts
  const canCreatePosts = computed(() => isAdmin.value || isAuthor.value)

  return {
    user,
    token,
    isAuthenticated,
    isAdmin,
    isAuthor,
    isReader,
    canCreatePosts,
    initAuth,
    register,
    login,
    logout,
    fetchUser,
    forgotPassword,
    resetPassword,
    hasRole,
    getAuthHeaders,
  }
}
