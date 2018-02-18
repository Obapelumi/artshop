<template>
<header class="header sn-header-style-3">
        <div class="container"> 
          <div class="header-top">
            <div class="container">
              <div class="box-header-top">
                <div class="header-top-left">
                  <aside id="text-2" class="widget widget_text">
                    <div class="textwidget">
                      <i class="fa fa-envelope-o"></i>
                      <a href="mailto:contactus@artshop.com.ng">contactus@artshop.com.ng</a>
                    </div>
                  </aside>
                  <aside id="text-3" class="widget widget_text">
                    <div class="textwidget"><i class="fa fa-phone"></i><a href="tel:+234 (0) 80 5110 6313">+234 (0) 80 5110 6313</a> | <a href="tel:+234 (0) 81 8983 7848">+234 (0) 81 8983 7848</a></div>
                  </aside>
                </div>
                <div class="header-top-right">
                  <div class="header-top-div header-top-search">
                    <router-link to="/checkout" v-if="checkCart">Checkout | </router-link>
                    <router-link to="/register" v-if="!check.auth"> Sign up | </router-link> 
                    <router-link to="/login" v-if="!check.auth"> Log in </router-link> 
                    <router-link to="/dashboard/orders" v-if="auth.checkAdmin()">|       My Account</router-link> <router-link to="/dashboard" v-else-if="check.auth">|       My Account</router-link></div>
                </div>
              </div>
              <div class="header-top-logo"><router-link to="/" title="ARTSHOP"><img src="/images/logo/favicon.png" alt="Artshop" class="logo-img" style="margin-left: 40px;"/></router-link>
                <form @submit.prevent="search">
                  <select v-model="searchItem" class="capitalize">
                    <option selected="selected" value="products">Products</option>
                    <option value="vendors">Vendors</option>
                  </select>
                  <input type="text" v-model="searchValue"/>
                  <button><i class="fa fa-search"></i></button>
                </form>
                <div class="header-right">
                  <div class="header-right-box">
                    <div class="header-customize header-customize-right">
                      <div class="shopping-cart-wrapper header-customize-item no-price style-default">
                        <div class="widget_shopping_cart_content">
                          <router-link to="/cart"><div class="widget_shopping_cart_icon"><i class="wicon fa fa-cart-plus"></i><span class="total" v-if="checkCart">{{cart.totalQty}}</span><span class="total" v-if="!checkCart">0</span></div></router-link>
                          <div class="sub-total-text"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#8358;</span>0.00</span></div>
                          <div class="cart_list_wrapper">
                            <div class="scroll-wrapper cart_list product_list_widget scrollbar-inner" v-if="checkCart">
                              <ul class="cart_list product_list_widget scrollbar-inner scroll-content">
                                <li class="empty" v-for="item in cart.items" :key="item.item.id">
                                  <h4 style="cursor: pointer;" @click="singleProduct(item.item.slug)">
                                    {{item.item.name}} ({{item.qty}})
                                  </h4>
                                  <p>{{item.item.brief_detail}}</p>
                                </li>
                              </ul>
                            </div>
                            <!-- end product list-->
                            <div class="scroll-wrapper cart_list product_list_widget scrollbar-inner" v-else>
                              <ul class="cart_list product_list_widget scrollbar-inner scroll-content">
                                <li class="empty">
                                  <h4>An empty cart</h4>
                                  <p>You have no item in your shopping cart</p>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="my-wishlist header-customize-item">
                        <div class="widget_shopping_wishlist_content">
                          <div class="my-wishlist-wrapper"><a title="Wishlist" href="#" class="yolo-wishlist" @click.prevent="$router.push('/wish-list')"><i class="wicon fa fa-heart-o"></i><span class="total">{{wishListCount}}</span></a></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <a href="#primary-menu"><i class="fa fa-bars"></i></a>

          <div class="header-bottom">
            <div class="main-nav-wrapper header-left">
              <div class="header-logo pull-left">
                <router-link :to="logoPath()" title="ARTSHOP"><img src="/images/logo/favicon.png" alt="logo" class="logo-img" style="height: 50px; width:50px;"/></router-link>
              </div>
              <!-- .header-logo-->

             <art-navbar :categories="categories" :check="check" :checkCart="checkCart" :products="products" ></art-navbar>
              <!-- .header-main-nav-->
            </div>
            <div class="main-nav-wrapper header-right">
              <div class="header-right-box">
                <div class="header-customize header-customize-right">
                      <div class="shopping-cart-wrapper header-customize-item no-price style-default">
                        <div class="widget_shopping_cart_content">
                          <router-link to="/cart"><div class="widget_shopping_cart_icon"><i class="wicon fa fa-cart-plus"></i><span class="total" v-if="checkCart">{{cart.totalQty}}</span><span class="total" v-if="!checkCart">0</span></div></router-link>
                          <div class="sub-total-text"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#8358;</span>0.00</span></div>
                          <div class="cart_list_wrapper">
                            <div class="scroll-wrapper cart_list product_list_widget scrollbar-inner" v-if="checkCart">
                              <ul class="cart_list product_list_widget scrollbar-inner scroll-content">
                                <li class="empty" v-for="item in cart.items" :key="item.item.id">
                                  <h4 style="cursor: pointer;" @click="singleProduct(item.item.slug)">
                                    {{item.item.name}} ({{item.qty}})
                                  </h4>
                                  <p>{{item.item.brief_detail}}</p>
                                </li>
                              </ul>
                            </div>
                            <!-- end product list-->
                            <div class="scroll-wrapper cart_list product_list_widget scrollbar-inner" v-else>
                              <ul class="cart_list product_list_widget scrollbar-inner scroll-content">
                                <li class="empty">
                                  <h4>An empty cart</h4>
                                  <p>You have no item in your shopping cart</p>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="my-wishlist header-customize-item">
                        <div class="widget_shopping_wishlist_content">
                          <div class="my-wishlist-wrapper"><a title="Wishlist" href="#" class="yolo-wishlist" @click.prevent="$router.push('/wish-list')"><i class="wicon fa fa-heart-o"></i><span class="total">{{wishListCount}}</span></a></div>
                        </div>
                      </div>
                    </div>
              </div>
            </div>
          </div>

          <nav data-ps-id="3859a354-888f-fe67-254f-cd059357f1c2" class="yolo-canvas-menu-wrapper dark ps-container"><a href="#" class="yolo-canvas-menu-close"><i class="fa fa-close"></i></a>
            <div class="yolo-canvas-menu-inner sidebar">
              <aside id="text-5" class="widget widget_text">
                <div class="textwidget">
                  <div class="about-us-widget text-center">
                    <div class="about-image"><img src="/images/demo/about-us.png" alt=""/></div>
                    <div class="about-description">Hi, I am John Doe - web designer & blogger. I love design and travel a lot. Explore new places and meet friends. Enjoy my template!</div>
                  </div>
                </div>
              </aside>
              <aside id="yolo-social-profile-2" class="text-center widget widget-social-profile">
                <ul class="social-profile social-icon-bordered">
                  <li><a title="Twitter" href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                  <li><a title="Facebook" href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                  <li><a title="GooglePlus" href="#" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                  <li><a title="Instagram" href="#" target="_blank"><i class="fa fa-instagram"></i></a></li>
                </ul>
              </aside>
              <aside id="null-instagram-feed-5" class="instagram-col-3 padding-2 widget widget null-instagram-feed">
                <h4 class="widget-title"><span>Instagram</span></h4>
                <ul class="intagram">
                  <li><a href="#"><img src="/images/demo/instagram-1.jpg" alt="tag-mega"/></a></li>
                  <li><a href="#"><img src="/images/demo/instagram-2.jpg" alt="tag-mega"/></a></li>
                  <li><a href="#"><img src="/images/demo/instagram-3.jpg" alt="tag-mega"/></a></li>
                  <li><a href="#"><img src="/images/demo/instagram-4.jpg" alt="tag-mega"/></a></li>
                  <li><a href="#"><img src="/images/demo/instagram-5.jpg" alt="tag-mega"/></a></li>
                  <li><a href="#"><img src="/images/demo/instagram-6.jpg" alt="tag-mega"/></a></li>
                </ul>
                <p class="clear"><a href="#" rel="me" target="_blank">Follow Me!</a></p>
              </aside>
            </div>
          </nav>
        </div>
      </header>
</template>

<style>
    header{
      box-shadow: 0px 1px 3px 0px rgba(0, 38, 0, 0.1);
    }
</style>

<script>
	export default{
    props: ['cart', 'checkCart', 'check', 'wishList', 'categories', 'products', 'vendors'],
    data () {
      return {
        category_id: 'Categories',
        searchItem:  'products',
        searchValue: null,
      }
    },
    methods: {
      singleProduct (slug) {
        var push = '/product/' + slug;
        this.$router.push(push);
      },
      search () {
        if (this.searchItem === 'products') {
          var $this = this;
          this.shop.sortingConfig = {
              filter: 'search',
              value: $this.searchValue,
              name: $this.searchValue
          };
          this.$router.push('/shop');
        }
        else if (this.searchItem === 'vendors') {
          var $this = this;
          this.vendor.sortingConfig = {
              filter: 'search',
              value: $this.searchValue,
              name: $this.searchValue
          };
          this.$router.push('/vendors');
        }
      },
      logoPath () {
        if (this.shop.checkCart()) {
          return '/checkout'
        }
        else {
          return '/'
        }
      }
    },
    computed: {
      wishListCount () {
        var $this = this;
        if ($this.wishList !== null) {
          return Object.keys($this.wishList).length;
        }
        else {
          return 0;
        }
      },
    },
    beforeCreate () {
      
    },
	}
</script>