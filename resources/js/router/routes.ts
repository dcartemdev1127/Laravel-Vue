import { DefaultLayout, AuthLayout } from "@/layouts/index";

import ECommerce from "@/views/dashboard/ECommerce.vue";
import Chats from "@/views/chats/index.vue";
import Email from "@/views/email/index.vue";
import FileManager from "@/views/fileManager/index.vue";
import Widgets from "@/views/widgets/index.vue";

const dashboardPrefix = "/dashboard";
const eCommercePrefix = "/ecommerce";
const learningPrefix = "/learning";
const studentPrefix = "/student";
const instructorPrefix = "/instructors";
const invoicePrefix = "/invoices";
const realEstatePrefix = "/real-estate";
const agentPrefix = "/real-estate-agent";
const agenciesPrefix = "/real-estate-agencies";
const authPrefix = "/auth";
const pagesPrefix = "/pages";
const uiPrefix = "/ui";
const advanceUIPrefix = "/advance-ui";
const customUIPrefix = "/ui";
const formsPrefix = "/forms";
const tablePrefix = "/tables";
const chartPrefix = "/charts";
const iconPrefix = "/icons";
const mapPrefix = "/maps";

const accountRoutes = [
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

const dashboardRoutes = [
  {
    path: "/",
    name: "ECommerce",
    component: ECommerce,
    meta: { title: "Dashboard", authRequired: true, layout: DefaultLayout },
  },
  {
    path: `${dashboardPrefix}/analytics`,
    name: "Analytics",
    component: () => import("@/views/dashboard/Analytics.vue"),
    meta: { title: "Analytics", authRequired: true },
  },
  {
    path: `${dashboardPrefix}/crm`,
    name: "CRM",
    component: () => import("@/views/dashboard/CRM.vue"),
    meta: { title: "CRM", authRequired: true },
  },
  {
    path: `${dashboardPrefix}/learning`,
    name: "Learning",
    component: () => import("@/views/dashboard/Learning.vue"),
    meta: { title: "Learning", authRequired: true },
  },
  {
    path: `${dashboardPrefix}/real-estate`,
    name: "RealEstate",
    component: () => import("@/views/dashboard/RealEstate.vue"),
    meta: { title: "Real Estate", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});
const eCommerceRoutes = [
  {
    path: `${eCommercePrefix}/products`,
    name: "Products",
    component: () => import("@/views/eCommerce/Products.vue"),
    meta: { title: "Products", authRequired: true },
  },
  {
    path: `${eCommercePrefix}/products-grid`,
    name: "ProductsGrid",
    component: () => import("@/views/eCommerce/ProductGrid.vue"),
    meta: { title: "Products Grid", authRequired: true },
  },
  {
    path: `${eCommercePrefix}/product-details`,
    name: "ProductsOverview",
    component: () => import("@/views/eCommerce/ProductDetails.vue"),
    meta: { title: "Products Overview", authRequired: true },
  },
  {
    path: `${eCommercePrefix}/add-product`,
    name: "AddProduct",
    component: () => import("@/views/eCommerce/AddProduct.vue"),
    meta: { title: "Add Product", authRequired: true },
  },
  {
    path: `${eCommercePrefix}/orders`,
    name: "Orders",
    component: () => import("@/views/eCommerce/Orders.vue"),
    meta: { title: "Orders", authRequired: true },
  },
  {
    path: `${eCommercePrefix}/order-overview`,
    name: "OrderOverview",
    component: () => import("@/views/eCommerce/OrderOverview.vue"),
    meta: { title: "Orders Overview", authRequired: true },
  },
  {
    path: `${eCommercePrefix}/customers`,
    name: "Customers",
    component: () => import("@/views/eCommerce/Customers.vue"),
    meta: { title: "Customers", authRequired: true },
  },
  {
    path: `${eCommercePrefix}/cart`,
    name: "ShopCart",
    component: () => import("@/views/eCommerce/ShopCart.vue"),
    meta: { title: "Shop Cart", authRequired: true },
  },
  {
    path: `${eCommercePrefix}/checkout`,
    name: "Checkout",
    component: () => import("@/views/eCommerce/Checkout.vue"),
    meta: { title: "Checkout", authRequired: true },
  },
  {
    path: `${eCommercePrefix}/sellers`,
    name: "Sellers",
    component: () => import("@/views/eCommerce/Sellers.vue"),
    meta: { title: "Sellers", authRequired: true },
  },
  {
    path: `${eCommercePrefix}/seller-overview`,
    name: "SellersOverview",
    component: () => import("@/views/eCommerce/SellersOverview.vue"),
    meta: { title: "Sellers Overview", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});
const learningRoutes = [
  {
    path: `${learningPrefix}/list`,
    name: "LearningListView",
    component: () => import("@/views/learning/courses/ListView.vue"),
    meta: { title: "List View", authRequired: true },
  },
  {
    path: `${learningPrefix}/grid`,
    name: "LearningGridView",
    component: () => import("@/views/learning/courses/GridView.vue"),
    meta: { title: "Grid View", authRequired: true },
  },
  {
    path: `${learningPrefix}/category`,
    name: "Category",
    component: () => import("@/views/learning/courses/Category.vue"),
    meta: { title: "Category", authRequired: true },
  },
  {
    path: `${learningPrefix}/overview`,
    name: "LearningOverview",
    component: () => import("@/views/learning/courses/Overview.vue"),
    meta: { title: "Overview", authRequired: true },
  },
  {
    path: `${learningPrefix}/create`,
    name: "CreateCourse",
    component: () => import("@/views/learning/courses/CreateCourse.vue"),
    meta: { title: "Create Course", authRequired: true },
  },
  {
    path: `${studentPrefix}/subscriptions`,
    name: "MySubscriptions",
    component: () => import("@/views/learning/student/Subscriptions.vue"),
    meta: { title: "My Subscriptions", authRequired: true },
  },
  {
    path: `${studentPrefix}/courses`,
    name: "MyCourses",
    component: () => import("@/views/learning/student/Courses.vue"),
    meta: { title: "My Courses", authRequired: true },
  },
  {
    path: `${instructorPrefix}/list`,
    name: "InstructorListView",
    component: () => import("@/views/learning/instructors/ListView.vue"),
    meta: { title: "List View", authRequired: true },
  },
  {
    path: `${instructorPrefix}/grid`,
    name: "InstructorGridView",
    component: () => import("@/views/learning/instructors/GridView.vue"),
    meta: { title: "Grid View", authRequired: true },
  },
  {
    path: `${instructorPrefix}/overview`,
    name: "InstructorOverview",
    component: () => import("@/views/learning/instructors/Overview.vue"),
    meta: { title: "Overview", authRequired: true },
  },
  {
    path: `${instructorPrefix}/create`,
    name: "InstructorCreate",
    component: () =>
      import("@/views/learning/instructors/CreateInstructor.vue"),
    meta: { title: "Create Instructor", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});
const invoicesRoutes = [
  {
    path: `${invoicePrefix}/list`,
    name: "InvoiceList",
    component: () => import("@/views/invoices/List.vue"),
    meta: { title: "Invoice List", authRequired: true },
  },
  {
    path: `${invoicePrefix}/overview`,
    name: "InvoiceOverview",
    component: () => import("@/views/invoices/Overview.vue"),
    meta: { title: "Invoice Overview", authRequired: true },
  },
  {
    path: `${invoicePrefix}/create`,
    name: "CreateInvoice",
    component: () => import("@/views/invoices/CreateInvoice.vue"),
    meta: { title: "Create Invoice", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});
const ticketsRoutes = [
  {
    path: `/tickets/list`,
    name: "TicketsList",
    component: () => import("@/views/tickets/List.vue"),
    meta: { title: "Tickets", authRequired: true },
  },
  {
    path: `/tickets/overview`,
    name: "TicketsOverview",
    component: () => import("@/views/tickets/Overview.vue"),
    meta: { title: "Tickets Overview", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});
const realEstateRoutes = [
  {
    path: `${realEstatePrefix}/grid`,
    name: "RealEstateGrid",
    component: () => import("@/views/realEstate/GridView.vue"),
    meta: { title: "Listing Grid", authRequired: true },
  },
  {
    path: `${realEstatePrefix}/list`,
    name: "RealEstateList",
    component: () => import("@/views/realEstate/ListView.vue"),
    meta: { title: "Listing List", authRequired: true },
  },
  {
    path: `${realEstatePrefix}/map`,
    name: "RealEstateMap",
    component: () => import("@/views/realEstate/MapView.vue"),
    meta: { title: "Listing Map", authRequired: true },
  },
  {
    path: `${realEstatePrefix}/property-overview`,
    name: "RealEstatePropertyOverview",
    component: () => import("@/views/realEstate/Overview.vue"),
    meta: { title: "Property Overview", authRequired: true },
  },
  {
    path: `${agentPrefix}/list`,
    name: "RealEstateAgentListView",
    component: () => import("@/views/realEstate/agent/ListView.vue"),
    meta: { title: "Agent List", authRequired: true },
  },
  {
    path: `${agentPrefix}/grid`,
    name: "RealEstateAgentGridView",
    component: () => import("@/views/realEstate/agent/GridView.vue"),
    meta: { title: "Agent Grid", authRequired: true },
  },
  {
    path: `${agentPrefix}/overview`,
    name: "RealEstateAgentOverview",
    component: () => import("@/views/realEstate/agent/Overview.vue"),
    meta: { title: "Overview", authRequired: true },
  },
  {
    path: `${agenciesPrefix}/list`,
    name: "RealEstateAgenciesList",
    component: () => import("@/views/realEstate/agencies/ListView.vue"),
    meta: { title: "List View", authRequired: true },
  },
  {
    path: `${agenciesPrefix}/overview`,
    name: "RealEstateAgenciesOverview",
    component: () => import("@/views/realEstate/agencies/Overview.vue"),
    meta: { title: "Overview", authRequired: true },
  },
  {
    path: `${realEstatePrefix}/add-properties`,
    name: "RealEstateAddProperty",
    component: () => import("@/views/realEstate/AddProperty.vue"),
    meta: { title: "Add Properties", authRequired: true },
  },
  {
    path: `${realEstatePrefix}/earnings`,
    name: "RealEstateEarnings",
    component: () => import("@/views/realEstate/Earnings.vue"),
    meta: { title: "Earnings", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});
const authRoutes = [
  {
    path: `${authPrefix}/signin`,
    name: "SignIn",
    component: () => import("@/views/authentication/SignIn.vue"),
    meta: { title: "Sign In", authRequired: false },
  },
  {
    path: `${authPrefix}/signup`,
    name: "SignUp",
    component: () => import("@/views/authentication/SignUp.vue"),
    meta: { title: "Sign Up", authRequired: false },
  },
  {
    path: `${authPrefix}/pass-reset`,
    name: "ResetPassword",
    component: () => import("@/views/authentication/ResetPassword.vue"),
    meta: { title: "Reset Password", authRequired: false },
  },
  {
    path: `${authPrefix}/pass-change`,
    name: "ChangePassword",
    component: () => import("@/views/authentication/CreatePassword.vue"),
    meta: { title: "Create New Password", authRequired: false },
  },
  {
    path: `${authPrefix}/lockscreen`,
    name: "LockScreen",
    component: () => import("@/views/authentication/LockScreen.vue"),
    meta: { title: "Lock Screen", authRequired: false },
  },
  {
    path: `${authPrefix}/logout`,
    name: "LogOut",
    component: () => import("@/views/authentication/Logout.vue"),
    meta: { title: "Log Out", authRequired: false },
  },
  {
    path: `${authPrefix}/success-msg`,
    name: "SuccessMessage",
    component: () => import("@/views/authentication/SuccessMsg.vue"),
    meta: { title: "Success Message", authRequired: false },
  },
  {
    path: `${authPrefix}/twostep`,
    name: "TwoStepVerification",
    component: () => import("@/views/authentication/TwoStepVerification.vue"),
    meta: { title: "Two Step Verification", authRequired: false },
  },
  {
    path: `${authPrefix}/404`,
    name: "Error404",
    component: () => import("@/views/authentication/error/404.vue"),
    meta: { title: "404 Error", authRequired: false },
  },
  {
    path: `${authPrefix}/500`,
    name: "Error500",
    component: () => import("@/views/authentication/error/500.vue"),
    meta: { title: "500 Error", authRequired: false },
  },
  {
    path: `${authPrefix}/503`,
    name: "Error503",
    component: () => import("@/views/authentication/error/503.vue"),
    meta: { title: "503 Error", authRequired: false },
  },
  {
    path: `${authPrefix}/offline`,
    name: "Offline",
    component: () => import("@/views/authentication/error/Offline.vue"),
    meta: { title: "Offline Page", authRequired: false },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: AuthLayout },
  };
});
const pagesRoutes = [
  {
    path: `${pagesPrefix}/starter`,
    name: "Starter",
    component: () => import("@/views/pages/StarterKit.vue"),
    meta: { title: "Starter", authRequired: false, layout: DefaultLayout },
  },
  {
    path: `${pagesPrefix}/profile`,
    name: "PagesProfile",
    component: () => import("@/views/pages/Profile.vue"),
    meta: { title: "Profile", authRequired: false, layout: DefaultLayout },
  },
  {
    path: `${pagesPrefix}/profile-settings`,
    name: "PagesProfileSettings",
    component: () => import("@/views/pages/ProfileSettings.vue"),
    meta: {
      title: "Profile Settings",
      authRequired: false,
      layout: DefaultLayout,
    },
  },
  {
    path: `${pagesPrefix}/contacts`,
    name: "PagesContacts",
    component: () => import("@/views/pages/Contacts.vue"),
    meta: { title: "Contact", authRequired: false, layout: DefaultLayout },
  },
  {
    path: `${pagesPrefix}/timeline`,
    name: "PagesTimeline",
    component: () => import("@/views/pages/Timeline.vue"),
    meta: { title: "Timeline", authRequired: false, layout: DefaultLayout },
  },
  {
    path: `${pagesPrefix}/faqs`,
    name: "PagesFAQs",
    component: () => import("@/views/pages/FAQs.vue"),
    meta: { title: "FAQs", authRequired: false, layout: DefaultLayout },
  },
  {
    path: `${pagesPrefix}/pricing`,
    name: "PagesPricing",
    component: () => import("@/views/pages/Pricing.vue"),
    meta: { title: "Pricing", authRequired: false, layout: DefaultLayout },
  },
  {
    path: `${pagesPrefix}/maintenance`,
    name: "PagesMaintenance",
    component: () => import("@/views/pages/Maintenance.vue"),
    meta: { title: "Maintenance", authRequired: false, layout: AuthLayout },
  },
  {
    path: `${pagesPrefix}/coming-soon`,
    name: "PagesComingSoon",
    component: () => import("@/views/pages/ComingSoon.vue"),
    meta: { title: "Coming Soon", authRequired: false, layout: AuthLayout },
  },
  {
    path: `${pagesPrefix}/privacy-policy`,
    name: "PagesPrivacyPolicy",
    component: () => import("@/views/pages/PrivacyPolicy.vue"),
    meta: {
      title: "Privacy Policy",
      authRequired: false,
      layout: DefaultLayout,
    },
  },
  {
    path: `${pagesPrefix}/term-conditions`,
    name: "PagesTermConditions",
    component: () => import("@/views/pages/TermAndConditions.vue"),
    meta: {
      title: "Term & Conditions",
      authRequired: false,
      layout: DefaultLayout,
    },
  },
];
const vuetifyUIRoutes = [
  {
    path: `${uiPrefix}/alerts`,
    name: "UIAlerts",
    component: () => import("@/views/ui/Alerts.vue"),
    meta: { title: "Alerts", authRequired: true },
  },
  {
    path: `${uiPrefix}/badges`,
    name: "UIBadges",
    component: () => import("@/views/ui/Badges.vue"),
    meta: { title: "Badges", authRequired: true },
  },
  {
    path: `${uiPrefix}/buttons`,
    name: "UIButtons",
    component: () => import("@/views/ui/Buttons.vue"),
    meta: { title: "Buttons", authRequired: true },
  },
  {
    path: `${uiPrefix}/colors`,
    name: "UIColors",
    component: () => import("@/views/ui/Colors.vue"),
    meta: { title: "Colors", authRequired: true },
  },
  {
    path: `${uiPrefix}/cards`,
    name: "UICards",
    component: () => import("@/views/ui/Cards.vue"),
    meta: { title: "Cards", authRequired: true },
  },
  {
    path: `${uiPrefix}/carousel`,
    name: "UICarousel",
    component: () => import("@/views/ui/Carousel.vue"),
    meta: { title: "Carousel", authRequired: true },
  },
  {
    path: `${uiPrefix}/menu`,
    name: "UIMenu",
    component: () => import("@/views/ui/Menu.vue"),
    meta: { title: "Menu", authRequired: true },
  },
  {
    path: `${uiPrefix}/grid`,
    name: "UIGrid",
    component: () => import("@/views/ui/Grid.vue"),
    meta: { title: "Grid", authRequired: true },
  },
  {
    path: `${uiPrefix}/images`,
    name: "UIImages",
    component: () => import("@/views/ui/Images.vue"),
    meta: { title: "Images", authRequired: true },
  },
  {
    path: `${uiPrefix}/tabs`,
    name: "UITabs",
    component: () => import("@/views/ui/Tabs.vue"),
    meta: { title: "Tabs", authRequired: true },
  },
  {
    path: `${uiPrefix}/expansion-panels`,
    name: "UIExpansionPanels",
    component: () => import("@/views/ui/ExpansionPanels.vue"),
    meta: { title: "Expansion Panels", authRequired: true },
  },
  {
    path: `${uiPrefix}/dialogs`,
    name: "UIDialog",
    component: () => import("@/views/ui/Dialogs.vue"),
    meta: { title: "Dialogs", authRequired: true },
  },
  {
    path: `${uiPrefix}/navigation-drawer`,
    name: "UINavigationDrawer",
    component: () => import("@/views/ui/NavigationDrawer.vue"),
    meta: { title: "Navigation Drawer", authRequired: true },
  },
  {
    path: `${uiPrefix}/progress`,
    name: "UIProgress",
    component: () => import("@/views/ui/Progress.vue"),
    meta: { title: "Progress", authRequired: true },
  },
  {
    path: `${uiPrefix}/media`,
    name: "UIMedia",
    component: () => import("@/views/ui/Media.vue"),
    meta: { title: "Media", authRequired: true },
  },
  {
    path: `${uiPrefix}/embed-video`,
    name: "UIEmbedVideo",
    component: () => import("@/views/ui/EmbedVideo.vue"),
    meta: { title: "Embed Video", authRequired: true },
  },
  {
    path: `${uiPrefix}/typography`,
    name: "UITypography",
    component: () => import("@/views/ui/Typography.vue"),
    meta: { title: "Typography", authRequired: true },
  },
  {
    path: `${uiPrefix}/lists`,
    name: "UILists",
    component: () => import("@/views/ui/Lists.vue"),
    meta: { title: "Lists", authRequired: true },
  },
  {
    path: `${uiPrefix}/general`,
    name: "UIGeneral",
    component: () => import("@/views/ui/General.vue"),
    meta: { title: "General", authRequired: true },
  },
  {
    path: `${uiPrefix}/utilities`,
    name: "UIUtilities",
    component: () => import("@/views/ui/Utilities.vue"),
    meta: { title: "Utilities", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});

const advanceUIRoutes = [
  {
    path: `${advanceUIPrefix}/sweetalerts`,
    name: "AdvanceSweetAlerts",
    component: () => import("@/views/advanceUI/SweetAlerts.vue"),
    meta: { title: "Sweet Alerts", authRequired: true },
  },
  {
    path: `${advanceUIPrefix}/scrollbar`,
    name: "AdvanceScrollbar",
    component: () => import("@/views/advanceUI/Scrollbar.vue"),
    meta: { title: "Scrollbar", authRequired: true },
  },
  {
    path: `${advanceUIPrefix}/swiper`,
    name: "AdvanceSwiperSlider",
    component: () => import("@/views/advanceUI/Swiper.vue"),
    meta: { title: "Swiper Slider", authRequired: true },
  },
  {
    path: `${advanceUIPrefix}/ratings`,
    name: "AdvanceRatings",
    component: () => import("@/views/advanceUI/Ratings.vue"),
    meta: { title: "Ratings", authRequired: true },
  },
  {
    path: `${advanceUIPrefix}/highlight`,
    name: "AdvanceHighlight",
    component: () => import("@/views/advanceUI/Highlight.vue"),
    meta: { title: "Highlight", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});

const customUIRoutes = [
  {
    path: `${customUIPrefix}/profile`,
    name: "CustomUIProfile",
    component: () => import("@/views/customUI/Profile.vue"),
    meta: { title: "Profile", authRequired: true },
  },
  {
    path: `${customUIPrefix}/counter`,
    name: "CustomUICounter",
    component: () => import("@/views/customUI/Counter.vue"),
    meta: { title: "Counter", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});

const formsRoutes = [
  {
    path: `${formsPrefix}/elements`,
    name: "FormBasicElements",
    component: () => import("@/views/forms/BasicElements.vue"),
    meta: { title: "Basic Elements", authRequired: true },
  },
  {
    path: `${formsPrefix}/select`,
    name: "FormSelect",
    component: () => import("@/views/forms/FormSelect.vue"),
    meta: { title: "Form Select", authRequired: true },
  },
  {
    path: `${formsPrefix}/checkboxs-radios`,
    name: "FormCheckBoxRadio",
    component: () => import("@/views/forms/CheckboxRadio.vue"),
    meta: { title: "Checkboxes & Radios", authRequired: true },
  },
  {
    path: `${formsPrefix}/pickers`,
    name: "FormPickers",
    component: () => import("@/views/forms/Pickers.vue"),
    meta: { title: "Pickers", authRequired: true },
  },
  {
    path: `${formsPrefix}/masks`,
    name: "FormInputMasks",
    component: () => import("@/views/forms/InputMasks.vue"),
    meta: { title: "Input Masks", authRequired: true },
  },
  {
    path: `${formsPrefix}/advanced`,
    name: "AdvanceForm",
    component: () => import("@/views/forms/AdvanceForm.vue"),
    meta: { title: "Form Advanced", authRequired: true },
  },
  {
    path: `${formsPrefix}/range-sliders`,
    name: "RangeSlider",
    component: () => import("@/views/forms/RangeSlider.vue"),
    meta: { title: "Range Slider", authRequired: true },
  },
  {
    path: `${formsPrefix}/validation`,
    name: "FormValidations",
    component: () => import("@/views/forms/Validations.vue"),
    meta: { title: "Form Validations", authRequired: true },
  },
  {
    path: `${formsPrefix}/wizard`,
    name: "FormWizard",
    component: () => import("@/views/forms/Wizard.vue"),
    meta: { title: "Wizard", authRequired: true },
  },
  {
    path: `${formsPrefix}/editors`,
    name: "FormEditors",
    component: () => import("@/views/forms/Editors.vue"),
    meta: { title: "Editors", authRequired: true },
  },
  {
    path: `${formsPrefix}/file-uploads`,
    name: "FormFileUpload",
    component: () => import("@/views/forms/FileUpload.vue"),
    meta: { title: "File Upload", authRequired: true },
  },
  {
    path: `${formsPrefix}/layouts`,
    name: "FormLayout",
    component: () => import("@/views/forms/Layouts.vue"),
    meta: { title: "Form Layout", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});

const tableRoutes = [
  {
    path: `${tablePrefix}/basic`,
    name: "BasicTables",
    component: () => import("@/views/tables/Basic.vue"),
    meta: { title: "Basic Tables", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});

const chartRoutes = [
  {
    path: `${chartPrefix}/apex-line`,
    name: "ApexLineChart",
    component: () => import("@/views/charts/apexCharts/Line.vue"),
    meta: { title: "Apex Line Chats", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-area`,
    name: "ApexAreaChart",
    component: () => import("@/views/charts/apexCharts/Area.vue"),
    meta: { title: "Apex Area Chats", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-column`,
    name: "ApexColumnChart",
    component: () => import("@/views/charts/apexCharts/Column.vue"),
    meta: { title: "Apex Column Chats", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-bar`,
    name: "ApexBarChart",
    component: () => import("@/views/charts/apexCharts/Bar.vue"),
    meta: { title: "Apex Bar Chats", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-mixed`,
    name: "ApexMixedChart",
    component: () => import("@/views/charts/apexCharts/Mixed.vue"),
    meta: { title: "Apex Mixed Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-range-area`,
    name: "ApexRangeAreaChart",
    component: () => import("@/views/charts/apexCharts/RangeArea.vue"),
    meta: { title: "Apex RangeArea Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-funnel`,
    name: "ApexFunnelChart",
    component: () => import("@/views/charts/apexCharts/Funnel.vue"),
    meta: { title: "Apex Funnel Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-candlestick`,
    name: "ApexCandlestickChart",
    component: () => import("@/views/charts/apexCharts/Candlestick.vue"),
    meta: { title: "Apex Candlestick Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-boxplot`,
    name: "ApexBoxplotChart",
    component: () => import("@/views/charts/apexCharts/Boxplot.vue"),
    meta: { title: "Apex Boxplot Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-bubble`,
    name: "ApexBubbleChart",
    component: () => import("@/views/charts/apexCharts/Bubble.vue"),
    meta: { title: "Apex Bubble Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-scatter`,
    name: "ApexScatterChart",
    component: () => import("@/views/charts/apexCharts/Scatter.vue"),
    meta: { title: "Apex Scatter Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-heatmap`,
    name: "ApexHeatmapChart",
    component: () => import("@/views/charts/apexCharts/Heatmap.vue"),
    meta: { title: "Apex Heatmap Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-treemap`,
    name: "ApexTreemapChart",
    component: () => import("@/views/charts/apexCharts/Treemap.vue"),
    meta: { title: "Apex Treemap Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-pie`,
    name: "ApexPieChart",
    component: () => import("@/views/charts/apexCharts/Pie.vue"),
    meta: { title: "Apex Pie Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-radialbar`,
    name: "ApexRadialbarChart",
    component: () => import("@/views/charts/apexCharts/Radialbar.vue"),
    meta: { title: "Apex Radialbar Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-radar`,
    name: "ApexRadarChart",
    component: () => import("@/views/charts/apexCharts/Radar.vue"),
    meta: { title: "Apex Radar Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/apex-polar`,
    name: "ApexPolarChart",
    component: () => import("@/views/charts/apexCharts/Polar.vue"),
    meta: { title: "Apex Polar Charts", authRequired: true },
  },
  {
    path: `${chartPrefix}/chartjs`,
    name: "Chartjs",
    component: () => import("@/views/charts/Chartjs.vue"),
    meta: { title: "Chartjs", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});

const iconRoutes = [
  {
    path: `${iconPrefix}/remix`,
    name: "RemixIcon",
    component: () => import("@/views/icons/Remix.vue"),
    meta: { title: "Remix Icon", authRequired: true },
  },
  {
    path: `${iconPrefix}/boxicons`,
    name: "Boxicons",
    component: () => import("@/views/icons/Boxicons.vue"),
    meta: { title: "Boxicons Icon", authRequired: true },
  },
  {
    path: `${iconPrefix}/materialdesign`,
    name: "MaterialDesign",
    component: () => import("@/views/icons/MaterialDesign.vue"),
    meta: { title: "MaterialDesign Icon", authRequired: true },
  },
  {
    path: `${iconPrefix}/phosphor`,
    name: "Phosphor",
    component: () => import("@/views/icons/Phosphor.vue"),
    meta: { title: "Phosphor Icon", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});

const mapRoutes = [
  {
    path: `${mapPrefix}/google`,
    name: "Google",
    component: () => import("@/views/maps/Google.vue"),
    meta: { title: "Google", authRequired: true },
  },
  {
    path: `${mapPrefix}/amcharts`,
    name: "AmChart",
    component: () => import("@/views/maps/AmChart.vue"),
    meta: { title: "AmChart", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});

const myRoute = [
  {
    path: `/department`,
    name: "department",
    component: () => import("@/views/forms/Layouts.vue"),
    meta: { title: "Department", authRequired: true },
  },
].map((data) => {
  return {
    ...data,
    meta: { ...data.meta, layout: DefaultLayout },
  };
});

export const routes = [
  ...accountRoutes,
  ...dashboardRoutes,
  {
    path: "/chat",
    name: "Chat",
    component: Chats,
    meta: { title: "Chat", authRequired: true, layout: DefaultLayout },
  },
  {
    path: "/email",
    name: "Email",
    component: Email,
    meta: { title: "Email", authRequired: true, layout: DefaultLayout },
  },
  // ...eCommerceRoutes,
  {
    path: "/file-manager",
    name: "FileManager",
    component: FileManager,
    meta: { title: "File Manager", authRequired: true, layout: DefaultLayout },
  },
  // ...learningRoutes,
  // ...invoicesRoutes,
  // ...ticketsRoutes,
  // ...realEstateRoutes,
  ...authRoutes,
  // ...pagesRoutes,
  // ...vuetifyUIRoutes,
  // ...advanceUIRoutes,
  // ...customUIRoutes,
  // {
  //   path: "/widgets",
  //   name: "Widgets",
  //   component: Widgets,
  //   meta: { title: "Widgets", authRequired: true, layout: DefaultLayout },
  // },
  ...formsRoutes,
  ...tableRoutes,
  // ...chartRoutes,
  // ...iconRoutes,
  ...mapRoutes,
  ...myRoute,
  {
    path: "/logout",
    name: "Logout",
    component: () => import("@/views/account/Logout.vue"),
    meta: { title: "Logout", authRequired: false },
  },
];
