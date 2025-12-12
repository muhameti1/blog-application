export default defineNuxtRouteMiddleware((to, from) => {
  const { isAuthenticated, initAuth } = useAuth();

  // Initialize auth state from localStorage
  initAuth();

  // If already authenticated, redirect to dashboard
  if (isAuthenticated.value) {
    return navigateTo("/dashboard");
  }
});
