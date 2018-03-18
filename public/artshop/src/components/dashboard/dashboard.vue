<template>
<section class="dashboard section" id="dashboard-index">
	<title>Dashboard | Artshop</title>
	<!-- Container Start -->
	<div class="container">
		<!-- Row Start -->
		<div class="row">
			<art-dashboard-sidebar 
				:user="user" 
				:thisVendor="thisVendor()"
				:thisAdmin="thisAdmin()"
				:myOrders="myOrders" 
				:orders="orders" 
				:products="products"
				:reviews="reviews"
			></art-dashboard-sidebar>
			<transition name="slideRight">
			<router-view 
				:user="user" 
				:vendors="vendors"
				:admins="admins"
				:products="products"
				:reviews="reviews"
				:tags="tags"
				:posts="posts"
				:categories="categories" 
				:orders="orders" 
				:myOrders="myOrders"
				:newsletters="newsletters"
				@updateProduct="$emit('updateProduct')"
				@updateShop="$emit('updateShop')"
				@updatePosts="$emit('updatePosts')"
				@updateOrders="adminData"
				@updateReviews="$emit('updateReviews')">		
			</router-view>
			</transition>
		</div>
		<!-- Row End -->
	</div>
	<!-- Container End -->
</section>
</template>

<script>
	export default{
		props: ['products', 'categories', 'posts', 'vendors', 'tags', 'reviews'],
		data(){
			return{
				user: {},
				orders: [],
				admins: [],
				myOrders: [],
				newsletters: [],
			}
		},
		methods: {
			getMyOrders () {
				if (this.auth.checkCustomer()) {
					var $this = this;
					this.axios.get('order/' + $this.user.id)
						.then(response => {
							$this.myOrders = response.data.order
						});
				}
			},
			adminData () {				
				if (this.auth.checkAdmin()) {
					this.$emit('updateProduct');
					this.shop.getOrders(this);
					this.admin.getNewsLetters(this);
					this.admin.getAdmins(this);
				}
			},
			thisAdmin () {
				var $this = this;
				return this.admins.filter(admin => admin.user_id == $this.user.id)[0];
			},
			thisVendor () {
				if (this.auth.checkVendor()) {
					var $this = this;
					return this.vendors.filter(vendor => vendor.user_id == $this.user.id)[0];
				}
			},
		},
		computed: {

		},
		created () {
			this.user = this.auth.getUser(this);
			this.getMyOrders();
			this.adminData();
		},
	}
</script>

<style>

</style>