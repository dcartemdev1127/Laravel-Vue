import { MenuItemType } from "@/components/layouts/types";

export const menuItems: MenuItemType[] = [
  {
    label: "Department",
    icon: "ph-bank-thin",
    link: "/department",
    id: "sidebarDepartment",
  },
  {
    label: "User Management",
    icon: "ph-users-thin",
    link: '/users',
    id: 'sidebarUsers'
  },
  {
    label: 'Workspace',
    icon: " ph-share-network-thin",
    link: '/workspace',
    id: 'sidebarWorkspace'
  },
];