<template>
 <nav id="primary-menu" class="main-nav" v-if="theme.checkWidth()">
                <ul class="nav">
                  <li class="active menu-item menu-home"><router-link to="/">Home</router-link></li>
                  <li class="mega-menu menu-item"><a href="/#/shop">Shop</a>
                    <ul class="sub-menu sub-menu-mega">
                      <li class="sub-menu-mega-item active-menu" v-if="featuredProducts.length > 0 || discountedProducts.length > 0"><a href="#">Products of the Week</a>
                        <ul>
                          <li v-if="featuredProducts.length > 0"><a href="#">Products of the week</a>
                            <ul>
                              <li v-for="product in featuredProducts" :key="product.id">
                                <a href="#">
                                  <img :src="theme.imagePath(product.file[0].path)" 
                                    :alt="product.name" 
                                    :title="product.name"
                                    v-if="product.file.length > 0" 
                                    style="max-width: 85px; max-height: 100px;"
                                  />
                                  <img width="300" height="400" 
                                    src="/images/logo/logo-black.jpg"
                                    :alt="product.name" :title="product.name" class="attachment-shop_catalog size-shop_catalog wp-post-image" v-else
                                    style="max-width: 85px; max-height: 100px;" 
                                  />
                                </a>
                                <p class="mega-right" @click="$router.push('product/' + product.slug)">
                                  <span class="product-title">{{product.name}}</span>
                                  <span class="price">
                                    <ins>
                                      <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product | discount | money}}</span>
                                    </ins>
                                  </span>
                                </p>
                              </li>
                            </ul>
                          </li>
                          <li v-if="discountedProducts.length > 0"><a href="#">Flash Sale</a>
                            <ul>
                              <li v-for="product in discountedProducts" :key="product.id">
                                <a href="#">
                                  <img :src="theme.imagePath(product.file[0].path)" 
                                    :alt="product.name" 
                                    :title="product.name"
                                    v-if="product.file.length > 0" 
                                    style="max-width: 85px; max-height: 100px;"
                                  />
                                  <img width="300" height="400" 
                                    src="/images/logo/logo-black.jpg"
                                    :alt="product.name" :title="product.name" class="attachment-shop_catalog size-shop_catalog wp-post-image" v-else
                                    style="max-width: 85px; max-height: 100px;" 
                                  />
                                </a>
                                <p class="mega-right">
                                  <span class="product-title">{{product.name}}</span>
                                  <span class="price">
                                    <del><span class="woocommerce-Price-amount amount">
                                      <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product.price | money}}</span>
                                    </del>
                                    <ins>
                                      <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product | discount | money}}</span>
                                    </ins>
                                  </span>
                                </p>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li class="sub-menu-mega-item"><router-link to="/shop">All Products</router-link>
                      </li>
                      <li v-if="checkCart"><a href="/#/cart">Cart</a></li>
                      <li v-if="shop.checkWishList()"><router-link to="/wish-list">Wish List</router-link></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog" v-if="checkCart"><router-link to="/checkout">CHECK OUT</router-link>
                    <ul class="sub-menu">
                      <li><router-link to="/checkout">Check Out</router-link></li>
                      <li><router-link to="/cart">Cart</router-link></li>
                    </ul>
                  </li>
                  <li v-if="categories.length > 0" class="menu-item menu-shop"><a href="#" @click.prevent="categories = categories">Categories</a>
                    <ul class="sub-menu">
                      <li class="menu_style_dropdown menu-item"
                        v-for="category in categories" :key="category.id">
                        <a href="#" @click.prevent="byCategory(category.id, category.name)">{{category.name}}</a>
                        <ul class="sub-menu">
                          <li class="menu-item menu-item-object-page" v-for="tag in category.tag" :key="tag.id">
                            <a href="#" @click.prevent="byTag(tag.id, tag.name)">{{tag.name}}</a>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog"><router-link to="/vendors">Vendors</router-link>
                    <ul class="sub-menu">
                      <li><router-link to="/vendors">Our Vendors</router-link></li>
                      <li v-if="check.vendor"><router-link to="/dashboard/create-product">Create Product</router-link></li>
                      <li v-if="!check.vendor"><router-link to="/register-vendor">Become a vendor</router-link></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog"><router-link to="/blog">Blog</router-link></li>
                  <li class="menu-item menu-blog" v-if="check.auth">
                    <router-link to="/dashboard/orders" v-if="check.admin">My Account</router-link> <router-link to="/dashboard" v-else-if="check.auth">My Account</router-link>
                  <ul class="sub-menu">
                      <li v-if="check.vendor"><router-link to="/dashboard/create-product">Create Product</router-link></li>
                      <li v-if="check.vendor"><router-link to="/dashboard/my-products">My Products</router-link></li>
                      <li v-if="!check.vendor"><router-link to="/register-vendor">Become a vendor</router-link></li>
                      <li v-if="check.auth"><a href="#" @click.prevent="signout()">Log Out</a> </li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog" v-else><router-link to="/login">Login</router-link>
                    <ul class="sub-menu">
                      <li><router-link to="/login">LOG IN</router-link></li>
                      <li><router-link to="/register">SIGN UP</router-link></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog"><router-link to="/about">About</router-link>
                    <ul class="sub-menu">
                      <li><router-link to="/about">About Us</router-link></li>
                      <li><router-link to="/faqs">Policy &amp; FAQs</router-link></li>
                    </ul>
                  </li>
                </ul>
                <div class="header-customize-item canvas-menu-toggle-wrapper">
                  <div class="canvas-menu-toggle"><i class="fa fa-bell" v-if="false"></i></div>
                </div>
                <ul class="header-customize-item header-social-profile-wrapper" v-if="!checkCart">
                  <li><a href="http://facebook.com/artshopng" target="_blank"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="http://instagram.com/artshopng" target="_blank"><i class="fa fa-instagram"></i></a></li>
                  <li><a href="http://twitter.com/artshopng" target="_blank"><i class="fa fa-twitter"></i></a></li>
                </ul>
              </nav>
              <nav id="primary-menu" class="main-nav" v-else>
                <ul class="nav">
                  <li class="active menu-item menu-home"><a href="/#/">Home</a></li>
                  <li class="mega-menu menu-item"><a href="/#/shop">Shop</a>
                    <ul class="sub-menu sub-menu-mega">
                      <li class="sub-menu-mega-item active-menu" v-if="featuredProducts.length > 0 || discountedProducts.length > 0"><a href="#">Products of the Week</a>
                        <ul>
                          <li v-if="featuredProducts.length > 0"><a href="#">Products of the week</a>
                            <ul>
                              <li v-for="product in featuredProducts" :key="product.id">
                                <a href="#">
                                  <img :src="theme.imagePath(product.file[0].path)" 
                                    :alt="product.name" 
                                    :title="product.name"
                                    v-if="product.file.length > 0" 
                                    style="max-width: 85px; max-height: 100px;"
                                  />
                                  <img width="300" height="400" 
                                    src="/images/logo/logo-black.jpg"
                                    :alt="product.name" :title="product.name" class="attachment-shop_catalog size-shop_catalog wp-post-image" v-else
                                    style="max-width: 85px; max-height: 100px;" 
                                  />
                                </a>
                                <p class="mega-right" @click="$router.push('product/' + product.slug)">
                                  <span class="product-title">{{product.name}}</span>
                                  <span class="price">
                                    <ins>
                                      <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product | discount | money}}</span>
                                    </ins>
                                  </span>
                                </p>
                              </li>
                            </ul>
                          </li>
                          <li v-if="discountedProducts.length > 0"><a href="#">Flash Sale</a>
                            <ul>
                              <li v-for="product in discountedProducts" :key="product.id">
                                <a href="#">
                                  <img :src="theme.imagePath(product.file[0].path)" 
                                    :alt="product.name" 
                                    :title="product.name"
                                    v-if="product.file.length > 0" 
                                    style="max-width: 85px; max-height: 100px;"
                                  />
                                  <img width="300" height="400" 
                                    src="/images/logo/logo-black.jpg"
                                    :alt="product.name" :title="product.name" class="attachment-shop_catalog size-shop_catalog wp-post-image" v-else
                                    style="max-width: 85px; max-height: 100px;" 
                                  />
                                </a>
                                <p class="mega-right">
                                  <span class="product-title">{{product.name}}</span>
                                  <span class="price">
                                    <del><span class="woocommerce-Price-amount amount">
                                      <span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product.price | money}}</span>
                                    </del>
                                    <ins>
                                      <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#8358;</span>{{product | discount | money}}</span>
                                    </ins>
                                  </span>
                                </p>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li class="sub-menu-mega-item"><a href="/#/shop">All Products</a>
                      </li>
                      <li v-if="checkCart"><a href="/#/cart">Cart</a></li>
                      <li v-if="shop.checkWishList()"><router-link to="/wish-list">Wish List</router-link></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog" v-if="checkCart"><a href="/#/checkout">CHECK OUT</a>
                    <ul class="sub-menu">
                      <li><a href="/#/checkout">Check Out</a></li>
                      <li><a href="/#/cart">Cart</a></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-shop" v-if="categories.length > 0"><a href="#" @click.prevent="categories = categories">Categories</a>
                    <ul class="sub-menu">
                      <li class="menu_style_dropdown menu-item"
                        v-for="category in categories" :key="category.id">
                        <a href="#" @click.prevent="byCategory(category.id, category.name)">{{category.name}}</a>
                        <ul class="sub-menu">
                          <li class="menu-item menu-item-object-page" v-for="tag in category.tag" :key="tag.id">
                            <a href="#" @click.prevent="byTag(tag.id, tag.name)">{{tag.name}}</a>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog"><a href="/#/vendors">Vendors</a>
                    <ul class="sub-menu">
                      <li><a href="/#/vendors">Our Vendors</a></li>
                      <li v-if="check.vendor"><a href="/#/dashboard/create-product">Create Product</a></li>
                      <li v-if="!check.vendor"><a href="/#/register-vendor">Become a vendor</a></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog"><a href="/#/blog">Blog</a></li>
                  <li class="menu-item menu-blog" v-if="check.auth">
                    <a href="/#/dashboard/orders" v-if="check.admin">My Account</a> <a href="/#/dashboard" v-else-if="check.auth">My Account</a>
                  <ul class="sub-menu">
                      <li v-if="check.vendor"><a href="/#/dashboard/create-product">Create Product</a></li>
                      <li v-if="check.vendor"><a href="/#/dashboard/my-products">My Products</a></li>
                      <li v-if="!check.vendor"><a href="/#/register-vendor">Become a vendor</a></li>
                      <li v-if="check.auth"><a href="/#/logout">Log Out</a> </li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog" v-else><a href="/#/login">Login</a>
                    <ul class="sub-menu">
                      <li><a href="/#/login">LOGIN</a></li>
                      <li><a href="/#/register">SIGN UP</a></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog"><a href="/#/about">About</a>
                    <ul class="sub-menu">
                      <li><a href="/#/about">About Us</a></li>
                      <li><a href="/#/faqs">Policy &amp; FAQs</a></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog"><a href="/#/about">Follow Us</a>
                    <ul class="sub-menu">
                      <li><a href="http://facebook.com/artshopng" target="_blank"><i class="fa fa-facebook"></i></a></li>
                      <li><a href="http://instagram.com/artshopng" target="_blank"><i class="fa fa-instagram"></i></a></li>
                      <li><a href="http://twitter.com/artshopng" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    </ul>
                  </li>
                </ul>
                <div class="header-customize-item canvas-menu-toggle-wrapper">
                  <div class="canvas-menu-toggle"><i class="fa fa-bell" v-if="false"></i></div>
                </div>
              </nav>
</template>

<script>
	export default{
    props: ['categories', 'products', 'check', 'checkCart'],
		data(){
			return{
				book: 'book'
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
      byTag(id, name) {
        var value = {
          id: id,
          name: name
        }
        this.shopProducts = this.shop.productFilter(this, 'tag', value, this.take);
        this.$router.push('/shop');
      },
			signout () {
				this.auth.signout(this);
      },
    },
    computed: {
      featuredProducts () {
        return this.shop.productsByDisplay(this, 4);
      },
      discountedProducts () {
        var $this = this;
        return this.products.filter(product => $this.shop.checkDiscount(product)).slice(0,8);
      },
    },
    mounted () {
      
    }
	}
</script>
