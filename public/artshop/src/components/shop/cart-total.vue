<template>
  <div class="row">
    <div class="col-md-6">
      <form class="cart-total">
        <h4>Totals</h4>
        <table>
          <tbody>
          <tr>
            <td>Number of Items</td>
            <td> <span class="subtotal">{{cart.totalQty}}</span></td>
          </tr>
          <tr v-if="!shoppingCart">
            <td>Subtotal</td>
            <td> <span class="subtotal">&#8358;{{cart.totalPrice | money}}</span></td>
          </tr>
          <tr v-if="!shoppingCart">
            <td>Shpping Cost</td>
            <td> <span class="subtotal">&#8358;{{shipping_cost | money}}</span></td>
          </tr>
          <tr v-if="shoppingCart">
            <td>Total</td>
            <td> <span class="total">&#8358;{{cart.totalPrice | money}}</span></td>
          </tr>
          <tr v-if="!shoppingCart">
            <td>Total</td>
            <td> <span class="total">&#8358;{{parseInt(cart.totalPrice) + parseInt(shipping_cost) | money}}</span></td>
          </tr>
        </tbody>
      </table>
      </form>
    </div>
  </div>	
</template>

<script>
	export default{
    props: ['cart', 'shipping_cost', 'shoppingCart'],
		data(){
			return{

			}
		},
		methods: {

		},
    created () {
      // this.cart = this.shop.getCart();
    }
	}
</script>

<style>
	
</style>

