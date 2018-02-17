<template>
	<div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
		<div class="sidebar">
			<!-- User Widget -->
			<div class="widget user-dashboard-profile">
				<!-- User Image -->
				<div class="profile-thumb" v-if="auth.checkVendor() || auth.checkAdmin()">
					<img 
						:src="theme.imagePath(thisVendor.file.path)" 
						:alt="user.name"
						v-if="thisVendor && auth.checkVendor() && thisVendor.file"
						class="rounded-circle"
					>
					<img 
						src="/images/logo/favicon.png"
						:alt="user.name"
						v-else-if="auth.checkAdmin() || auth.checkBlogger()"
						class="rounded-circle"
					>
				</div>
				<!-- User Name -->
				<h5 class="text-center">{{user.name}}</h5>
				<p>Joined {{ user.created_at | moment("dddd, MMMM Do YYYY") }}</p>
				<router-link to="/dashboard/edit-profile" class="btn btn-main-sm" v-if="auth.checkVendor()">Edit Profile</router-link>
			</div>
			<!-- Dashboard Links -->
			<div class="widget user-dashboard-menu">
				<ul>
					<li :class="{ active: adminOrders }" v-if="auth.checkAdmin()">
						<router-link to="/dashboard/orders">
							<i class="fa fa-shopping-cart"></i> Customer Orders <span v-if="pendingOrders.length > 0">{{pendingOrders.length}}</span>
						</router-link>
					</li>
					<li :class="{ active: approveProducts }" v-if="auth.checkAdmin()">
						<router-link to="/dashboard/approve-products">
							<i class="fa fa-shopping-bag"></i> Artshop Products <span v-if="pendingProducts.length > 0">{{pendingProducts.length}}</span>
						</router-link>
					</li>
					<li :class="{ active: isProductReviews }" v-if="auth.checkAdmin()">
						<router-link to="/dashboard/admin/all-reviews">
							<i class="fa fa-quote-left"></i> Product Reviews <span v-if="reviews.length > 0">{{reviews.length}}</span>
						</router-link>
					</li>
					<li :class="{ active: isStatistics }" v-if="auth.checkAdmin()">
						<router-link to="/dashboard/admin/statistics">
							<i class="fa fa-bar-chart"></i> Statistics
						</router-link>
					</li>
					<li :class="{ active: inviteAdmin }" class="col-lg-6 col-md-6 col-sm-6" v-if="auth.checkAdmin()">
						<router-link to="/dashboard/invite-admin">
							<i class="fa fa-user"></i> Invite Admin
						</router-link>
					</li>
					<li :class="{ active: isBlog }" class="col-lg-6 col-md-6 col-sm-6" v-if="auth.checkAdmin()">
						<router-link to="/dashboard/blog">
							<i class="fa fa-newspaper-o"></i> Blog
						</router-link>
					</li>
					<li :class="{ active: isNewsletter }" class="col-lg-6 col-md-6 col-sm-6" v-if="auth.checkAdmin()">
						<router-link to="/dashboard/newsletter">
							<i class="fa fa-envelope-o"></i> News Letter
						</router-link>
					</li>
					<li>
					<li :class="{ active: isSettings }" class="col-lg-6 col-md-6 col-sm-6" v-if="auth.checkAdmin()">
						<router-link to="/dashboard/settings">
							<i class="fa fa-cog"></i> Settings
						</router-link>
					</li>
					<div class="clearfix"></div>
					<li :class="{ active: dashboard }" v-if="auth.checkCustomer()">
						<router-link to="/dashboard">
							<i class="fa fa-cart-plus"></i> My Orders <span v-if="myOrders.length > 0">{{myOrders.length}}</span>
						</router-link>
					</li>
					<li :class="{ active: isAccountSettings }">
						<router-link to="/dashboard/account-settings">
							<i class="fa fa-cog"></i> Account Settings
						</router-link>
					</li>
					<li :class="{ active: isBlog }"  v-if="auth.checkBlogger()">
						<router-link to="/dashboard/blog">
							<i class="fa fa-newspaper-o"></i> Blog
						</router-link>
					</li>
					<li :class="{ active: myProductPath }" v-if="products && auth.checkVendor()">
						<router-link to="/dashboard/my-products">
							<i class="fa fa-shopping-basket"></i> My Products <span v-if="myProducts">{{myProducts.length}}</span>
						</router-link>
					</li>
					<li :class="{ active: createProduct }" v-if="auth.checkVendor()">
						<router-link to="/dashboard/create-product">
							<i class="fa fa-pencil"></i> Create Product 
						</router-link>
					</li>
					<li :class="{ active: isReviews }" v-if="auth.checkVendor() && reviewCount">
						<router-link to="/dashboard/reviews">
							<i class="fa fa-quote-left"></i> Reviews <span>{{reviewCount}}</span>
						</router-link>
					</li>
					<li>
						<a href="" @click.prevent="signout()"><i class="fa fa-power-off"></i> Logout</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</template>

<script>

	export default{
		props: ['user', 'orders', 'myOrders', 'products', 'thisAdmin', 'thisVendor', 'reviews'],

		data(){
			return {
				myProducts: [],
				pendingProducts: [],
				pendingOrders: [],
				reviewCount: 0,
			}
		},
		methods: {
			signout () {
				this.auth.signout(this);
			},
			setMyProducts () {
				if (this.auth.checkVendor()) {
					var $this = this; 
					var id = this.user.vendor.id;
					if ($this.products) {
						$this.myProducts = $this.shop.productsByVendor (this, id);
					}
				}
			},
			setPendingProducts () {
				if (this.products) {
					this.pendingProducts = this.shop.productsByDisplay (this, 0);
				}
			},
			setPendingOrders () {
				var $this = this;
				if ($this.orders) {
					$this.pendingOrders = $this.orders.filter(order => order.status === 'paid');
				}
			},
			setReviewCount () {
				var $this = this;
				if ($this.products && this.auth.checkVendor()) {
					var id = this.user.vendor.id;
					var myProducts = this.shop.productsByVendor (this, id);
					var reviewedProducts = myProducts.filter(product => product.review.length > 0);
					$this.reviewCount = reviewedProducts.length;
				}
			},
		},
		computed: {
			dashboard () {
				if (this.$route.path === '/dashboard' 
					|| this.$route.path.includes('/dashboard/my-orders')) 
				{
					return true
				}
				else {
					return false
				}
			},
			isAccountSettings () {
				if (this.$route.path === '/dashboard/account-settings') 
				{
					return true
				}
				else {
					return false
				}
			},
			isProductReviews () {
				if (this.$route.path === '/dashboard/admin/all-reviews') {
					return true;
				}
				else {
					return false;
				}
			},
			createProduct () {
				if (this.$route.path === '/dashboard/create-product') {
					return true
				}
				else {
					return false
				}
			},
			myProductPath () {
				if (this.$route.path === '/dashboard/my-products') {
					return true
				}
				else {
					return false
				}
			},
			adminOrders () {
				if (this.$route.path === '/dashboard/orders' 
					|| this.$route.path.includes('/dashboard/orders')) 
				{
					return true
				}
				else {
					return false
				}
			},
			approveProducts () {
				if (this.$route.path === '/dashboard/approve-products') {
					return true
				}
				else if (this.$route.path.includes('/dashboard/pending-product')) {
					return true
				}
				else {
					return false
				}
			},
			isStatistics () {
				if (this.$route.path === '/dashboard/admin/statistics') {
					return true
				}
				else {
					return false
				}
			},
			isReviews () {
				if (this.$route.path === '/dashboard/reviews') {
					return true
				}
				else {
					return false
				}
			},
			inviteAdmin () {
				if (this.$route.path === '/dashboard/invite-admin') {
					return true
				}
				else {
					return false
				}
			},
			isNewsletter () {
				if (this.$route.path === '/dashboard/newsletter') {
					return true
				}
				else {
					return false
				}
			},
			isBlog () {
				if (this.$route.path === '/dashboard/blog') {
					return true
				}
				else {
					return false
				}
			},	
			isSettings () {
				if (this.$route.path === '/dashboard/settings') {
					return true
				}
				else {
					return false
				}
			},	
		},
		watch: {
			orders () {
				var $this = this;
				$this.setPendingOrders();
			},
			products () {
				var $this = this;
				$this.setMyProducts();
				$this.setPendingProducts();
				$this.setReviewCount();
			}
		},
		created () {
			var $this = this;
			$this.setReviewCount();
			$this.setMyProducts();
			$this.setPendingProducts();
			if ($this.orders.length > 0) {
				$this.setPendingOrders();	
			}
		},
	}
</script>

<style>
	
</style>