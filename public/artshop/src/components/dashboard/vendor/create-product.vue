<template>
  <form class="checkout" id="create-product" @submit.prevent="submitProduct">
            <div id="customer_details" class="col2-set row">
              <div class="col-1 col-md-6">
                <div class="woocommerce-billing-fields">
                  <h3>Create Product</h3>
                  <p>
                    <label>Product Name <abbr title="required" class="required">*</abbr></label>
                    <input type="text" v-model="product.name" required>
                  </p>
                  <p>
                    <label>Sub Text <abbr title="required" class="required">*</abbr></label>
                    <input type="text" v-model="product.brief_detail" required>
                  </p>
                  <p id="order_comments_field" class="notes woocommerce-validated">
                    <label>Description <abbr title="required" class="required">*</abbr></label>
                    <textarea id="order_comments" name="full_detail" placeholder="Full Description" rows="6" cols="5" v-model="product.full_detail" required></textarea>
                  </p>
                  <div class="row">
                    <div class="col-md-6">
                      <p>
                        <label>Category <abbr title="required" class="required">*</abbr></label>
                        <select class="country_to_state country_select capitalize" v-model="product.category_id" required>
                          <option v-for="category in categories" :value="category.id" :key="category.id">{{category.name}}</option>
                        </select>
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>Price (Naira) <abbr title="required" class="required">*</abbr></label>
                        <input type="number" v-model="product.price" required>
                      </p>
                    </div>
                  </div>
                  <p>
                    <label>Images <abbr title="You can upload up to ten images of your work" class="required">?</abbr></label>
                      <art-image-upload 
                        v-for="index in product.images" 
                        :meta="meta" 
                        :index="index" 
                        :key="index"
                        :upload="uploadImage">
                      </art-image-upload>
                  </p>
                  <div class="col-md-4 col-sm-6 add-more" @click="addMoreImages">+</div>
                  <p>
                    <label>Stock <abbr title="required" class="required">*</abbr></label>
                    <input type="number" v-model="product.stock" name="stock" required/>
                  </p>
                  <div>
                    <p>
                        <label>Size Type <abbr title="required" class="required">*</abbr></label>
                        <select id="billing_country" class="country_to_state country_select" v-model="product.size_type">
                          <option value="varying">Varying Sizes</option>
                          <option value="fixed">Fixed Size</option>
                          <option value="fixedDimensions">Fixed Size With Dimensions</option>
                        </select>
                      </p>
                  </div> <div class="clearfix"></div>
                  <h4 v-if="product.size_type === 'fixedDimensions'">DIMENSIONS</h4>
                  <div class="row" v-if="product.size_type === 'fixedDimensions'">
                    <div class="col-md-6">
                      <p>
                        <label>Length  <small>(in metres)</small></label>
                        <input type="number" v-model="product.length"/>
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>Breadth  <small>(in metres)</small></label>
                        <input type="number" v-model="product.breadth"/>
                      </p>
                    </div>
                  </div>
                  <div class="row" v-if="product.size_type === 'fixedDimensions'">
                    <div class="col-md-6">
                      <p>
                        <label>Height  <small>(in metres)</small></label>
                        <input type="number" v-model="product.height"/>
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>Weight  <small>(in kilograms)</small></label>
                        <input type="number" v-model="product.weight"/>
                      </p>
                    </div>
                  </div>
                  <div class="row sizes" v-if="product.size_type === 'varying'">
                    <p class="col-md-3 col-sm-4 col-xs-6">
                      <label for="small">Extra Small</label>
                      <input type="checkbox" name="small" value="extra small" v-model="product.sizes">
                    </p>
                    <p class="col-md-3 col-sm-4 col-xs-6">
                      <label for="small"> Small</label>
                      <input type="checkbox" name="small" value="small" v-model="product.sizes">
                    </p>
                    <p class="col-md-3 col-sm-4 col-xs-6">
                      <label for="medium"> Medium</label>
                      <input type="checkbox" name="medium" value="medium" v-model="product.sizes">
                    </p>
                    <p class="col-md-3 col-sm-4 col-xs-6">
                      <label for="medium"> Large</label>
                      <input type="checkbox" name="large" value="large" v-model="product.sizes">
                    </p>
                    <p class="col-md-3 col-sm-4 col-xs-6">
                      <label for="small">Extra Large</label>
                      <input type="checkbox" name="small" value="extra large" v-model="product.sizes">
                    </p>
                  </div>
                  <p>
                    <label>Other information <abbr title="required" class="required">*</abbr></label>
                    <textarea id="order_comments" name="order_comments" placeholder="Other information" rows="6" cols="5" v-model="product.other_info"></textarea>
                  </p>
                    <div class="clear"></div>
                  </div>
                  <div class="button-cart-right">
                    <a class="btn btn-black" @click.prevent="saveDraft"><abbr title="Images will not be saved">Save as draft</abbr></a>
                    <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 30px;" type="submit"> Submit</button>
                  </div>
                </div>
              </div>
          </form>
</template>

<script>
  export default {
      props: ['user', 'categories', 'vendors'],
      data(){
        return{
          uploadImage: false,
          meta: {
            image: {
              product_id: 0,
              slug: '',
            },
          },
          product: {
            user_id: this.user.id,
            name: '',
            images: [1,],
            brief_detail: '',
            category_id: 0,
            full_detail: '',
            price: 0,
            stock: 0,
            size_type: 'fixed',
            sizes: [],
            length: 0,
            breadth: 0,
            height: 0,
            weight: 0,
            display: 0,
            handling: 0,
            other_info:'',
            tags: [],
          },
          // timer: '',
        }
      },
      methods: {
        addMoreImages () {
          var $this = this;
          if ($this.product.images.length < 10) {
            this.product.images.push($this.product.images.length + 1);
          }
          else {
            this.theme.smoke('info', 'Upload limit of 10 images reached', 4000);
          }
        },
        submitProduct() {
          var $this = this;
          this.$validator.validateAll().then(() => {
            $this.vendor.createProduct($this);
          });
        },
        saveDraft () {
          this.vendor.saveDraft(this, '/dashboard');
        },
      },
      created () {
        console.log('created')
        var $this = this;
        var draft = $this.vendor.getDraft($this)

        if (draft != null) {
          $this.product = draft;
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
    top: -10px;
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
  .sizes label {
    text-align: center;
  }
</style>