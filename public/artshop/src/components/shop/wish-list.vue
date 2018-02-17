<template>
<section class="wishlist">
  <title>Artshop | Wish List</title>
  <div class="container">
    <form class="wishlist-form">
      <h2>My wishlist on ARTSHOP</h2>
      <table>
        <tr>
          <th>&#32;</th>
          <th>&#32;</th>
          <th>Product Name</th>
          <th>Price </th>
          <th>Stock </th>
          <th>&#32;</th>
        </tr>
        <tr v-for="product in wishList" :key="product.id" v-if="wishList">
          <td data-title="Remove"><a href="#" class="remove" @click.prevent="removeFromWishList(product)"><i class="pe-7s-close-circle"></i></a></td>
          <td data-title="Image">
          	<a href="#" @click.prevent="singleProduct(product.slug)">
              <img 
                :src="theme.imagePath(product.file[0].path)" 
                :alt="product.name" 
                width="180" height="220" style="height: 180px; width: 180px"
                v-if="product.file.length > 0"
              >
              <img 
                src="/images/logo/logo-black.jpg"
                :alt="product.name" 
                width="180" height="220" style="height: 180px; width: 180px" 
                v-else
              >
            </a>
          </td>
          <td data-title="Product Name">
          	<a href="#" @click.prevent="singleProduct(product.slug)" class="product-name">{{product.name}}</a>
          </td>
          <td data-title="Unit Price">
          	<span class="amount">&#8358;{{ product.price | money }}</span>
          </td>
          <td data-title="Stock">
          	<span class="in-stock">{{ product.stock }} left</span>
          </td>
          <td data-title="">
          	<a href="#" class="buttuon" @click.prevent="addToCart(product)">Add To Cart</a>
          </td>
        </tr>
      </table>
      <h1 v-if="!shop.checkWishList()">There are no items in your wish list</h1>
    </form>
  </div>
</section>
</template>

<script>
export default {
	props: ['wishList'],
	data () {
		return {

		}
	},
	methods: {
		removeFromWishList (product) {
			this.shop.removeFromWishList(this, product);
			this.$emit('updateWishList');
		},
		addToCart (product) {
		    this.shop.addToCart(this, product, 1);
    },
    singleProduct (slug) {
      var push = '/product/' + slug;
      this.$router.push(push);
    },
	},
}
</script>

<style>
	.wishlist .remove:hover,
	.wishlist .remove:focus {
		color: #C91713;
	}
</style>