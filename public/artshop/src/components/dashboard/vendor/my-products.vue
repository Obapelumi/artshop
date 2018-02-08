<template>
<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
	<!-- Recently Favorited -->
	<div class="widget dashboard-container my-adslist">
		<h2 class="widget-header">My Products</h2>
		<div class="cart-form">
			<table>
			    <tbody>
			    <tr>
			      <th>Image</th>
			      <th>Product</th>
			      <th>Price</th>
			      <th>Status</th>
				  <th>Sales</th>
			      <th>Created</th>
			      <th>Edit</th>
			    </tr>
			    <tr v-for="product in myProducts" :key="product.id" v-if="myProducts" :class="{ updateStock: stockPending(product) }" class="">
			      <td data-title="Product">
			      	<a href="#" @click.prevent="singleProduct(product.slug)" class="image-product">
                        <img 
                            :src="theme.imagePath(product.file[0].path)" 
                            :alt="product.name" 
                            width="180" height="220" style="height: 80px; width: 180px"
                            v-if="product.file.length > 0"
                        >
                        <img 
                            src="/images/logo/logo-black.jpg"
                            :alt="product.name" 
                            width="180" height="220" style="height: 80px; width: 180px" 
                            v-else
                        >
					</a>
			      </td>
			      <td data-title="Name">
			      	<a @click.prevent="singleProduct(product.slug)" href="" class="name-product">
						<h4>{{product.name}}</h4>
			      	</a>
			      </td>
			      <td data-title="Price">
			      	<span class="price">&#8358;{{product.price | money}}</span>
			      </td>
			      <td data-title="Status">
					<span class="quantity capitalize" v-if="stockPending(product)">Update Stock</span>
			      	<span class="quantity capitalize" v-else>   {{shop.productStatus(product.display)}}</span>
			      </td>
				  <td data-title="Status">
					<span class="quantity" style="tex-align: center;">{{product.quantity_sold}}</span>
			      </td>
			      <td data-title="Created">
			      	<span class="total">{{product.created_at | moment("dddd, MMMM Do YYYY")}}</span>
			      </td>
			      <td data-title="Edit">
			      	<a @click.prevent="editPage(product.slug)" href="">
			      		<i class="fa fa-pencil"></i>
			      	</a>
			      </td>
			    </tr>
			  </tbody>
			</table>
			<h1 v-if="myProducts.length <= 0">You have no products yet</h1>
		</div>
	</div>
</div>
</template>

<script>

	export default{
		props: ['user', 'orders', 'myOrders', 'products'],
		data(){
			return{
				myProducts: [],
			}
		},
		methods: {
			editPage (slug) {
	        var push = '/dashboard/edit-product/' + slug;
	        this.$router.push(push);
			},
		    singleProduct (slug) {
		      var push = '/product/' + slug;
		      this.$router.push(push);
	        },
	        setProducts () {
				var id = this.user.vendor.id;
				this.myProducts = this.shop.productsByVendor (this, id);
			},
			stockPending(product) {
				return this.vendor.checkStockUpdate(this, product)
			}
		},
		watch: {
			products () {
				this.setProducts();	
			}
		},
		created () {
			if (this.products) {
				this.setProducts();	
			}
		},

	}
</script>

<style>
	.updateStock {
		background: rgb(192, 192, 192);
	}
</style>