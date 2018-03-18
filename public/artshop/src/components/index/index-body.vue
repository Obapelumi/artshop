<template>
      <div id="example-wrapper" class="home-blog">
        <div class="section">
          <title>Artshop - Premier Handmade Marketplace to Buy & Sell Quality crafts, art, textile prints and afrocentric</title>
          <div class="slide-home slide-home-1">
            <div data-number="1" data-margin="100" data-loop="yes" data-navcontrol="yes" class="sofani-owl-carousel">
              <div class="slide1">
                <div class="slide1-left" style="background-color: rgba(51, 51, 51, 0.0); padding: 20px;">
                  <div class="title" style="background-color: rgba(51, 51, 51, 0.0); color: rgba(256,256,256,0); ">THE HOME OF NIGERIAN ART</div>
                  <h1 style="color: rgba(256,256,256,0);">Artshop</h1>
                  <p style="color: rgba(256,256,256,0);">
                    ArtShopNG is Nigeria’s leading online marketplace for Art, Crafts and Textile Prints, connecting people with art, crafts and textile prints they love.
                  </p>
                  <router-link to="/shop" style="color: rgba(256,256,256,0); border-color: rgba(256,256,256,0);">SHOP NOW</router-link>
                </div>
                <div class="slide1-right">
                  <figure>
                    <img src="/images/home/covers.jpg" alt="slide" style="height: 555px;" v-if="theme.checkWidth()"/>
                    <img src="/images/home/covers.jpg" alt="slide" v-else/>
                  </figure>
                  <!-- <div class="slide1-content">&#8358;95</div> -->
                </div>
              </div>
            </div>
          </div>
        </div>

        <section v-if="categories.length > 0">
          <div class="home-1-highlight yolo-wrap">
            <div class="sperator text-center">
              <p>PRODUCT CATEGORIES</p>
              <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
            </div>
            <div class="container">
              <div class="row">
                <div class="">
                  <div class="row">
                    <div class="col-sm-4" v-for="category in categories" :key="category.id">
                      <div class="banner-shortcode-wrap style_1">
                        <div class="banner-content title_top"><a href="#" @click.prevent="byCategories(category.id, category.name)">
                            <h3 class="banner-title">{{category.name}}</h3><span class="overlay-bg bg-df8888"></span><img width="277" height="285" src="/images/categories/category.jpg" :alt="category.name"/></a></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- .home-1-highlight-->
        </section>

        <section v-if="featuredProducts.length > 0">
          <div class="product">
            <div class="sperator text-center">
              <p>FEATURED PRODUCTS</p>
              <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
            </div>
            <div class="product-tab">
              <ul class="products-tabs nav nav-pills">
                <li class="active"><a href="#1a" @click.prevent="allProducts" data-toggle="tab">All</a></li>
                <li v-for="category in categories" :key="category.id">
                  <a href="#1a" @click.prevent="byCategory(category.id)" data-toggle="tab">{{category.name}}</a>
                </li>
              </ul>
              <div class="desc-review-content tab-content clearfix">
                <div id="1a" class="tab-pane active">
                  <div class="home-1-featured-products">
                    <div class="products-in-category-tabs-wrapper">
                      <div class="products-content">
                        <transition name="fade">
                        <div class="woocommerce product-listing columns-5 clearfix" v-if="categoryProducts.length > 0">
                          <div class="product-item-wrap has-post-thumbnail" v-for="product in categoryProducts" :key="product.id">
                            <div class="product-item-inner has-post-thumbnail">
                              <div class="product-thumb">
                                <div class="product-flash-wrap" v-if="!shop.checkStock(product)">
                                  <span class="on-sale product-flash" v-show="false">8.8%</span>
                                  <span class="on-sale product-flash">Product Out of Stock</span>
                                </div>
                                <div class="product-thumb-primary">
                                  <img width="300" height="400" 
                                    :src="theme.imagePath(product.file[0].path)" 
                                    :alt="product.name" 
                                    :title="product.name" 
                                    class="attachment-shop_catalog size-shop_catalog" 
                                    v-if="product.file.length > 0"
                                  />
                                  <img width="300" height="400" 
                                    src="/images/logo/logo-black.jpg"
                                    :alt="product.name" :title="product.name" class="attachment-shop_catalog size-shop_catalog wp-post-image" v-else />
                                </div>
                                <div class="product-thumb-secondary">
                                  <img width="300" height="400" 
                                    :src="theme.imagePath(product.file[0].path)" 
                                    :alt="product.name" 
                                    :title="product.name" 
                                    class="attachment-shop_catalog size-shop_catalog" 
                                    v-if="product.file.length > 0"
                                  />
                                  <img width="300" height="400" 
                                    src="/images/logo/logo-black.jpg"
                                    :alt="product.name" 
                                    :title="product.name" 
                                    class="attachment-shop_catalog size-shop_catalog wp-post-image" 
                                  v-else />
                                </div>
                                <a href="#" @click.prevent="singleProduct(product.slug)" class="product-link">
                                  <div class="product-hover-sign">
                                    <hr/>
                                    <hr/>
                                  </div>
                                </a>
                                <div class="product-actions">
                                  <div class="yith-wcwl-add-to-wishlist add-to-wishlist-386">
                                    <div class="yith-wcwl-wishlistexistsbrowse show"><span class="feedback">The product is already in the wishlist!</span>
                                    <a href="#" @click.prevent="addToWishList(product)">Browse Wishlist</a></div>
                                  </div>
                                  <div class="add-to-cart-wrap" v-if="shop.checkStock(product)"><a href="#" @click.prevent="addToCart(product)" class="add_to_cart_button"><i class="fa fa-cart-plus"></i> Add to cart</a></div>
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
                                          <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product.price | money}}
                                        </strike> {{product.discount[0].discount * 100}}% off <br>
                                          <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product | discount | money}}
                                      </span>
                                      <span class="woocommerce-Price-amount amount" v-else>
                                          <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product.price| money}}
                                      </span> <br>
                                  </span>
                                  <a href="#" @click.prevent="singleProduct(product.slug)">
                                    <h3>{{product.name}}</h3>
                                  </a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <art-loading v-else-if="productFirstLoad"></art-loading>
                        <h1 v-else>No Products found</h1>
                        </transition>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- <div class="section-no-margin">
          <div class="sperator text-center">
              <p>TOP VENDORS</p>
              <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
          </div>
          <div class="banner-home-3">
            <div class="container">
              <transition name="fade">
              <div class="banner-shortcode-5-content" v-if="featuredVendors.length > 0">
                <div class="row">
                  <div class="col-md-4 col-sm-4" style="height: 400px" v-for="vendor in featuredVendors" :key="vendor.id">
                    <div class="banner-shortcode-wrap style_5">
                      <div class="banner-content">
                        <router-link :to="'/vendors/'+ vendor.slug">
                          <h5 class="banner-title">{{vendor.brand_name}}</h5>
                                  <img width="300" height="400" 
                                    :src="theme.imagePath(vendor.file.path)" 
                                    :alt="vendor.brand_name" 
                                    :title="vendor.brand_name" 
                                    class="attachment-shop_catalog size-shop_catalog" 
                                    v-if="vendor.file"
                                  />
                                  <img width="300" height="400" 
                                    src="/images/logo/logo-black.jpg"
                                    :alt="vendor.brand_name" 
                                    :title="vendor.brand_name" 
                                    class="attachment-shop_catalog size-shop_catalog wp-post-image" v-else 
                                  />
                          <div class="banner-hover">
                            <h5 class="banner-hover-title">{{vendor.brand_name}}</h5>
                            <p class="banner-hover-sub-title">Shop now</p>
                          </div>
                        </router-link>
                      </div>
                    </div>
                  </div> <div class="clearfix"></div>
                </div>
              </div>
              <art-loading v-else></art-loading>
              </transition>
            </div>
          </div>
        </div> -->

        <art-customer-spotlight 
          v-if="reviews.length > 0"
          :products="products"
          :vendors="vendors"
          :reviews="reviews"
        ></art-customer-spotlight>

        <section v-if="discountedProducts.length > 0">
          <div class="dales-this-week">
            <div class="sperator text-center">
              <p>HOT DEALS</p>
              <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
            </div>
            <div class="home-1-deals-this-week yolo-wrap">
              <div class="vc_row wpb_row vc_row-fluid">
                <div class="container">
                  <div class="wpb_column vc_column_container vc_col-sm-12">
                    <div class="vc_column-inner vc_custom_1461828027488">
                      <div class="wpb_wrapper">
                        <div id="shortcode-product-wrap-579a08bd562b2" class="shortcode-product-wrap">
                          <div class="product-wrap">
                            <div class="product-inner clearfix product-style-grid product-paging-none product-col-4">
                              <div class="woocommerce product-listing clearfix columns-4">
                                <div class="product-item-wrap button-has-tooltip product-style_1" v-for="product in discountedProducts" :key="product.id">
                                  <div class="product-item-inner">
                                    <div class="product-thumb white">
                                      <div class="product-flash-wrap"></div>
                                      <div class="product-thumb-primary">
                                        <img width="300" height="400" 
                                          :src="theme.imagePath(product.file[0].path)" 
                                          :alt="product.name" 
                                          :title="product.name" 
                                          class="attachment-shop_catalog size-shop_catalog" 
                                          v-if="product.file.length > 0"
                                        />
                                        <img width="300" height="400" 
                                          src="/images/logo/logo-black.jpg"
                                          :alt="product.name" :title="product.name" class="attachment-shop_catalog size-shop_catalog wp-post-image" v-else />
                                      </div>
                                      <div class="product-thumb-secondary">
                                        <img width="300" height="400" 
                                          :src="theme.imagePath(product.file[0].path)" 
                                          :alt="product.name" 
                                          :title="product.name" 
                                          class="attachment-shop_catalog size-shop_catalog" 
                                          v-if="product.file.length > 0"
                                        />
                                        <img width="300" height="400" 
                                          src="/images/logo/logo-black.jpg"
                                          :alt="product.name" 
                                          :title="product.name" 
                                          class="attachment-shop_catalog size-shop_catalog wp-post-image" 
                                        v-else />
                                      </div>
                                      <a href="#" class="product-link" @click.prevent="singleProduct(product.slug)">
                                        <div class="product-hover-sign">
                                          <hr/>
                                          <hr/>
                                        </div>
                                      </a>
                                      <div class="product-actions">
                                      <div class="yith-wcwl-add-to-wishlist add-to-wishlist-386">
                                        <div class="yith-wcwl-wishlistexistsbrowse show"><span class="feedback">The product is already in the wishlist!</span>
                                        <a href="#" @click.prevent="addToWishList(product)">Browse Wishlist</a></div>
                                      </div>
                                      <div class="add-to-cart-wrap"><a href="#" @click.prevent="addToCart(product)" class="add_to_cart_button"><i class="fa fa-cart-plus"></i> Add to cart</a></div>
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
                                                <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product.price | money}}
                                              </strike> {{product.discount[0].discount * 100}}% off <br>
                                                <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product | discount | money}}
                                            </span>
                                            <span class="woocommerce-Price-amount amount" v-else>
                                                <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product.price| money}}
                                            </span> <br>
                                        </span>
                                        <a href="#" @click.prevent="singleProduct(product.slug)">
                                          <h3>{{product.name}}</h3>
                                        </a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- .home-1-deals-this-week-->
          </div>
          <!-- .home-1-deals-this-week-->
        </section>

        <art-index-blog v-if="posts.length > 0" :posts="posts"></art-index-blog>

		  <art-newsletter></art-newsletter>

    </div>
</template>

<script>
	export default{
    props: ['products', 'categories', 'posts', 'vendors', 'reviews'],
		data(){
			return{
        categoryProducts: [],
        discountedProducts: [],
        ratings: [1, 2, 3, 4, 5],
        productFirstLoad: true,
			}
		},
		methods: {
      singleProduct (slug) {
        var push = '/product/' + slug;
        this.$router.push(push);
      },
      byCategory(id) {
        var $this = {};
        $this.products = this.featuredProducts;
        this.categoryProducts = this.shop.productsByCategory($this, id).slice(0,10);
      },
      discounted () {
        var $this = this;
        this.discountedProducts = this.products.filter(product => $this.shop.checkDiscount(product)).slice(0,8);
      },
      allProducts () {
        if (this.products.length > 0) {
          this.productFirstLoad = false;
          var featuredProducts = this.featuredProducts;
          this.categoryProducts = featuredProducts.slice(0, 10);
        }
      },
      addToCart (product) {
        this.shop.addToCart(this, product, 1);
      },
      addToWishList (product) {
        this.shop.addToWishList(this, product);
        this.$emit('updateWishList');
      },
      byCategories(id, name) {
        var value = {
          id: id,
          name: name
        }
        this.shopProducts = this.shop.productFilter(this, 'category', value, this.take);
        this.$router.push('/shop');
      },
    },
    computed: {
      featuredVendors () {
        return this.vendors.filter(vendor => vendor.display === 'featured');
      },
      featuredProducts () {
        return this.shop.productsByDisplay(this, 4);
      },
    },
    watch: {
      products () {
        this.allProducts();
        this.discounted();
      },
    },
    created () {
      if (this.products) {
        this.allProducts();
        this.discounted();
      }
    },
    mounted () {
      var $this = this
      this.theme.carousel();
			setTimeout(function () {
				$this.$emit('newsLetter');
			}, 3500);
    },
	}
</script>