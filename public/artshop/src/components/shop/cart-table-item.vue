<template>
    <tr v-if="checkCart">
      <td data-title="Product">
		  <a href="#" class="image-product" @click.prevent="singleProduct">
			<img 
				:src="theme.imagePath(item.item.file[0].path)" 
				:alt="item.item.name" 
				width="180" height="220" style="height: 80px; width: 180px"
				v-if="item.item.file.length > 0"
			>
			<img 
				src="/images/logo/logo-black.jpg"
				:alt="item.item.name" 
				width="180" height="220" style="height: 80px; width: 180px" 
				v-else
			>
			</a>
	  </td>
      <td data-title="Name">
		  <a href="#" class="name-product" @click.prevent="singleProduct">{{item.item.name}} </a>
	  </td>
	  <!-- <td data-title="Sizes">
		  <span v-for="size in item.sizes" :key="size">{{size}}</span>, <br>
	  </td> -->
      <td data-title="Price">
		  <span class="price" v-if="item.item.discount.length > 0">&#8358;{{item.item | discount | money}}</span>
		  <span class="price" v-else>&#8358;{{item.item.price | money}}</span>
	</td>
      <td data-title="Quantity"><span class="quanlity">Quantity:</span>
        <input type="number" value="1" min="0" @change="updateCart()" :max="item.item.stock" v-model="qty" v-if="shoppingCart">
        <input type="number" value="1" min="0" :max="item.item.stock" v-model="qty" readonly v-if="!shoppingCart">
      </td>
      <td data-title="Total"><span class="total">&#8358;{{item.cost | money}}</span></td>
      <td data-title="Remove" v-if="shoppingCart"><a href="" @click.prevent="remove"><i class="fa fa-times"></i></a></td>
    </tr>
</template>

<script>
export default {
	props: ['item', 'shoppingCart', 'checkCart',],
	data () {
		return {
			qty: this.item.qty,
		}
	},
	methods: {
		updateCart () {
			var diff = parseInt(this.qty) - this.item.qty;
			if (diff !== 0) {
				if (diff < 0) {
					diff = Math.abs(diff);
					this.shop.removeFromCart(this, this.item.item, diff);
				}
				else {
					this.shop.addToCart(this, this.item.item, diff);
				}
			}
		},
		remove () {
			this.shop.removeFromCart(this, this.item.item, this.qty);
		},
	    singleProduct () {
		     var slug = '/product/' + this.item.item.slug;
		    this.$router.push(slug)
	    },
	},
}
</script>

<style>
	
</style>