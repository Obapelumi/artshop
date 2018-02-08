<template>
<div class="product-item-wrap has-post-thumbnail">
<div class="product-item-inner">
  <div class="product-thumb">
    <div class="product-flash-wrap" v-if="!shop.checkStock(product)">
      <span class="on-sale product-flash" v-show="false">8.8%</span>
      <span class="on-sale product-flash">Product Out of Stock</span>
    </div>
    <div class="product-thumb-primary">
      <img width="300" height="400" 
        :src="theme.imagePath(product.file[0].path)" 
        :alt="name" 
        :title="name" 
        class="attachment-shop_catalog size-shop_catalog" 
        v-if="product.file.length > 0"
      />
      <img width="300" height="400" 
        src="/images/logo/logo-black.jpg" 
        :alt="name" :title="name" 
        class="attachment-shop_catalog size-shop_catalog wp-post-image" 
        v-else 
      />
    </div>
    <div class="product-thumb-secondary">
      <img width="300" height="400" 
        :src="theme.imagePath(product.file[0].path)" 
        :alt="name" 
        :title="name" 
        class="attachment-shop_catalog size-shop_catalog" 
        v-if="product.file.length > 0"
      />
      <img width="300" height="400" 
        src="/images/logo/logo-black.jpg"
        :alt="name" 
        :title="name" 
        class="attachment-shop_catalog size-shop_catalog wp-post-image" 
        v-else 
      />
    </div>
    <a href="#" class="product-link" @click.prevent="singleProduct">
      <div class="product-hover-sign">
        <hr/>
        <hr/>
      </div>
    </a>
    <div class="product-actions">
      <div class="yith-wcwl-add-to-wishlist add-to-wishlist-386">
        <div class="yith-wcwl-wishlistexistsbrowse show">
          <span class="feedback">The product is already in the wishlist!</span>
          <a href="#" @click.prevent="addToWishList">Browse Wishlist</a>
        </div>
      </div>
      <div class="add-to-cart-wrap" v-if="shop.checkStock(product)">
        <a href="#" class="add_to_cart_button" @click.prevent="addToCart">
          <i class="fa fa-cart-plus"></i> Add to cart
        </a>
      </div>
    </div>
  </div>
  <div class="product-info">
    <div class="rate" v-if="shop.productRating(product)">
      <span v-for="rating in ratings" :key="rating">
        <i class="fa fa-star" v-if="shop.productRating(product) >= rating"></i>
        <i class="fa fa-star-o" v-else></i>
      </span>
    </div>
    <span class="price">
      <span class="woocommerce-Price-amount amount" v-if="shop.checkDiscount(product)">
        <strike>
          <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{price | money}}
        </strike> {{product.discount[0].discount * 100}}% off <br>
          <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product | discount | money}}
      </span>
      <span class="woocommerce-Price-amount amount" v-else>
          <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{price| money}}
      </span> <br>
    </span>
    <a href="#" @click.prevent="singleProduct()">
      <h3>{{name}}</h3>
    </a>
  </div>
</div>
</div>
</template>

<script>
	export default{
		props: ['product', 'API_URL', ],
		data(){
			return{
        name: this.product.name,
        price: this.product.price,
        ratings: [1, 2, 3, 4, 5],

			}
		},
		methods: {
			addToCart () {
				this.shop.addToCart(this, this.product);
        this.$emit('updateCart');
			},
      addToWishList () {
        this.shop.addToWishList(this, this.product);
        this.$emit('updateWishList');
      },
      singleProduct () {
        var slug = '/product/' + this.product.slug;
        
        if (this.$route.path.includes('/product/')) {
          this.$router.go(slug)
        }
        else {
          this.$router.push(slug)
        }
      },
		},
		created () {

		},
	}
</script>

<style>
	
</style>