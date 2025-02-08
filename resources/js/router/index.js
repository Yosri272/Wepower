// resources/js/router/index.js
import { createRouter, createWebHistory } from "vue-router";

// import layouts
import defaultLayout from "../layouts/default.vue";
import authLayout from "../layouts/auth.vue";

// import pages
import Home from "../pages/Home.vue";
import Shop from "../pages/Shop.vue";
import ShopDetail from "../pages/ShopDetails.vue";
import ShopCategoryProduct from "../pages/ShopCategoryProduct.vue";
import ProductDetails from "../pages/ProductDetails.vue";
import CategoryProduct from "../pages/CategoryProduct.vue";
import Checkout from "../pages/Checkout.vue";

import Dashboard from "../pages/Dashboard.vue";
import OrderHistory from "../pages/OrderHistory.vue";
import OrderDetails from "../pages/OrderDetails.vue";
import Wishlist from "../pages/Wishlist.vue";
import MyProfile from "../pages/MyProfile.vue";
import ManageAddress from "../pages/ManageAddress.vue";
import Support from "../pages/Support.vue";
import TermsAndConditions from "../pages/TermsAndConditions.vue";
import PrivacyPolicy from "../pages/PrivacyPolicy.vue";
import AddNewAddress from "../pages/AddNewAddress.vue";
import EditAddress from "../pages/EditAddress.vue";
import AboutUs from "../pages/AboutUs.vue";
import ChangePassword from "../pages/ChangePassword.vue";
import BuyNow from "../pages/BuyNow.vue";
import MostPopular from "../pages/MostPopular.vue";
import ContactUs from "../pages/ContactUs.vue";
import BestDeal from "../pages/BestDeal.vue";
import Products from "../pages/Products.vue";
import Category from "../pages/Category.vue";
import SupportTicket from "../pages/SupportTicket.vue"
import SupportTicketDetails from "../pages/SupportTicketDetails.vue";

// all pages router will be here
const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/shops",
        name: "shop",
        component: Shop,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/products",
        name: "products",
        component: Products,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/categories",
        name: "categories",
        component: Category,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/most-popular",
        name: "most-popular",
        component: MostPopular,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/best-deal",
        name: "best-deal",
        component: BestDeal,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/shops/:id",
        name: "shop-detail",
        component: ShopDetail,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/shops/:id/categories/:slug",
        name: "shop-category-product",
        component: ShopCategoryProduct,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/products/:id/details",
        name: "productDetails",
        component: ProductDetails,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/categories/:slug",
        name: "category-product",
        component: CategoryProduct,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/checkout",
        name: "checkout",
        component: Checkout,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/buynow",
        name: "buynow",
        component: BuyNow,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard,
        meta: {
            layout: authLayout,
        },
    },
    {
        path: "/order-history",
        name: "order-history",
        component: OrderHistory,
        meta: {
            layout: authLayout,
        },
    },
    {
        path: "/order-history/:id",
        name: "order-details",
        component: OrderDetails,
        meta: {
            layout: authLayout,
        },
    },
    {
        path: "/wishlist",
        name: "wishlist",
        component: Wishlist,
        meta: {
            layout: authLayout,
        },
    },
    {
        path: "/profile",
        name: "profile",
        component: MyProfile,
        meta: {
            layout: authLayout,
        },
    },
    {
        path: "/manage-address",
        name: "manage-address",
        component: ManageAddress,
        meta: {
            layout: authLayout,
        },
    },
    {
        path: "/manage-address/new",
        name: "add-new-address",
        component: AddNewAddress,
        meta: {
            layout: authLayout,
        },
    },
    {
        path: "/manage-address/:id/edit",
        name: "edit-address",
        component: EditAddress,
        meta: {
            layout: authLayout,
        },
    },
    {
        path: "/change-password",
        name: "change-password",
        component: ChangePassword,
        meta: {
            layout: authLayout,
        },
    },
    {
        path: "/support-tickets",
        name: "support-ticket",
        component: SupportTicket,
        meta: {
            layout: authLayout,
        },
    },
    {
        path: "/support-ticket/:ticketNumber",
        name: "support-ticket-details",
        component: SupportTicketDetails,
        meta: {
            layout: authLayout,
        },
    },

    {
        path: "/support",
        name: "support",
        component: Support,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/terms-and-conditions",
        name: "terms-and-conditions",
        component: TermsAndConditions,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/privacy-policy",
        name: "privacy-policy",
        component: PrivacyPolicy,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/about-us",
        name: "about-us",
        component: AboutUs,
        meta: {
            layout: defaultLayout,
        },
    },
    {
        path: "/contact-us",
        name: "contact-us",
        component: ContactUs,
        meta: {
            layout: defaultLayout,
        },
    },
];

// create router
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
