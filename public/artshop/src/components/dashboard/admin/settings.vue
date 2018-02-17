<template>
    <div>
	    <form class="checkout" id="create-product" @submit.prevent="register">
            <div id="customer_details" class="col2-set row">
              <div class="col-1 col-md-6">
                <div class="woocommerce-billing-fields col-lg-6 col-md-6" style="margin-bottom:80px;">
                    <h3>CATEGORIES</h3>
                    <aside class="sidebar-blog categories" style="text-transform: uppercase; margin-top: 0px;">
                        <ul class="categories">
                        <li v-for="category in categories" :key="category.id"><a href="#">{{category.name}}</a></li>
                        
                        </ul>
                    </aside>
                </div>
                <div class="woocommerce-billing-fields col-lg-6 col-md-6" style="margin-bottom:80px;">
                  <h3>TAGS</h3>  
                      <aside class="sidebar-product-style-2 tagcloud">
                        <ul class="tagcloud">
                          <li v-for="tag in tags" :key="tag.id"><a href="#">{{tag.name}}</a></li>
                        </ul>
                      </aside>
                </div>
                <div class="clearfix"></div>
                <hr>
                <div class="woocommerce-billing-fields">
                  <!-- <h3>FEATURE VENDOR</h3>
                  <div class="row">
                    <form action="" @submit.prevent="featureVendor('featured')">
                        <div class="col-md-5">
                        <p>
                            <label>Vendor <abbr title="required" class="required">*</abbr></label>
                           <select type="text" class="capitalize" v-model="editedVendor" required> 
                                <option v-for="vendor in vendors" :key="vendor.id" :value="vendor">{{vendor.brand_name}}</option>
                            </select>
                        </p>
                        </div>
                        <div class="button-cart-right col-md-2">
                            <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 23px;" type="submit"> FEATURE</button>
                        </div>
                    </form>
                  </div> -->
                  <!-- <h3>REMOVE FEATURED VENDOR</h3>
                  <div class="row">
                    <form action="" @submit.prevent="featureVendor('approved')">
                        <div class="col-md-5">
                        <p>
                            <label>Vendor <abbr title="required" class="required">*</abbr></label>
                           <select type="text" class="capitalize" v-model="editedVendor" required> 
                                <option v-if="vendors" v-for="vendor in featuredVendors" :key="vendor.id" :value="vendor">{{vendor.brand_name}}</option>
                            </select>
                        </p>
                        </div>
                        <div class="button-cart-right col-md-2">
                            <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 23px;" type="submit"> REMOVE</button>
                        </div>
                    </form>
                  </div> -->
                  <h3>ADD CATEGORY</h3>
                  <div class="row">
                    <form action="" @submit.prevent="addCategory">
                        <div class="col-md-5">
                        <p>
                            <label>Name <abbr title="required" class="required">*</abbr></label>
                            <input type="text" v-model="category.name" required>
                        </p>
                        </div>
                        <!-- <div class="col-md-5">
                        <p>
                            <label>Shipping Factor <abbr title="percentage cost charged on each product in this category" class="required">?</abbr></label>
                            <input type="number" v-model="category.shipping_factor">
                        </p>
                        </div> -->
                        <div class="button-cart-right col-md-2" >
                            <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 23px;" type="submit"> Add</button>
                        </div>
                    </form>
                  </div>
                  <h3>DELETE CATEGORY</h3>
                  <div class="row">
                    <form action="" @submit.prevent="deleteCategory">
                        <div class="col-md-5">
                        <p>
                            <label>Category <abbr title="required" class="required">*</abbr></label>
                           <select type="text" class="capitalize" v-model="tag.category_id" required> 
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                            </select>
                        </p>
                        </div>
                        <div class="button-cart-right col-md-2">
                            <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 23px;" type="submit"> DELETE</button>
                        </div>
                    </form>
                  </div>
                  <h3>ADD TAG</h3>
                  <div class="row">
                    <form action="" @submit.prevent="addTag">
                        <div class="col-md-5">
                        <p>
                            <label>Name <abbr title="required" class="required">*</abbr></label>
                            <input type="text" v-model="tag.name">
                        </p>
                        </div>
                        <div class="col-md-5">
                        <p>
                            <label>Category <abbr title="required" class="required">*</abbr></label>
                            <select type="text" v-model="tag.category_id"> 
                                <option value="0">Category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                            </select>
                        </p>
                        </div>
                        <div class="button-cart-right col-md-2">
                            <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 23px;" type="submit"> Add</button>
                        </div>
                    </form>
                  </div>
                  <h3>DELETE TAG</h3>
                  <div class="row">
                    <form action="" @submit.prevent="deleteTag">
                        <div class="col-md-5">
                        <p>
                            <label>Tag <abbr title="required" class="required">*</abbr></label>
                           <select type="text" class="capitalize" v-model="tag_id" required> 
                                <option v-for="tag in tags" :key="tag.id" :value="tag.id">{{tag.name}}</option>
                            </select>
                        </p>
                        </div>
                        <div class="button-cart-right col-md-2">
                            <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 23px;" type="submit"> DELETE</button>
                        </div>
                    </form>
                  </div>
                  <h3>ADD DISCOUNT</h3>
                  <div class="row">
                    <form action="" @submit.prevent="addDiscount">
                        <div class="col-md-5">
                        <p>
                            <label>Percentage % <abbr title="percentage discount on product cost" class="required">?</abbr></label>
                            <input type="number" min="0.01" step="0.01" v-model="discount.discount" required>
                        </p>
                        </div>
                        <div class="col-md-5">
                        <p>
                            <label>Product <abbr title="required" class="required">*</abbr></label>
                            <select type="number" class="capitalize" v-model="discount.product_id" required>
                                <option v-for="product in products" :key="product.id" :value="product.id">{{product.name}}</option>
                            </select>
                        </p>
                        </div>
                        <div class="col-md-5">
                        <p>
                            <label>Period <abbr title="required" class="required">?</abbr></label>
                            <input type="date" :min="theme.config.TODAY()" v-model="discount.period" id="" required>
                        </p>
                        </div>
                        <div class="button-cart-right col-md-2">
                            <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 23px;" type="submit"> Add</button>
                        </div>
                    </form>
                  </div>
                  <h3>DELETE DISCOUNT</h3>
                  <div class="row">
                    <form action="" @submit.prevent="deleteDiscount">
                        <div class="col-md-5">
                        <p>
                            <label>Discount <abbr title="required" class="required">*</abbr></label>
                           <select type="text" class="capitalize" v-model="discount_id" required> 
                                <option v-for="discount in discounts" :key="discount.id" :value="discount.id">{{discount.name}} till {{discount.period | moment("MMMM Do YYYY")}}</option>
                            </select>
                        </p>
                        </div>
                        <div class="button-cart-right col-md-2">
                            <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 23px;" type="submit"> DELETE</button>
                        </div>
                    </form>
                  </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
          </form>
        </div>
</template>

<script>
export default {
    props: ['products', 'categories', 'tags', 'vendors'],
	data () {
		return {
            discounts: [],
			tag: {
                name: '',
                category_id: 0,
            },
            category: {
                name: null,
                shipping_factor: null,
            },
            discount: {
                discount: '',
                period: null,
                product_id: null,
                category_id: null,
            },
            tag_id: null,
            discount_id: null,
            editedVendor: null,
		}
	},
	methods: {
		register () {
            // empty
        },
        addCategory () {
            this.admin.addCategory(this);
        },
        deleteCategory () {
            this.admin.deleteCategory(this, tag.category_id);
        },
        addTag() {
            this.admin.addTag(this);
        },
        deleteTag () {
            this.admin.deleteTag(this, this.tag_id);
        },
        addDiscount() {
            this.admin.addDiscount(this);
        },
        deleteDiscount () {
            this.admin.deleteDiscount(this, this.discount_id);
        },
        featureVendor (display) {
            this.editedVendor.display = display;
            this.vendor.updateVendor(this, '/dashboard/settings');
        }
	},
	computed: {
        featuredVendors () {
            return this.vendors.filter(vendor => vendor.display === 'featured');
        }
    },
    created () {
        this.admin.getDiscounts(this);
    }
}
</script>

<style>
	
</style>