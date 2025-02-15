import axios from "axios";
import type { AxiosInstance } from "axios";
import appConfigs from "@/app/appConfigurations";

const axiosInstance: AxiosInstance = axios.create({
  baseURL: appConfigs.baseUrl,
  headers: {
    Authorization: "",
    // Authorization: options.token ? `Bearer ${options.token}` : "",
  },
});

export default axiosInstance;
