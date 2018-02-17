<template>
<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0" v-if="order != null">
	<!-- Recently Favorited -->
	<div class="widget dashboard-container my-adslist">
    <h4>Customer Name:</h4>
    <span>{{order.user.name}}</span><br> <br>
    <h4>Phone Number:</h4>
    <span>{{order.customer.phone}}</span><br> <br>
    <h4>Delivery Address:</h4>
    <span>{{order.customer.address}}</span>
    <h4 v-if="order.order_notes">Order Notes:</h4>
    <span>{{order.order_notes}}</span>
    <hr>
	<h2 class="widget-header">Order</h2>
    <div class="cart-form">
        <art-cart-table 
            :cart="order.cart.cart" 
            :shoppingCart="shoppingCart" 
            :checkCart="orderedCart">
        </art-cart-table>
        <art-cart-total :cart="order.cart.cart" 
            :shipping_cost="order.cart.shipping_cost"
            :shoppingCart="shoppingCart" 
        ></art-cart-total>
    </div>
    <div class="cart-total col-md-8" v-if="auth.checkAdmin()">
        <h4>HANDLE ORDER</h4>
        <form action="" class="woocommerce-billing-fields" @submit.prevent="updateOrder" v-if="order.status !== 'payout completed'">
            <div class="col-md-8">
                <p>
                    <label>Order Status <abbr title="Adjust accordingly" class="required">?</abbr></label>
                    <select name="" id="" v-model="order.status">
                        <option :value="status" v-for="status in statuses" :key="status">{{status}}</option>
                    </select>
                </p>
            </div>

            <div class="button-cart-right col-md-4">
                <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 23px;" type="submit" v-if="order.status === 'confirmed'"> <span>PAY VENDOR</span></button>
                <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 23px;" type="submit" v-else> <span>UPDATE</span></button>
            </div>
        </form>
        <h5 v-else>This order has been delivered and payment has been made to the vendor(s)</h5>
    </div> <div class="clearfix"></div>
  </div>
</div>
</template>

<script>
	export default{
      props: ['myOrders', 'orders'],
      data(){
        return{
          shoppingCart: false,
          orderedCart: true,
          statuses: ['paid', 'acknowledged', 'delivered', 'confirmed', 'payout in progress'],
          order: null,
        }
      },

      methods: {
        setOrder () {
            if (this.$route.path.includes('/dashboard/my-orders')) {
                this.order = this.getCustomerCart();
            }
            else {
                this.order = this.getAdminCart();
            }
        },
        getCustomerCart () {
            var id = this.$route.params.id;
            var result = this.myOrders.filter(order => order.id == id)[0];
            return result;
        },
        getAdminCart () {
            var id = this.$route.params.id;
            var result = this.orders.filter(order => order.id == id)[0];
            return result;
        },
        updateOrder () {
            this.admin.updateOrderStatus(this);
        },
      },

      watch : {
          orders () {
              this.setOrder();
          }
      },

      created () {
          if (this.orders.length > 0 || this.myOrders.length > 0) {
              this.setOrder();
          }
      },
      beforeDestroy () {
        if (this.$route.path.includes('/dashboard/my-orders')) {
            this.order = this.getCustomerCart();
        }
        else {
            this.order = this.getAdminCart();
        } 
      }
	}
</script>

<style>

</style>