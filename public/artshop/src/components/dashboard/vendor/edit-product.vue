<template>
  <form class="checkout" id="create-product" @submit.prevent="createProduct">
            <div id="customer_details" class="col2-set row">
              <div class="col-1 col-md-6">
                <div class="woocommerce-billing-fields">
                  <h3>Edit Product</h3>
                  <p>
                    <label>Product Name <abbr title="required" class="required">*</abbr></label>
                    <input type="text" v-model="product.name" :readonly="product.display > 2" />
                  </p>
                  <p>
                    <label>Sub Text <abbr title="required" class="required">*</abbr></label>
                    <input type="text" v-model="product.brief_detail"/>
                  </p>
                  <p id="order_comments_field" class="notes woocommerce-validated">
                    <label>Description <abbr title="required" class="required">*</abbr></label>
                    <textarea id="order_comments" name="order_comments" placeholder="Full Description" rows="6" cols="5" v-model="product.full_detail"></textarea>
                  </p>
                  <div class="row">
                    <div class="col-md-6">
                      <p>
                        <label>Category <abbr title="required" class="required">*</abbr></label>
                        <select id="billing_country" class="country_to_state country_select" v-model="product.category_id" readonly>
                          <option v-for="category in categories" :value="category.id" :key="category.id">{{category.name}}</option>
                        </select>
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label v-if="price > 0">&#8358;{{price * 100 | money}}</label>
                        <label v-else>Price (Naira) <abbr title="required" class="required">*</abbr> </label>
                        <input type="number" v-model="price" :readonly="product.display > 2"/>
                      </p>
                    </div>
                  </div>
                  <p>
                    <label>Images <abbr title="You can upload up to ten images of your work" class="required">?</abbr></label>
                      <art-image-update v-for="image in product.file" :file="image" :meta="meta" :index="image.id" :key="image.id"></art-image-update>
                  </p>
                  <div class="col-md-4 col-sm-6 add-more" @click="addMoreImages">+</div>
                  <p>
                    <label>How Many? <abbr title="required" class="required">*</abbr></label>
                    <input type="number" v-model="product.stock"/>
                  </p>
                  <div v-if="product.meta">
                  <h4 v-if="product.meta.size_type === 'fixedDimensions'">DIMENSIONS</h4>
                  <div class="row" v-if="product.meta.size_type === 'fixedDimensions'">
                    <div class="col-md-6">
                      <p>
                        <label>Length  <small>(in metres)</small></label>
                        <input type="number" v-model="product.meta.length"/>
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>Breadth  <small>(in metres)</small></label>
                        <input type="number" v-model="product.meta.breadth"/>
                      </p>
                    </div>
                  </div>
                  <div class="row" v-if="product.meta.size_type === 'fixedDimensions'">
                    <div class="col-md-6">
                      <p>
                        <label>Height  <small>(in metres)</small></label>
                        <input type="number" v-model="product.meta.height"/>
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>Weight  <small>(in kilograms)</small></label>
                        <input type="number" v-model="product.meta.weight"/>
                      </p>
                    </div>
                  </div>
                  <div class="row sizes" v-if="product.meta.size_type === 'varying'">
                    <p class="col-md-3 col-sm-4 col-xs-6">
                      <label for="small">Extra Small</label>
                      <input type="checkbox" name="small" value="extra small" v-model="product.meta.sizes">
                    </p>
                    <p class="col-md-3 col-sm-4 col-xs-6">
                      <label for="small"> Small</label>
                      <input type="checkbox" name="small" value="small" v-model="product.meta.sizes">
                    </p>
                    <p class="col-md-3 col-sm-4 col-xs-6">
                      <label for="medium"> Medium</label>
                      <input type="checkbox" name="medium" value="medium" v-model="product.meta.sizes">
                    </p>
                    <p class="col-md-3 col-sm-4 col-xs-6">
                      <label for="medium"> Large</label>
                      <input type="checkbox" name="large" value="large" v-model="product.meta.sizes">
                    </p>
                    <p class="col-md-3 col-sm-4 col-xs-6">
                      <label for="small">Extra Large</label>
                      <input type="checkbox" name="small" value="extra large" v-model="product.meta.sizes">
                    </p>
                  </div>
                  <p>
                    <label>Other information <abbr title="required" class="required">*</abbr></label>
                    <textarea id="order_comments" name="order_comments" placeholder="Other information" rows="6" cols="5" v-model="product.meta.other_info"></textarea>
                  </p>
                  </div>
                    <div class="clear"></div>
                  </div>
                  <div class="button-cart-right">
                    <button class="btn btn-transparent" @click.prevent="updateProduct" style="margin-left: 40%; margin-top: 30px;" type="submit"> Update</button>
                  </div>
                </div>
              </div>
          </form>
</template>

<script>
  export default {
      props: ['user', 'categories', 'products',],
      data(){
        return{
          meta: {
            image: {
              product_id: 0,
              slug: '',
            },
          },
          product: {},
          productMeta: {
            size_type: 'fixed',
            sizes: [],
          },
          price: {},
        }
      },
      watch: {
        products () {
          this.setProduct();
          this.setPrice();
          this.setMeta();
        },
      },
      methods: {
        addMoreImages () {
          var $this = this;
          if ($this.product.file.length < 10) {
            var imageId = new Date().getUTCMilliseconds();
            var image = {
              id: imageId,
              path: '/images/logo/favicon.png',
              isfake: true,
            }
            $this.product.file.push(image);
          }
        },
        updateProduct() {
          this.product = Object.assign(this.product, this.product.meta);
          this.vendor.updateProduct(this);
        },
        saveDraft () {
          this.vendor.saveDraft(this, '/dashboard');
        },
        setProduct () {
          var $this = this;
          this.product = this.shop.singleProduct(this)[0];
        },
        setPrice () {
          this.price = parseInt(this.product.price)/100;
        },
        setMeta () {
          this.meta.image.product_id = this.product.id;
          this.meta.image.slug = this.product.slug;
        }
      },
      computed: {

      },
      created () {
        var $this = this;
        if (this.products) {
          this.setProduct();
          this.setPrice();
          this.setMeta();
        } 
      },
  }
</script>

<style>
  #create-product {
    left: 500px !important;
  }
  .add-more {
    outline: 2px dashed grey; /* the dash box */
    outline-offset: -10px;
    /*background: lightcyan;*/
    color: dimgray;
    padding: 10px 10px;
    height: 180px; /* minimum height */
    position: relative;
    cursor: pointer;
    border-radius: 5px;
    margin-bottom: 30px;
    font-size: 100px;
    text-align: center;
  }
  .add-more:hover {
    background: rgba(196, 214, 176, 0.2); /* when mouse over to the drop zone, change color */
  }
  .btn abbr {
    text-decoration: none;
  }
</style>