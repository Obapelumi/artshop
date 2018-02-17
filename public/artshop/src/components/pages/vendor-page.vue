<template>
<div class="product-page portfolio-full slider has-header" id="vendor" v-if="vendors.length > 0">
        <art-page-header :page="'Vendors'" :subPage="thisVendor.brand_name"></art-page-header>
        <div class="container section">
          <div class="row">
            <div class="col-md-6 portfolio-slider">
                <div class="">
                  <figure>
                    <img 
                      :src="theme.imagePath(thisVendor.file.path)" 
                      :alt="thisVendor.brand_name"
                      v-if="thisVendor.file"
                      class="attachment-shop_catalog size-shop_catalog wp-post-image" 
                    >
                    <img 
                      src="/images/logo/favicon.png"
                      :alt="thisVendor.brand_name"
                      v-else
                      class="attachment-shop_catalog size-shop_catalog wp-post-image" 
                    >
                  </figure>
                </div>
            </div>
            <div class="portfolio-content-wrap col-md-6">
              <div class="portfolio-title-wrap">
                <h2 class="portfolio-title p-font">{{thisVendor.brand_name}}</h2>
              </div>
              <div class="portfolio-info">
                <p>{{thisVendor.bio}}</p>
              </div>
              <div class="portfolio-info spec">
                <div class="portfolio-info-box">
                  <h6 class="p-color p-font">Date Joined</h6>
                  <div class="portfolio-term-date">{{thisVendor.created_at | moment("dddd, MMMM Do YYYY") }}</div>
                </div>
                <div class="portfolio-info-box" v-if="sales > 0">
                  <h6 class="p-color p-font">Sales</h6>
                  <div class="portfolio-term-date">{{sales}}</div>
                </div>
                <div class="portfolio-info-box">
                  <h6 class="p-color p-font">Category</h6>
                  <div class="portfolio-term-cat"><span>{{thisVendor.category.name}}</span></div>
                </div>
                <div class="portfolio-info-box" v-if="tags.length < 0">
                  <h6 class="p-color p-font">Tags</h6>
                  <div class="portfolio-term-tag" v-for="tag in tags" :key="tag.id">
                    <span>{{tag.name}}</span>
                  </div>
                </div>
                <div class="portfolio-info-box">
                  <h6 class="p-color p-font">Follow</h6>
                  <ul class="portfolio-social-profile-wrapper">
                    <li>
                      <a :href="'https://twitter.com/share?url=' + theme.config.SITE_URL + '/vendors/' + thisVendor.slug + '&amp;text=' + thisVendor.brand_name" target="_blank"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                      <a :href="'http://www.facebook.com/sharer.php?u=' + theme.config.SITE_URL + '/vendors/' + thisVendor.slug" target="_blank"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                      <a :href="'https://plus.google.com/share?url=' + theme.config.SITE_URL + '/vendors/' + thisVendor.slug" target="_blank"><i class="fa fa-google-plus"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <div class="section slider-portfolio" v-if="vendorProducts.length > 0">
        <div class="container">
          <div class="sperator text-center">
            <p>{{thisVendor.brand_name}}'s Products</p>
            <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
          </div>
          <ul id="da-thumbs" data-number="3" data-margin="10" data-loop="yes" data-navcontrol="yes" class="da-thumbs sofani-owl-carousel">
            <li class="slider-portfolio-item" v-for="product in vendorProducts" :key="product.id">
              <div class="hover-dir">
                <figure>
                    <img 
                      :src="theme.imagePath(product.file[0].path)" 
                      :alt="product.name"
                      v-if="product.file.length > 0"
                      class="attachment-shop_catalog size-shop_catalog wp-post-image" 
                      @click.prevent="singleProduct(product.slug)"
                    >
                    <img 
                      src="/images/logo/favicon.png"
                      :alt="product.name"
                      v-else
                      class="attachment-shop_catalog size-shop_catalog wp-post-image"
                      @click.prevent="singleProduct(product.slug)" 
                    >
                </figure>
                <div>
                  <div class="in-slider">
                    <div class="in-slider-content"><a href="images/demo/portfolio-1.jpg" rel="prettyPhoto[gallery1]"><i class="fa fa-search"></i></a><a href="portfolio-vertical-slider.html">
                        <h5>{{product.name}}</h5></a>
                      <!-- <p>{{product.category.name}}</p> -->
                    </div>
                  </div>
                </div>
              </div>
                <div class="title-bottom">
                    <a href="#" class="pull-left" @click.prevent="singleProduct(product.slug)">{{product.name}}</a>
                    <a>&#8358;{{product.price | money}}</a>
                    <i class="fa fa-cart-plus" style="padding-right: 20px;" @click="addToCart(product)"></i> <i class="fa fa-heart" @click="addToWishList(product)"></i>

                </div>
            </li>
          </ul>
        </div>
      </div>
</div>
</template>

<script>
export default {
	props: ['vendors', 'products'],
	data () {
		return {
            thisVendor: {},
            vendorProducts: [],
            tags: [],
            sales: 0,
		}
	},
	methods: {
        setVendor () {
            var vendors = this.vendor.singleVendor(this);
            if (vendors.length > 0) {
              this.thisVendor = vendors[0];
            }
            else if (this.vendors.length > 0) {
              this.$router.push('/not-found');
            }
        },
        setProducts () {
            this.vendorProducts = this.shop.productsByVendor(this, this.thisVendor.id);
        },
        setTags () {
            var tags = [];
            this.vendorProducts.forEach(product => {
                if (product.tag.length > 0) {
                    tags.push(product.tag);   
                }
            });
            this.tags = tags;
        },
        setSales () {
            this.sales = this.vendor.vendorSales(this.vendorProducts);
        },
        singleProduct (slug) {
            var push = '/product/' + slug;
            this.$router.push(push);
        },
        addToCart (product) {
            this.shop.addToCart(product, 1);
            this.theme.smoke('success', 'Product added to cart', 1200);
            this.$emit('updateCart');
        },
        addToWishList (product) {
            this.shop.addToWishList(this, product);
            this.$emit('updateWishList');
        }
    },
  watch: {
    vendors () {
      this.setVendor();
      this.setProducts();
    },
    products () {
        this.setProducts();
        this.setVendor();
        this.setTags();
        this.setSales();
    }
  },
  created () {
    if (this.vendors.length > 0) {
      this.setVendor();
      this.setProducts();
      this.setTags();
      this.setSales();
    }
  },
  mounted () {
    this.theme.carousel();
  },
}
</script>

<style>
  #vendor i {
    cursor: pointer;
  }
</style>