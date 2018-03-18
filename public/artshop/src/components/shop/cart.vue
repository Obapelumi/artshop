<template>
<section>
  <div class="container">
    <title>Cart | Artshop</title>
    <h1>SHOPPING CART</h1>
    <form class="cart-form">
    <art-cart-table 
      :cart="cart" 
      :shoppingCart="shoppingCart" 
      :checkCart="checkCart" 
      @updateCart="updateCart">
    </art-cart-table>
    <art-cart-total :cart="cart" v-if="checkCart" :shoppingCart="shoppingCart"></art-cart-total>
      <div class="button-cart">
<!--         <div class="button-cart-left">
          <input type="text" placeholder="Coupon code"><a href="#" class="coupon">Apply Coupon</a>
        </div> -->
        <div class="button-cart-right pull-right">
          <button class="btn btn-transparent" @click.prevent="gocheckout" v-if="checkCart">CHECKOUT</button>
          <button class="btn btn-transparent" @click.prevent="$router.push('/shop')" v-else>SHOP</button>
        </div>
      </div>
    </form>
  </div>
</section>
</template>

<script>
	export default{
      props: ['cart', 'checkCart'],
      data(){
        return{
          shoppingCart: true,
        }
      },

      methods: {
        gocheckout () {
          this.$router.push('/checkout')
        },
        updateCart () {
          this.$emit('updateCart');
        },
      },

      created () {
        this.$emit('updateCart');
      },
	}
</script>

<style>

</style>