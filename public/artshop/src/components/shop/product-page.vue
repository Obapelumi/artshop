<template>
<div id="example-wrapper" class="single-product has-header">
      <art-page-header :page="'shop'" :subPage="product.name" :metaTags="metaTags"></art-page-header>
      <section class="product-information">
        <div class="container">
          <div class="row">
            <div class="col-md-6" v-if="product.file.length > 0">
              <div id="sync1" class="owl-carousel owl-template">
                <div class="item" v-for="image in product.file" :key="image.id">
                  <figure><img :src="theme.imagePath(image.path)" alt="slide" width="540" height="600"/></figure>
                </div>
              </div>
              <div id="sync2" class="owl-carousel owl-template" v-if="product.file.length > 1">
                <div class="item" v-for="image in product.file" :key="image.id">
                  <figure><img :src="theme.imagePath(image.path)" alt="slide" width="180" height="220"/></figure>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="summary-product entry-summary">
                <h1 class="product_title">{{product.name}}</h1>
                <div class="woocommerce-product-rating"></div>
                <div class="rate-price">
                  <div class="rate" v-if="product.review.length > 0">
                    <span v-for="rating in ratings" :key="rating">
                      <i class="fa fa-star" v-if="shop.productRating(product) >= rating"></i>
                      <i class="fa fa-star-o" v-else></i>
                    </span>
                  </div>
                  <a href="#" class="woocommerce-review-link" v-if="product.review.length > 0">({{product.review.length}} review(s))</a>
                  <p class="price">
                    <span v-if="shop.checkDiscount(product)">
                      <strike>&#8358;{{product.price | money}}</strike> 
                       &#8358;{{product | discount | money}} 
                      <a href=""><small class="woocommerce-review-link">{{product.discount[0].discount * 100}}% off</small></a>
                    </span>
                    <span v-else>&#8358;{{product.price | money}}</span>
                  </p>
                </div>
                <div class="product-single-short-description">
                  <p>{{product.brief_detail}}</p>
                </div>
                <form method="post" enctype="multipart/form-data" class="cart" @submit.prevent="addToCart">
                  <div class="quantity">
                    <div class="quantity-inner">
                      <input v-if="shop.checkStock(product)" step="1" min="1" name="quantity" v-model="qty" value="1" title="Qty" size="4" type="number" :max="product.stock" class="input-text qty text"/>
                      <select class="input-text qty text" v-if="product.meta" v-model="size">
                        <option v-if="product.meta.size_type !== 'varying'" value="Fixed SIze">Fixed Size</option>
                        <option v-else v-for="size in product.meta.sizes" :key="size" :value="size">{{size}}</option>
                      </select>
                    </div>
                  </div>
                  <button v-if="shop.checkStock(product)" type="submit" class="single_add_to_cart_button button alt" style="margin-bottom: 10px;"> Add to cart</button>
                  <button class="single_add_to_cart_button button alt" @click.prevent="addToWishList()" > Add to Wish List</button>
                </form>
                <form method="post" v-if="isMyProduct()" enctype="multipart/form-data" class="cart" @submit.prevent="updateStock">
                  <div class="quantity">
                    <div class="quantity-inner">
                      <input step="1" name="quantity" v-model="product.stock" title="Stock" size="4" type="number"  class="input-text qty text"/>
                    </div>
                  </div>
                  <button type="submit" class="single_add_to_cart_button button alt"> UPDATE STOCK</button>
                </form>
                <div class="product_meta">
                  <span class="sku_wrapper">
                    <label>Vendor:</label>
                    <span class="sku">{{vendorName}}</span>.
                  </span>
                  <span class="product-stock-status-wrapper">
                    <label>Quantity Sold:</label>
                    <span class="sku">{{product.quantity_sold}}</span>
                  </span>
                  <span class="product-stock-status-wrapper">
                    <label>Availability:</label>
                    <span class="product-stock-status in-stock">{{product.stock}} left</span>
                  </span>
                  <span class="posted_in">
                    <label>Category:</label>
                    <a href="#"  @click.prevent="byCategory(categoryId, categoryName)" class="capitalize">{{categoryName}}</a>.
                  </span>
                </div>
                <div class="product_meta" v-if="product.meta">
                  <span class="sku_wrapper" v-if="product.meta.length > 0">
                    <label>Length:</label>
                    <span class="sku">{{product.meta.length}} metres</span>.
                  </span>
                  <span class="product-stock-status-wrapper" v-if="product.meta.breadth > 0">
                    <label>Breadth:</label>
                    <span class="sku">{{product.meta.breadth}} metres</span>.
                  </span>
                  <span class="product-stock-status-wrapper" v-if="product.meta.height > 0">
                    <label>Height:</label>
                    <span class="product-stock-status in-stock">{{product.meta.height}} metres</span>
                  </span>
                  <span class="product-stock-status-wrapper" v-if="product.meta.weight > 0">
                    <label>Weight:</label>
                    <span class="product-stock-status in-stock">{{product.meta.weight}} kilograms</span>
                  </span>
                </div>
                <div class="social-share-wrap" v-if="product.tag.length > 0">
                  <label>Tags:</label>
                  <ul class="social-share">
                    <li><a href="#" v-for="tag in product.tag" :key="tag.id">{{tag.name}}</a></li>
                  </ul>
                </div>
                <div class="social-share-wrap">
                  <label>Share:</label>
                  <ul class="social-share">
                    <li><a :href="'http://www.facebook.com/sharer.php?u=' + theme.config.SITE_URL + '/product/' + product.slug" target="_blank"><i class="fa fa-facebook"></i>facebook</a></li>
                    <li><a :href="'https://twitter.com/share?url=' + theme.config.SITE_URL + '/product/' + product.slug + '&amp;text=' + product.name" target="_blank"><i class="fa fa-twitter"></i>twitter</a></li>
                    <li><a :href="'https://plus.google.com/share?url=' + theme.config.SITE_URL + '/product/' + product.slug" target="_blank"><i class="fa fa-google-plus"></i>google</a></li>
                    <!-- <li><a href="#"><i class="fa fa-linkedin"></i>linkedi</a></li>
                    <li><a href="#"><i class="fa fa-tumblr"></i>tumblr</a></li>
                    <li><a href="#"><i class="fa fa-pinterest"></i>pinterest</a></li> -->
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section>
        <div class="container">
          <div class="product-single-tab">
            <div class="row">
              <div class="col-md-2"></div>
              <div class="col-md-8">
                <ul class="nav nav-pills">
                  <li class="active"><a href="#1a" data-toggle="tab">Description</a></li>
                  <li><a href="#2a" data-toggle="tab">Reviews ({{product.review.length}})</a></li>
                </ul>
                <div class="desc-review-content tab-content clearfix">
                  <div id="1a" class="tab-pane active">
                    <p>{{product.full_detail}}</p>
                  </div>
                  <div id="2a" class="tab-pane">
                    <div id="reviews" class="woocommerce-Reviews">
                      <div id="comments" v-if="product.review.length > 0">
                        <h2 class="woocommerce-Reviews-title">{{product.review.length}} review(s) for <span>{{product.name}}</span></h2>
                        <ol class="commentlist">
                          <li class="comment" v-for="review in product.review" :key="review.id">
                            <div class="comment_container">
                              <!-- <img alt="avatar" src="/images/demo/avatar.png" width="60" height="60" class="avatar"/> -->
                              <div class="comment-text">
                                <div class="rate">
                                  <span v-for="rating in ratings" :key="rating">
                                    <i class="fa fa-star" v-if="shop.productRating(product) >= rating"></i>
                                    <i class="fa fa-star-o" v-else></i>
                                  </span>
                                </div>
                                <p class="meta"><strong>{{review.name}}</strong>
                                  <time>{{review.created_at | moment("from")}}</time>
                                </p> <div class="clearfix"></div>
                                <div class="description">
                                  <p>{{review.review}}</p>
                                </div>
                              </div>
                            </div>
                          </li>
                        </ol>
                      </div>
                      <div id="review_form_wrapper">
                        <div id="review_form">
                          <div id="respond" class="comment-respond">
                            <h3 id="reply-title" class="comment-reply-title">Add a review </h3>
                            <form class="comment-form" @submit.prevent="submitReview">
                              <div class="comment-form-rating">
                                <label>Your Rating</label>
                                <div class="rate">
                                  <span 
                                    v-for="voter in review.votes" 
                                    style="cursor: pointer;"
                                    :key="voter"
                                    @click="review.vote = voter"
                                  >
                                    <i class="fa fa-star" v-if="review.vote >= voter"></i>
                                    <i class="fa fa-star-o" v-else></i>
                                  </span>
                                </div>
                              </div>
                              <p class="comment-form-comment">
                                <textarea id="comment" name="comment" cols="45" rows="3" required="" v-model="review.review"></textarea>
                              </p>
                              <div class="comment-fields-wrap">
                                <div class="comment-fields-inner clearfix">
                                  <p class="comment-form-email">
                                    <label>Email <span class="required">*</span></label>
                                    <input id="email" name="email" value="" size="30" required="" type="email" v-model="review.email"/>
                                  </p>
                                  <p class="form-submit">
                                    <input id="submit" name="submit" value="Submit" type="submit" class="submit"/>
                                  </p>
                                </div>
                              </div>
                            </form>
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
      </section>
      <art-related-products :products="relatedProducts"></art-related-products>
</div>
</template>

<script>
import RelatedProducts from './related-products.vue';
export default{
  props: ['products',],
	data(){
		return{
			product: {},
			relatedProducts: [],
      qty: 1,	
      size: 'medium',
			vendorName: '',
      categoryName: '',
      categoryId: '',
      review: {
        email: '',
        review: '',
        vote: 0,
        votes: [1, 2, 3, 4, 5],
      },
      ratings: [1, 2, 3, 4, 5],
      metaTags: null,
		}
	},
	methods: {
      byCategory(id, name) {
        var value = {
          id: id,
          name: name
        }
        this.shopProducts = this.shop.productFilter(this, 'category', value, this.take);
        this.$router.push('/shop');
      },
		addToCart () {
			this.shop.addToCart(this, this.product, parseInt(this.qty), this.size);
    },
    addToWishList () {
      this.shop.addToWishList(this, this.product);
      this.$emit('updateWishList');
    },
    setProduct () {
      var $this = this;
      var products = this.shop.singleProduct(this);
      if (products.length > 0) {
        this.product = products[0];
        this.setMetaTags();
      }
      else {
        this.$router.push('/not-found');
      }
      
      this.categoryName = this.product.category.name;
      this.categoryId = this.product.category.id;
      this.relatedProducts = this.shop.productsByCategory(this, this.product.category_id).slice(0,20) 
      $this.vendorName = this.product.vendor.brand_name;
    },
    isMyProduct () {
      return this.vendor.isMyProduct(this)
    },
    updateStock () {
      if (this.isMyProduct()) {
        this.vendor.updateStock(this);
      }
    },
    submitReview () {
      var $this = this;
      $this.review.product_id = $this.product.id;
      $this.theme.submitting();
      $this.axios.post('review', $this.review)
        .then(response => {
          $this.theme.submitted();
          $this.theme.smoke('success', 'Review Submitted', 2000);
          $this.$emit('updateProduct');
        })
        .catch(response => {
          $this.theme.smoke('error', 'there was an error please try again', 5000);
        });

    },
    setMetaTags () {
      var $this = this;
      $this.metaTags = {
        type: 'website',
        title: $this.product.name + ' by ' + $this.product.vendor.brand_name,
        description: $this.product.brief_detail.slice(0, 100) + '...',
      }

      if ($this.product.file.length > 0) {
        $this.metaTags.image = $this.theme.imagePath($this.product.file[0].path);
      }
    }
  },
  watch: {
    products () {
      this.setProduct();
    },
  },
  mounted () {
    this.theme.carousel();
    this.theme.carousel2();
  },
  created () {
    console.log('created');
    if (this.products) {
      this.setProduct();
    }
  },
  components: {
    'art-related-products': RelatedProducts,
  }
}
</script>

<style>
	
</style>