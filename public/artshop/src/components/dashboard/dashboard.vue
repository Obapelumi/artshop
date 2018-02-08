<template>
<section class="dashboard section" id="dashboard-index">
	<title>Artshop | Dashboard</title>
	<!-- Container Start -->
	<div class="container">
		<!-- Row Start -->
		<div class="row">
			<art-dashboard-sidebar 
				:user="user" 
				:vendors="vendors"
				:admins="admins"
				:myOrders="myOrders" 
				:orders="orders" 
				:products="products"
			></art-dashboard-sidebar>
			<transition name="slideRight">
			<router-view 
				:user="user" 
				:vendors="vendors"
				:admins="admins"
				:products="products" 
				:tags="tags"
				:posts="posts"
				:categories="categories" 
				:orders="orders" 
				:myOrders="myOrders"
				:newsletters="newsletters"
				@updateProduct="$emit('updateProduct')"
				@updateShop="$emit('updateShop')"
				@updateOrders="adminData">		
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
		props: ['products', 'categories', 'posts', 'vendors', 'tags'],
		data(){
			return{
				user: {},
				orders: [],
				admins: [],
				myOrders: [],
				newsletters: [],
				thisVendor: null,
				thisAdmin: null,
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
			setAdmin () {
				this.thisAdmin = this.admins.filter(admin => admin.user_id === this.user.id)[0];
			},
			setVendor () {
				if (this.auth.checkVendor()) {
					this.thisVendor = this.vendors.filter(vendor => vendor.user_id === this.user.id)[0];
				}
			},
			adminData () {				
				if (this.auth.checkAdmin()) {
					this.shop.getOrders(this);
					this.admin.getNewsLetters(this);
					this.admin.getAdmins(this);
					this.setAdmin();
				}
			}
		},
		created () {
			this.user = this.auth.getUser(this);
			this.getMyOrders();
			this.adminData();
			this.setVendor();
		},
	}
</script>

<style>

</style>