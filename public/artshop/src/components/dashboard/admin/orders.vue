<template>
<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
	<!-- Recently Favorited -->
	<div id="customer_details" class="widget dashboard-container my-adslist">
		<h2 class="widget-header">ORDERS</h2>
		<a class="btn btn-transparent" @click.prevent="sortByStatus('paid')"> 
			<span>PAID</span>
		</a>
		<a class="btn btn-transparent" @click.prevent="sortByStatus('acknowledged')"> 
			<span>ACKNOWLEDGED</span>
		</a>
		<a class="btn btn-transparent" @click.prevent="sortByStatus('delivered')"> 
			<span>DELIVERED</span>
		</a>
		<a class="btn btn-transparent" @click.prevent="sortByStatus('confirmed')"> 
			<span>CONFIRMED</span>
		</a>
		<a class="btn btn-transparent" @click.prevent="sortByStatus('payout in progress')"> 
			<span>PAYOUT IN PROGRESS</span>
		</a>
		<a class="btn btn-transparent" @click.prevent="sortByStatus('payout completed')"> 
			<span>PAYOUT COMPLETED</span>
		</a>
		<h3 class="">{{status}}</h3>
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
			    <tr v-for="order in customerOrders" :key="order.id" v-if="customerOrders.length !== 0">
			      <td data-title="Image">
			      	<a href="#" class="image-product"><img src="/images/logo/favicon.png" alt="tab-1" width="180" height="220"></a>
			      </td>
			      <td data-title="Items">
			      	<a href="#" class="name-product" 
					  v-for="product in order.cart.cart.items" :key="product.id"
					  @click.prevent="singleOrder(order.id)">
			      		{{product.item.name}} ({{product.qty}}), <br> 
			      	</a>
			      </td>
			      <td data-title="Ammount">
			      	<span class="price">&#8358;{{order.amount_charged | money}}</span>
			      </td>
			      <td data-title="Status">
			      	<span class="quantity">   {{order.status}}</span>
			      </td>
			      <td data-title="Date of Purchase"><span class="total">{{order.created_at | moment("dddd, MMMM Do YYYY")}}</span></td>
			    </tr>
					
			  </tbody>
			</table>
			<h1 v-if="customerOrders.length <= 0">No Orders Yet</h1>
		</div>
	</div>
</div>
</template>

<script>

	export default{
		props: ['user', 'orders', 'myOrders'],
		data(){
			return{
				customerOrders: [],
				status: 'all',
			}
		},
		methods: {
			singleOrder (id) {
				this.$router.push('/dashboard/orders/' + id);
			},
			sortByStatus(status) {
				this.status = status;
				this.customerOrders = this.orders.filter(order => order.status === status);
			}
		},

		computed: {

		},

		created () {
			this.customerOrders = this.orders
		},

	}
</script>

<style>
	
</style>