 /*Dependencies*/
import Vue from 'vue';
import VeeValidate from 'vee-validate';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import auth from '../auth/';

/*Use Imports*/ 
Vue.use(VueAxios, axios);
axios.defaults.baseURL = 'http://localhost:8000/';
axios.defaults.headers.common['Authorization'] = auth.getToken.token;
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
Vue.use(VueRouter);
Vue.use(VeeValidate);

require('./components.js');

// /*Routing*/ 
// const routes = [
// 		{
// 			path: '/', 
// 			component: IndexBody
// 		},
// 		{
// 			path: '/not-found', 
// 			component: NotFound
// 		},
// 		{
// 			path: '/login', 
// 			component: Login,
// 			meta: {
// 				guest: true
// 			},
// 		},
// 		{
// 			path: '/signup', 
// 			component: Signup,
// 			meta: {
// 				guest: true
// 			},
// 		},
// 		{
// 			path: '/blog',
// 			component: Blog
// 		},
// 		{
// 			path: '/dashboard',
// 			component: Dashboard,
// 			meta: {
// 				auth: true
// 			},
// 			children: [
// 				{
// 					path: '',
// 					component: DashboardIndex
// 				},
// 				{
// 					path: 'edit-profile',
// 					component: DashboardEditProfile
// 				},
// 			],
// 		},
// 		{
// 			path: '/cart', 
// 			component: Cart
// 		},
// 		{
// 			path: '/checkout', 
// 			component: Checkout
// 		},
// 		{
// 			path: '*', 
// 			redirect: '/not-found'
// 		},
// 	];

// const router = new VueRouter({
// 		mode: 'history',
// 		base: __dirname,
// 		routes,
// 	});

// router.beforeEach((to, from, next) => {
//   const authRequired = to.matched.some((route) => route.meta.auth);

//   const guestRequired = to.matched.some((route) => route.meta.guest);

//   if (authRequired && !auth.checkAuth) {
//     next('/login');
//   } 
//   else if (guestRequired) {
//   	next('/dashboard');
//   }
//   else {
//     next();
//   }

// });

