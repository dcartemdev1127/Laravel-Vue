import { BreadcrumbType } from "@/app/common/types/breadcrumb.type";
import { MenuSelectItemType } from "@/app/common/components/filters/types";
import { TableHeaderType } from "@/app/common/types/table.types";

export const breadcrumb: BreadcrumbType[] = [
  {
    title: "pages",
    disabled: false,
  },
  {
    title: "profile-setting",
    disabled: true,
  },
];

export const portfolio = [
  {
    color: "secondary",
    icon: "bx bxl-github",
    value: "@richardmarshall",
  },
  {
    color: "primary",
    icon: "bx bxl-facebook-circle",
    value: "www.steex.com",
  },
  {
    color: "success",
    icon: "bx bxl-dribbble",
    value: "@richard_marshall",
  },
  {
    color: "danger",
    icon: "bx bxl-instagram",
    value: "Richard Marshall",
  },
];

export const tabs = [
  { title: "Personal Details", value: "personal" },
  { title: "Change Password", value: "password" },
  { title: "Education", value: "education" },
  { title: "Security & Privacy", value: "security" },
];

export const skillsOptions: MenuSelectItemType[] = [
  { value: "illustrator", label: "Illustrator" },
  { value: "photoshop", label: "Photoshop" },
  { value: "css", label: "CSS" },
  { value: "html", label: "HTML" },
  { value: "javascript", label: "Javascript" },
  { value: "python", label: "Python" },
  { value: "php", label: "PHP" },
];

export const productsHeader: TableHeaderType[] = [
  { title: "Mobile" },
  { title: "IP Address" },
  { title: "Date" },
  { title: "Address" },
  { title: "Action" },
];

export const loginHistory = [
  {
    product: "iPhone 12 Pro",
    ip_address: "192.44.234.160",
    date: "18 Dec, 2023",
    location: "Los Angeles, United States",
  },
  {
    product: "Apple iPad Pro",
    ip_address: "192.44.234.162",
    date: "03 Jan, 2023",
    location: "Phoenix, United States",
  },
  {
    product: "Galaxy S21 Ultra 5G",
    ip_address: "192.45.234.54",
    date: "25 Feb, 2023",
    location: "Washington, United States",
  },
  {
    product: "Dell Inspiron 14",
    ip_address: "192.40.234.32",
    date: "16 Oct, 2022",
    location: "Phoenix, United States",
  },
  {
    product: "iPhone 12 Pro",
    ip_address: "192.44.326.42",
    date: "22 May, 2022",
    location: "Connecticut, United States",
  },
  {
    product: "Apple iPad Pro",
    ip_address: "190.44.182.33",
    date: "19 Nov, 2023",
    location: "Los Angeles, United States",
  },
  {
    product: "Galaxy S21 Ultra 5G",
    ip_address: "194.44.235.87",
    date: "30 Aug, 2022",
    location: "Connecticut, United States",
  },
];

export const security = [
  {
    title: "Two-factor Authentication",
    description:
      "Two-factor authentication is an enhanced security. Once enabled, you'll be required to give two types of identification when you log into Google Authentication and SMS are Supported.",
    btnText: "Enable Two-factor Authentication",
  },
  {
    title: "Secondary Verification",
    description:
      "The first factor is a password and the second commonly includes a text with a code sent to your smartphone, or biometrics using your fingerprint, face, or retina.",
    btnText: "Set up secondary method",
  },
  {
    title: "Backup Codes",
    description:
      "A backup code is automatically generated for you when you turn on two-factor authentication through your iOS or Android Twitter app. You can also generate a backup code on twitter.com.",
    btnText: "Generate backup codes",
  },
];

export const notifications = [
  {
    title: "Direct messages",
    value: "Messages from people you follow",
    isActive: true,
  },
  {
    title: "Show desktop notifications",
    value: `Choose the option you want as your default setting. Block a site: Next to "Not allowed to send notifications," click Add.`,
    isActive: true,
  },
  {
    title: "Show email notifications",
    value:
      "Under Settings, choose Notifications. Under Select an account, choose the account to enable notifications for.",
    isActive: true,
  },
  {
    title: "Show chat notifications",
    value:
      "To prevent duplicate mobile notifications from the Gmail and Chat apps, in settings, turn off Chat notifications.",
    isActive: false,
  },
  {
    title: "Show purchase notifications",
    value:
      "Get real-time purchase alerts to protect yourself from fraudulent charges.",
    isActive: false,
  },
];
