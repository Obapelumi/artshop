<template>
<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
	<!-- Recently Favorited -->
	<title>ARTSHOP | Dashboard</title>
	<div class="widget dashboard-container my-adslist">
		<h2 class="widget-header">My Orders</h2>
		<div class="cart-form">
			<table>
			    <tbody>
			    <tr>
			      <th> </th>
			      <th>Items</th>
			      <th>Ammount Paid</th>
			      <th>Status</th>
			      <th>Date of Purchase</th>
			    </tr>
				<!-- <transition name="fade"> -->
					<tr v-for="order in myOrders" :key="order.id" v-if="myOrders.length > 0">
					<td data-title="Image">
						<a href="#" @click.prevent="singleOrder(order.id)" class="image-product">
							<!-- <img :src="imagePath(order.cart.cart.items[0].file[0].path)" v-if="order.cart.cart.items[0].file[0].path" alt="tab-1" width="180" height="220" style="height: 80px; width: 180px"> -->
							<img src="/images/logo/favicon.png" alt="tab-1" width="180" height="220">
						</a>
					</td>
					<td data-title="Items" style="cursor: pointer" @click.prevent="singleOrder(order.id)">
						<a href="#" class="name-product" 
						v-for="product in order.cart.cart.items" :key="product.id"
						>{{product.item.name}} ({{product.qty}}), <br> </a>
					</td>
					<td data-title="Ammount">
						<span class="price">&#8358;{{order.amount_charged | money}}</span>
					</td>
					<td data-title="Status" class="capitalize">
						<span class="quantity" v-if="order.status === 'payout completed' || order.status === 'payout in progress'">   confirmed</span>
						<span class="quantity" v-else>   {{order.status}}</span>
					</td>
					<td data-title="Date Purchased"><span class="total">{{order.created_at | moment("dddd, MMMM Do YYYY")}}</span></td>
					</tr>
				<!-- </transition>			 -->
			  </tbody>
			</table>
			<h1 v-if="myOrders.length === 0">You have not placed any orders</h1>
		</div>
	</div>
</div>
</template>

<script>

	export default{
		props: ['user', 'orders', 'myOrders'],
		data(){
			return{
				
			}
		},
		methods: {
			singleOrder (id) {
				this.$router.push('/dashboard/my-orders/' + id);
			}
		},

		computed: {

		},

		created () {
			
		},

	}
</script>

<style>
	
</style>