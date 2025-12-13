import { ref, onMounted, onUnmounted } from "vue";

interface RealtimeUpdateOptions {
  interval?: number;
  enabled?: boolean;
  initialData?: any;
}

export const useRealtime = <T>(
  fetchFunction: () => Promise<T>,
  options: RealtimeUpdateOptions = {}
) => {
  const { interval = 5000, enabled = true, initialData = null } = options;

  const data = ref<T>(initialData);
  const loading = ref(true);
  const error = ref<string | null>(null);
  const lastUpdated = ref<Date | null>(null);

  let intervalId: NodeJS.Timeout | null = null;
  let isPolling = ref(false);

  const fetchData = async (silent = false) => {
    if (!enabled || isPolling.value) return;

    if (!silent) loading.value = true;
    error.value = null;

    try {
      isPolling.value = true;
      data.value = await fetchFunction();
      lastUpdated.value = new Date();
    } catch (err: any) {
      error.value = err.data?.message || "Failed to fetch data";
      console.error("Realtime fetch error:", err);
    } finally {
      loading.value = false;
      isPolling.value = false;
    }
  };

  const startPolling = () => {
    if (!enabled || intervalId) return;

    // Initial fetch
    fetchData();

    // Start polling
    intervalId = setInterval(() => {
      fetchData(true); // Silent updates
    }, interval);
  };

  const stopPolling = () => {
    if (intervalId) {
      clearInterval(intervalId);
      intervalId = null;
    }
  };

  const refresh = () => {
    fetchData();
  };

  onMounted(() => {
    if (enabled) {
      startPolling();
    }
  });

  onUnmounted(() => {
    stopPolling();
  });

  return {
    data,
    loading,
    error,
    lastUpdated,
    refresh,
    startPolling,
    stopPolling,
  };
};
