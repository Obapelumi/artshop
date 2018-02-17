 /*Dependencies*/
import Vue from 'vue';
import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';
import axios from 'axios';
import VueAxios from 'vue-axios';
var numeral = require('numeral');

/*Artshop Plugins */
import auth from './art-plugins/auth/';
import theme from './art-plugins/theme/';
import shop from './art-plugins/shop/';
import vendor from './art-plugins/vendor/';
import admin from './art-plugins/admin/';

import App from './App.vue';

/*Use Plugins and Dependencies*/ 
Vue.use(VueRouter);
Vue.use(VeeValidate);
Vue.use(auth);
Vue.use(VueAxios, axios);
Vue.use(theme);
Vue.use(shop);
Vue.use(vendor);
Vue.use(admin);
Vue.use(require('vue-moment'));

/* Set Axios defaults */
axios.defaults.baseURL = theme.config.SITE_URL + '/api';
if (auth.checkAuth()) {
	axios.defaults.headers.common['Authorization'] = 'Bearer ' + auth.getToken().token;
}
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

/*Componens*/
Vue.component('art-header', require('./components/layouts/header.vue'));
Vue.component('art-navbar', require('./components/layouts/navbar.vue')); 
Vue.component('art-notification', require('./components/layouts/notification.vue'));
Vue.component('art-footer', require('./components/layouts/footer.vue')); 
Vue.component('art-newsletter', require('./components/includes/newsletter.vue'));
Vue.component('art-you-sure', require('./components/includes/art-you-sure.vue'));
Vue.component('art-validate', require('./components/includes/validator-errors.vue'));
Vue.component('art-image-upload', require('./components/includes/image-upload.vue'));
Vue.component('art-image-update', require('./components/includes/image-update.vue'));
Vue.component('art-page-header', require('./components/includes/page-header.vue'));
Vue.component('art-loading', require('./components/includes/loading.vue'));
Vue.component('art-index-blog', require('./components/index/index-blog.vue'));
Vue.component('art-customer-spotlight', require('./components/index/customer-spotlight.vue'));
Vue.component('art-dashboard-sidebar', require('./components/dashboard/dashboard-sidebar.vue'));
Vue.component('art-cart-table', require('./components/shop/cart-table.vue'));
Vue.component('art-cart-total', require('./components/shop/cart-total.vue'));
Vue.component('art-shop-product', require('./components/shop/shop-product.vue'));

Vue.filter("money", function (value) {
	return numeral(value/100).format("0,0.00"); 
});

Vue.filter("discount", function (product) {
	return shop.getProductDiscount(product); 
});

/*Routes*/ 
const routes = [
		{
			path: '/', 
			component: require('./components/index/index-body.vue'),
		},
		{
			path: '/shop', 
			component: require('./components/shop/shop-index.vue'),
		},
		{
			path: '/product/:slug', 
			component: require('./components/shop/product-page.vue'),
		},
		{
			path: '/not-found', 
			component: require('./components/pages/not-found.vue'),
		},
		{
			path: '/about', 
			component: require('./components/pages/about.vue'),
		},
		{
			path: '/faqs', 
			component: require('./components/pages/policies.vue'),
		},
		{
			path: '/merchant-agreement', 
			component: require('./components/pages/merchant-agreement.vue'),
		},
		{
			path: '/login', 
			component: require('./components/auth/login.vue'),
			meta: {
				guest: true
			},
		},
		{
			path: '/logout', 
			meta: {
				auth: true
			},
			beforeEnter: (to, from, next) => {
				var $this = {
					theme: theme,
					axios: axios,
					$router: router,
				}
				auth.signout($this);
			},
		},
		{
			path: '/register', 
			component: require('./components/auth/register.vue'),
			meta: {
				guest: true
			},
		},
		{
			path: '/verify-mail', 
			component: require('./components/auth/verify-mail.vue'),
			meta: {
				guest: true
			},
		},
		{
			path: '/password-reset', 
			component: require('./components/auth/password-reset.vue'),
			meta: {
				guest: true
			},
		},
		{
			path: '/register-vendor', 
			component: require('./components/auth/register-vendor.vue'),
			beforeEnter: (to, from, next) => {
				if (auth.checkVendor() === true) {
					next('/dashboard');
				}
				else if (auth.checkAuth() === true) {
					next();
				}
				else if (auth.checkAuth() === false) {
					theme.config.NEXT = '/register-vendor';
					next('/register');
				}
			},
		},
		{
			path: '/blog',
			component: require('./components/blog/blog-index.vue'),
		},
		{
			path: '/blog/:slug',
			component: require('./components/blog/blog-detail.vue'),
		},
		{
			path: '/vendors',
			component: require('./components/pages/vendors.vue')
		},
		{
			path: '/vendors/:slug',
			component: require('./components/pages/vendor-page.vue')
		},
		{
			path: '/dashboard',
			component: require('./components/dashboard/dashboard.vue'),
			meta: {
				auth: true
			},
			children: [
				{
					path: '',
					component: require('./components/dashboard/dashboard-index.vue'),
				},
				{
					path: '/dashboard/my-orders/:id',
					component: require('./components/dashboard/dashboard-cart.vue'),
				},
				{
					path: '/dashboard/edit-profile',
					component: require('./components/dashboard/vendor/edit-profile.vue'),
					meta: {
						vendor: true
					}
				},
				{
					path: '/dashboard/account-settings',
					component: require('./components/dashboard/account-settings.vue')
				},
				{
					path: '/dashboard/create-product',
					component: require('./components/dashboard/vendor/create-product.vue'),
					meta: {
						vendor: true
					}
				},
				{
					path: '/dashboard/my-products',
					component: require('./components/dashboard/vendor/my-products.vue'),
					meta: {
						vendor: true
					}
				},
				{
					path: '/dashboard/reviews',
					component: require('./components/dashboard/vendor/product-reviews.vue'),
					meta: {
						vendor: true
					}
				},
				{
					path: '/dashboard/orders',
					component: require('./components/dashboard/admin/orders.vue'),
					meta: {
						admin: true
					}
				},
				{
					path: '/dashboard/orders/:id',
					component: require('./components/dashboard/dashboard-cart.vue'),
				},
				{
					path: '/dashboard/edit-product/:slug',
					component: require('./components/dashboard/vendor/edit-product.vue'),
				},
				{
					path: '/dashboard/approve-products',
					component: require('./components/dashboard/admin/artshop-products.vue'),
					meta: {
						admin: true
					}
				},
				{
					path: '/dashboard/admin/statistics',
					component: require('./components/dashboard/admin/statistics.vue'),
					meta: {
						admin: true
					}
				},
				{
					path: '/dashboard/pending-product/:slug',
					component: require('./components/dashboard/admin/review-product.vue'),
					meta: {
						admin: true
					}
				},
				{
					path: '/dashboard/invite-admin',
					component: require('./components/dashboard/admin/invite-admin.vue'),
					meta: {
						admin: true
					}
				},
				{
					path: '/dashboard/newsletter',
					component: require('./components/dashboard/admin/newsletter.vue'),
					meta: {
						admin: true
					}
				},
				{
					path: '/dashboard/blog',
					component: require('./components/dashboard/admin/blog.vue'),
				},
				{
					path: '/dashboard/blog/edit/:slug',
					component: require('./components/dashboard/admin/edit-post.vue'),
				},
				{
					path: '/dashboard/settings',
					component: require('./components/dashboard/admin/settings.vue'),
					meta: {
						admin: true
					}
				},
				{
					path: '/dashboard/admin/all-reviews',
					component: require('./components/dashboard/admin/all-reviews.vue'),
					meta: {
						admin: true
					}
				},				
			],
		},
		{
			path: '/cart', 
			component: require('./components/shop/cart.vue')
		},
		{
			path: '/wish-list',
			component: require('./components/shop/wish-list.vue'),
		},
		{
			path: '/checkout', 
			component: require('./components/auth/register-customer.vue'),
			beforeEnter: (to, from, next) => {
				if (auth.checkCustomer() === true && shop.checkCart() === true) {
					next('/pay');
				}
				else if (shop.checkCart() === true) {
					if (auth.checkAuth()) {
						next();	
					}
					else {
						theme.config.NEXT = '/checkout';
						next('/register');
						theme.smoke('info', 'Please Sign Up to Continue', 3000);
					}
				}
				else {
					theme.smoke('info', 'No items in Your Shopping Cart', 3000);
					next(from);
				}
			},
		},
		{
			path: '/pay', 
			component: require('./components/shop/checkout-pay.vue'),
			meta: {
				auth: true
			},
			beforeEnter: (to, from, next) => {
				if (auth.checkCustomer() === true && shop.checkCart() === true) {
					next();
				}
				else if (shop.checkCart() === true) {
					next('/checkout');
				}
				else {
					theme.smoke('info', 'No items in Your Shopping Cart', 3000);
					next(from);
				}
			},
		},
		{
			path: '*', 
			redirect: '/'
		},
	];

const router = new VueRouter({
		base: __dirname,
		routes,
		scrollBehavior (to, from, savedPosition) {
			if (theme.checkWidth()) {
				return { x: 0, y: 200 }
			}
			else {
				return { x: 0, y: 0 }
			}
		},
	});

router.beforeEach((to, from, next) => {
	const authRequired = to.matched.some((route) => route.meta.auth);

	const guestRequired = to.matched.some((route) => route.meta.guest);

	const vendorRequired = to.matched.some((route) => route.meta.vendor);

	const adminRequired = to.matched.some((route) => route.meta.admin);

	const token = auth.getToken();

	if (authRequired && !auth.checkAuth()) {
		theme.config.NEXT = to;
		next('/login');
	} 

	else if (guestRequired && auth.checkAuth()) {
		axios.defaults.headers.common['Authorization'] = 'Bearer ' + auth.getToken().token;
		next('/dashboard');
	}
	else {
		next();
	}
});

new Vue({
  el: '#app',
  router,
  render: h => h(App)
});
