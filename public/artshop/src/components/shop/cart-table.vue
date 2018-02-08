<template>
<table>
    <tbody>
    <tr>
      <th>Product</th>
      <th> </th>
      <!-- <th>Sizes</th> -->
      <th>Price</th>
      <th>Quantity</th>
      <th>Total</th>
      <th v-if="shoppingCart"> </th>
    </tr>
    <tr v-if="!shop.checkCart()">
      <h1>Your Cart is empty</h1>
    </tr>
    <art-cart-table-item v-for="item in cart.items" 
      :item="item" 
      :shoppingCart="shoppingCart" 
      :checkCart="checkCart" 
      :key="item.item.id" 
      @updateCart="updateCart"
      v-if="shop.checkCart()">
    </art-cart-table-item>
  </tbody>
</table>	
</template>

<script>
import CartItem from './cart-table-item.vue';

	export default{
    props: ['cart', 'shoppingCart', 'checkCart'],
		data(){
			return{

			}
		},
		methods: {
        updateCart () {
          this.$emit('updateCart');
        },
		},
    components: {
      'art-cart-table-item': CartItem,
    }
	}
</script>

<style>
	
</style>

