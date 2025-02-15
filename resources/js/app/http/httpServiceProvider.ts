import FakeBackendService from "@/app/http/services/fakeBackendService";
import HttpService from '@/app/http/httpService';

const fakeBackendService = new FakeBackendService();
const http = new HttpService()

export { fakeBackendService, http };
