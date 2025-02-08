import { defineStore } from "pinia";

export const useMaster = defineStore("masterStore", {
    state: () => ({
        logo: null,
        currency: "$",
        position: "prefix",
        showDownloadApp: false,
        playStoreLink: null,
        appStoreLink: null,
        multiVendor: true,
        paymentGateways: [],
        mobile: null,
        email: null,
        showFooter: true,
        address: null,
        footerText: null,
        footerDescription: null,
        footerLogo: null,
        footerQr: null,
        socialLinks: [],
        basketCanvas: false,
        search: null,
        categories: [],
        themeColors: {
            primary: null,
            primary50: null,
            primary100: null,
            primary200: null,
            primary300: null,
            primary400: null,
            primary500: null,
            primary600: null,
            primary700: null,
            primary800: null,
            primary900: null,
            primary950: null,
        },
        pusher_app_key: null,
        pusher_app_cluster: null,
        app_environment: 'local',
    }),

    getters: {
        getPosition: (state) => {
            return state.position;
        },

        getPlayStoreLink: (state) => {
            return state.playStoreLink;
        },

        getAppStoreLink: (state) => {
            return state.appStoreLink;
        },
        getMultiVendor: (state) => {
            return state.multiVendor;
        },
    },

    actions: {
        fetchData() {
            axios.get("/master").then((response) => {
                this.currency = response.data.data.currency.symbol;
                this.position = response.data.data.currency.position;
                this.playStoreLink = response.data.data.google_playstore_link;
                this.appStoreLink = response.data.data.app_store_link;
                this.multiVendor = response.data.data.multi_vendor;
                this.mobile = response.data.data.mobile;
                this.email = response.data.data.email;
                this.showFooter = response.data.data.web_show_footer;
                this.address = response.data.data.address;
                this.paymentGateways = response.data.data.payment_gateways;
                this.footerText = response.data.data.web_footer_text;
                this.footerDescription = response.data.data.web_footer_description;
                this.footerLogo = response.data.data.web_footer_logo;
                this.footerQr = response.data.data.footer_qr;
                this.logo = response.data.data.web_logo;
                this.socialLinks = response.data.data.social_links;
                this.themeColors = response.data.data.theme_colors;
                this.pusher_app_key = response.data.data.pusher_app_key;
                this.pusher_app_cluster = response.data.data.pusher_app_cluster;
                this.app_environment = response.data.data.app_environment;
                this.showDownloadApp = response.data.data.show_download_app;
            });
        },
        showCurrency(amount) {
            if (this.position == "prefix") {
                return this.currency + amount;
            }
            return amount + this.currency;
        },
    },

    persist: true,
});
