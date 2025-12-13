export const useNewsletter = () => {
  const config = useRuntimeConfig();
  const apiBaseUrl = config.public.apiBase;

  const subscribe = async (email: string) => {
    try {
      const response = await $fetch(`${apiBaseUrl}/newsletter/subscribe`, {
        method: "POST",
        body: { email },
      });
      return { success: true, data: response };
    } catch (error: any) {
      const message = error.data?.message || "Failed to subscribe";
      return { success: false, error: message };
    }
  };

  const unsubscribe = async (token: string) => {
    try {
      const response = await $fetch(
        `${apiBaseUrl}/newsletter/unsubscribe/${token}`,
        {
          method: "GET",
        }
      );
      return { success: true, data: response };
    } catch (error: any) {
      const message = error.data?.message || "Failed to unsubscribe";
      return { success: false, error: message };
    }
  };

  const checkStatus = async (email: string) => {
    try {
      const response = await $fetch(`${apiBaseUrl}/newsletter/status`, {
        method: "POST",
        body: { email },
      });
      return { success: true, data: response };
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || "Failed to check status",
      };
    }
  };

  return {
    subscribe,
    unsubscribe,
    checkStatus,
  };
};
