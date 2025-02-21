import { MenuItemType } from "@/components/layouts/types";

export const menuItems: MenuItemType[] = [
  {
    label: "Department",
    icon: "ph-folder-open",
    link: "/department",
    id: "sidebarDepartment",
  },
  {
    label: "User Management",
    icon: "ph-folder-open",
    link: '/users',
    id: 'sidebarUsers'
  },
  {
    label: 'Workspace',
    icon: "ph-folder-open",
    link: '/workspace',
    id: 'sidebarWorkspace'
  },
];