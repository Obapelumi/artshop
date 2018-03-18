<template>
<div id="example-wrapper" class="single-product col-md-10 offset-md-1 col-lg-8 offset-lg-0">
      <section class="product-information">
        <div class="container">
          <div class="row">
            <h2 class="product_title">{{product.name}} by {{vendorName}}</h2><br>
            <div class="col-md-8">
              <div id="sync1" class="owl-carousel owl-template" v-if="product.file.length > 0">
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
            <div class="col-md-8" style="margin-top: 40px;">
              <div class="summary-product entry-summary">
                <div class="woocommerce-product-rating"></div>
                <div class="rate-price">
                  <p class="price"><span>&#8358;{{product.price | money}}</span></p>
                </div>
                <div class="product-single-short-description">
                  <p>{{product.brief_detail}}</p>
                </div>
                <div class="product_meta"><span class="sku_wrapper">
                    <label>Vendor:</label><span class="sku">{{vendorName}}</span>.</span><span class="product-stock-status-wrapper">
                    <label>Availability:</label><span class="product-stock-status in-stock">{{product.stock}} left</span></span><span class="posted_in">
                    <label>Category:</label><a href="#">{{categoryName}}</a>.</span>
               
                </div>
                <div class="social-share-wrap">
                  <label>Tags:</label>
                  <ul class="social-share">
                    <li><a href="#" v-for="tag in product.tag" :key="tag.id">{{tag.name}}</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="row col-md-8 product-single-tab" style="margin-top: 20px;">
              <div class="col-md-2"></div>
              <div class="col-md-8">
                <ul class="nav nav-pills">
                  <li class="active"><a href="#1a" data-toggle="tab">Description</a></li>
                  <li><a href="#2a" data-toggle="tab">ACTIONS</a></li>
                </ul>
                <div class="desc-review-content tab-content clearfix">
                  <div id="1a" class="tab-pane active">
                    <p>{{product.full_detail}}</p>
                  </div>
                  <div id="2a" class="tab-pane">
                    <div id="reviews" class="woocommerce-Reviews">
                      <div id="review_form_wrapper">
                        <div id="review_form">
                          <div id="respond" class="comment-respond">
                            <h3 id="reply-title" class="comment-reply-title">Send a remark to {{vendorName}} about {{product.name}} </h3> <br>
                            <form class="comment-form" @submit.prevent="action">
                              <input type="hidden" v-model="email" readonly>
                              <p class="comment-form-comment">
                                <label>Remark <span class="required">*</span></label>
                                <textarea id="comment" name="comment" cols="45" rows="3" v-model="remark"></textarea>
                              </p>
                              <p class="comment-form-comment">
                                
                              </p>
                              <div class="comment-fields-wrap">
                                <div class="comment-fields-inner clearfix">
                                  <p class="form-submit">
                                    <label>Action <abbr title="required" class="required">*</abbr></label>
                                    <select v-model="display" type="number" class="capitalize" required>
                                        <option v-if="product.display != 1" value="1">Reject</option>
                                        <option v-if="product.display != 2" value="2">Archive</option>
                                        <option v-if="product.display != 3" value="3">Approve</option>
                                        <option v-if="product.display != 4" value="4">Feature</option>
                                    </select>
                                    <!-- <button class="btn btn-transparent reject" @click.prevent="action(1)" v-if="product.display <= 3"> Reject</button>
                                    <button class="btn btn-transparent reject" @click.prevent="action(2)" v-if="product.display >= 3"> Archive</button>
                                    <button class="btn btn-transparent" @click.prevent="action(3)" v-if="product.display < 3"> Approve</button> -->
                                    <button type="submit" class="btn btn-transparent"> Confirm</button>
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
</div>
</template>

<script>
export default{
  props: ['products', 'user'],
	data(){
		return{
			product: {},
			vendorName: 'Artshop',
			categoryName: '',
      remark: null,
      email: this.user.email,
      display: null,
		}
	},
	methods: {
    setProduct () {
      var $this = this;
      this.product = this.shop.singleProduct(this)[0];
      this.categoryName = this.product.category.name;
      $this.vendorName = this.product.vendor.brand_name;
    },
    action () {
      var $this = this;
      var data = {
        display: $this.display,
      }
      $this.theme.submitting();
      $this.axios.put('product/' + $this.product.slug, data)
           .then(response => {
              if ($this.remark != null) {
                var data = {
                  email: $this.email,
                  product_id: $this.product.id,
                  review: $this.remark,
                };

                $this.axios.post('review', data)
                  .then(response => {
                    $this.theme.submitted();
                    $this.$emit('updateProduct');
                    var message = 'Product ' + $this.shop.productStatus($this.display);
                    $this.theme.smoke('success', message, 3000);
                    $this.$router.push('/dashboard/approve-products');
                  });
              }
              else {
                $this.theme.submitted();
                $this.$emit('updateProduct');
                var message = 'Product ' + $this.shop.productStatus($this.display);
                $this.theme.smoke('success', message, 3000);
                $this.$router.push('/dashboard/approve-products');
              }
           })
           .catch(response => {
             $this.theme.submitted();
             $this.theme.smoke('error', 'Connection problem please try again');
           })
    },
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
    if (this.products) {
      this.setProduct();
    }
  },
}
</script>

<style>
</style>