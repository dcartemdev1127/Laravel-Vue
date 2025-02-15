const { VITE_APP_DEFAULT_AUTH, VITE_BASE_URL } = import.meta.env;

const appConfigs = {
  auth: VITE_APP_DEFAULT_AUTH,
  baseUrl: 'http://localhost:8000',
  googleMapAPIKey: "AIzaSyAbvyBxmMbFhrzP9Z8moyYr6dCr-pzjhBE"
};

export default appConfigs;
