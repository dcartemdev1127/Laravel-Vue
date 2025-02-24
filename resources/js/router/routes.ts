import { DefaultLayout, AuthLayout, AdminLayout } from "@/layouts/index";

const authRoutes = [
  {
    path: `/signin`,
    name: "AccountSignIn",
    component: () => import("@/views/account/SignIn.vue"),
    meta: { title: "Sign In", authRequired: false },
  },
  {
    path: `/signup`,
    name: "AccountSignUp",
    component: () => import("@/views/account/SignUp.vue"),
    meta: { title: "Sign Up", authRequired: false },
  },
  {
    path: `/pass-reset`,
    name: "AccountResetPassword",
    component: () => import("@/views/account/ResetPassword.vue"),
    meta: { title: "Reset Password", authRequired: false },
  },
  {
    path: `/pass-change`,
    name: "AccountChangePassword",
    component: () => import("@/views/account/CreatePassword.vue"),
    meta: { title: "Create New Password", authRequired: false },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: AuthLayout },
  };
});

const adminRoute = [
  {
    path: `/department`,
    name: "department",
    component: () => import("@/views/department/Department.vue"),
    meta: { title: "Department", authRequired: true },
  },
  {
    path: `/category`,
    name: "category",
    component: () => import("@/views/category/index.vue"),
    meta: { title: "Category", authRequired: true },
  },
  {
    path: '/users',
    name: 'users',
    component: () => import("@/views/users/UserManagement.vue"),
    meta: { title: "Category", authRequired: true },
  },
  {
    path: '/workspace',
    name: 'workspace',
    component: () => import ('@/views/workspace/index.vue'),
    meta: { title: "Category", authRequired: true }
  },
  {
    path: '/edit_workspace',
    name: 'edit_workspace',
    component: () => import ('@/views/workspace/edit.vue'),
    meta: { title: "Category", authRequired: true },
  },
  {
    path: '/issue',
    name: 'issue',
    component: () => import ('@/views/issue/index.vue'),
    meta: { title: "Category", authRequired: true },
  },
  {
    path: '/form',
    name: 'form',
    component: () => import ('@/views/form/index.vue'),
    meta: { title: "Category", authRequired: true },
  },
  {
    path: '/step',
    name: 'step',
    component: () => import ('@/views/step/index.vue'),
    meta: { title: "Category", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: AdminLayout },
  };
});

const dashboardRoute = [
  {
    path: `/:id`,
    name: "dashboard",
    component: () => import("@/views/dashboard/Dashboard.vue"),
    meta: { title: "Dashboard", authRequired: false },
  },
  {
    path: `/report/:id`,
    name: "report",
    component: () => import("@/views/report/index.vue"),
    meta: { title: "Report", authRequired: false },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});

export const routes = [
  ...authRoutes,
  ...adminRoute,
  ...dashboardRoute,
  {
    path: "/logout",
    name: "Logout",
    component: () => import("@/views/account/Logout.vue"),
    meta: { title: "Logout", authRequired: false },
  },
];
