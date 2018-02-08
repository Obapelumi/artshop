<template>
 <nav id="primary-menu" class="main-nav" v-if="theme.checkWidth()">
                <ul class="nav">
                  <li class="active menu-item menu-home"><router-link to="/">Home</router-link></li>
                  <li class="menu-item menu-blog"><router-link to="/shop">SHOP</router-link>
                    <ul class="sub-menu">
                      <li><router-link to="/shop">Find Products</router-link></li>
                      <li v-if="shop.checkWishList()"><router-link to="/wish-list">Wish List</router-link></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog" v-if="shop.checkCart()"><router-link to="/checkout">CHECK OUT</router-link>
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
                      <li v-if="auth.checkVendor()"><router-link to="/dashboard/create-product">Create Product</router-link></li>
                      <li v-if="!auth.checkVendor()"><router-link to="/register-vendor">Become a vendor</router-link></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog"><router-link to="/blog">Blog</router-link></li>
                  <li class="menu-item menu-blog" v-if="auth.checkAuth()">
                    <router-link to="/dashboard/orders" v-if="auth.checkAdmin()">My Account</router-link> <router-link to="/dashboard" v-else-if="auth.checkAuth()">My Account</router-link>
                  <ul class="sub-menu">
                      <li v-if="auth.checkVendor()"><router-link to="/dashboard/create-product">Create Product</router-link></li>
                      <li v-if="auth.checkVendor()"><router-link to="/dashboard/my-products">My Products</router-link></li>
                      <li v-if="!auth.checkVendor()"><router-link to="/register-vendor">Become a vendor</router-link></li>
                      <li v-if="auth.checkAuth()"><a href="#" @click.prevent="signout()">Log Out</a> </li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog" v-else><router-link to="/login">Login</router-link></li>
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
                <ul class="header-customize-item header-social-profile-wrapper" v-if="!shop.checkCart()">
                  <li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="#" target="_blank"><i class="fa fa-instagram"></i></a></li>
                  <li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                </ul>
              </nav>
              <nav id="primary-menu" class="main-nav" v-else>
                <ul class="nav">
                  <li class="active menu-item menu-home"><a href="/#/">Home</a></li>
                  <li class="menu-item menu-blog"><a href="/#/shop">SHOP</a>
                    <ul class="sub-menu">
                      <li><a href="/#/shop">Find Products</a></li>
                      <li v-if="shop.checkWishList()"><a href="/#/wish-list">Wish List</a></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog" v-if="shop.checkCart()"><a href="/#/checkout">CHECK OUT</a>
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
                      <li v-if="auth.checkVendor()"><a href="/#/dashboard/create-product">Create Product</a></li>
                      <li v-if="!auth.checkVendor()"><a href="/#/register-vendor">Become a vendor</a></li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog"><a href="/#/blog">Blog</a></li>
                  <li class="menu-item menu-blog" v-if="auth.checkAuth()">
                    <a href="/#/dashboard/orders" v-if="auth.checkAdmin()">My Account</a> <a href="/#/dashboard" v-else-if="auth.checkAuth()">My Account</a>
                  <ul class="sub-menu">
                      <li v-if="auth.checkVendor()"><a href="/#/dashboard/create-product">Create Product</a></li>
                      <li v-if="auth.checkVendor()"><a href="/#/dashboard/my-products">My Products</a></li>
                      <li v-if="!auth.checkVendor()"><a href="/#/register-vendor">Become a vendor</a></li>
                      <li v-if="auth.checkAuth()"><a href="/#/logout">Log Out</a> </li>
                    </ul>
                  </li>
                  <li class="menu-item menu-blog" v-else><a href="/#/login">Login</a></li>
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
    props: ['categories', 'products'],
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
    mounted () {
      
    }
	}
</script>
