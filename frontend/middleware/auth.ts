export default defineNuxtRouteMiddleware((to, from) => {
  const { isAuthenticated, initAuth } = useAuth()

  // Initialize auth state from localStorage
  initAuth()

  // If not authenticated, redirect to login
  if (!isAuthenticated.value) {
    return navigateTo('/login')
  }
})
