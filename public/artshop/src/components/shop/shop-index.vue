<template>
	<div class="shop-index has-header">
<art-page-header :page="'shop'" :subPage="shop.sortingConfig.name"></art-page-header>
		<div class="section">
        <div class="products-in-category-tabs-wrapper container">
          <div class="products-content">
            <div class="woocommerce product-listing columns-4">
              <transition name="slideRight">
                <div class="sidebar-shop-search" v-if="showSearch">
                  <form style="margin-bottom: 20px;" @submit.prevent="search">
                    <input type="text" v-model="searchValue" placeholder="Search product"/>
                    <a href="#" @click.prevent="search"><i class="fa fa-search"></i></a>
                  </form>
                </div>
              </transition>
              <div class="ul-list-shop">
                <ul class="products-tab-shop nav nav-pills">
                  <li class="active">
                    <a href="#1a" @click="allProducts()" data-toggle="tab">All</a></li>
                  <li v-for="category in categories" :key="category.id">
                    <a href="#1a" @click.prevent="byCategory(category.id, category.name)" data-toggle="tab">{{category.name}}</a>
                  </li>
                </ul>
                <ul class="sidebar-shop">
                  <li @click="filterShower">Filter</li>
                </ul>
              </div>
              <transition name="slideRight">
                <div class="sidebar-shop-col" v-if="showFilter" style="margin-bottom: 20px;">
                  <div class="sidebar-product-style-2">
                    <div class="row">
                      <div class="col-md-4">
                        <aside class="sort-by">
                          <h4>Sort By</h4>
                          <ul>
                            <li>Default</li>
                            <li><a href="#" @click.prevent="sortByRating" >Average rating</a></li>
                            <li><a href="#" @click.prevent="sortByLatest">Newness</a></li>
                            <li><a href="#" @click.prevent="sortByPrice(false)">Price: Low to High</a></li>
                            <li><a href="#" @click.prevent="sortByPrice(true)">Price: High to Low</a></li>
                          </ul>
                        </aside>
                      </div>
                      <div class="col-md-4">
                        <aside class="sort-by">
                          <h4>Filter by price</h4>
                          <ul class="categories">
                            <li>All</li>
                            <li>&#8358;0.00 - &#8358;{{priceRange | money}}
                              <input 
                                type="range" 
                                min="0" 
                                :max="maxPrice"
                                v-model="priceRange"
                                @change="filterByPrice"
                              >
                            </li>
                          </ul>
                        </aside>
                      </div>
                      <div class="col-md-4">
                        <aside class="tagcloud">
                          <h4>tags</h4>
                          <ul class="tagcloud">
                            <li><a href="#" v-for="tag in tags" :key="tag.id">{{tag.name}}</a></li>
                          </ul>
                        </aside>
                      </div>
                    </div>
                  </div>
                </div>
              </transition> 
              <div class="desc-review-content tab-content clearfix">
                <div id="1a" class="tab-pane active" v-if="shopProducts.length > 0">
                    <art-shop-product 
                      v-for="product in shopProducts"
                      :product="product" 
                      :key="product.id"
                      @updateCart="updateCart"
                      @updateWishList="updateWishList">
                    </art-shop-product>
                  </div>
                  <art-loading v-else-if="firstLoad"></art-loading>
                  <h1 v-else>No Products found</h1>
                </div>
                <!-- </div>
                <a href="#" class="btn btn-color-white load-more-shop btn-background-white" @click.prevent="loadMore">Load more</a> -->

                <div id="2a" class="tab-pane">
                  
                </div>
                <div id="3a" class="tab-pane">
                  
                </div>
                <div id="4a" class="tab-pane">
                  
                </div>
                <div id="5a" class="tab-pane">
                  
                </div>
                <div id="6a" class="tab-pane">
                  
                </div>
              </div>
              <a href="#" class="btn btn-color-white load-more-shop btn-background-white" @click.prevent="loadMore">Load more</a>
            </div>
          </div>
        </div>
      </div>
</template>

<script>
	export default{
    props: ['categories', 'products', 'tags'],
		data(){
			return{
				shopProducts: [],
				take: 16,
        showFilter: false,
        showSearch: false,
        pageName: null,
        priceRange: 0,
        searchValue: null,
        firstLoad: true,
			}
		},
		methods: {
			filterShower() {
        if (this.showFilter === true ) {
          this.showFilter = false; 
        }
        else {
          this.showFilter = true;  
        }
			},
			searchShower() {
        if (this.showSearch === true ) {
          this.showSearch = false;  
        }
        else {
          this.showSearch = true; 
        }
			},
			loadMore() {
        this.theme.submitting();
        this.$emit('loadMore');
			},
      byCategory(id, name) {
        var value = {
          id: id,
          name: name
        }
        this.shopProducts = this.shop.productFilter(this, 'category', value, this.take);
      },
      sortByRating () {
        this.shop.sortingConfig.name = 'Sort by Product Rating';
        this.shopProducts = this.shop.sortByRating(this, this.products);
      },
      sortByPrice (desc) {
        this.shop.sortingConfig.name = 'Sort by Product Price';
        this.shopProducts = this.shop.sortByPrice(this.products, desc);
      },
      sortByLatest () {
        this.shop.sortingConfig.name = 'Sort by Newness';
        this.shopProducts = this.shop.sortByLatest(this.products);
      },
      filterByPrice () {
        var products = this.products.filter(product => {
          var discountedPrice = this.shop.getProductDiscount(product); 

          if (discountedPrice <= this.priceRange) {
            return true;
          }
        });
        this.shopProducts = this.shop.sortByPrice(products, true);
      },
      allProducts () {
        this.shop.sortingConfig.name = null;
        this.shopProducts = this.products.slice(0, this.take)
      },
      setProducts () {
        this.shopProducts = this.shop.sortProducts(this, this.take);
        this.priceRange = this.maxPrice;
        this.firstLoad = false;
      },
      search () {
        var $this = this;
        this.shop.sortingConfig = {
          filter: 'search',
          value: $this.searchValue,
          name: $this.searchValue,
        };
        this.checkSearch();
      },
      checkSearch () {
        if (this.shop.sortingConfig.filter === 'search') {
            this.shop.search(this, this.shop.sortingConfig.value);
        }
      },
      updateCart () {
        this.$emit('updateCart');
      },
      updateWishList () {
        this.$emit('updateWishList');
      },
		},
		computed: {
      maxPrice () {
        return Math.max.apply(Math, this.products.map(function(product){
            return product.price;
          }));
      }
		},
    watch: {
      products () {
        this.setProducts();
      },
    },
    created () {
      if (this.products.length > 0) {
          this.setProducts();
          this.checkSearch();
      }
    },
    mounted () {
      this.searchShower();
    },
    beforeDestroy () {
      this.shop.sortingConfig = {
        filter: null,
        value: null,
        name: null
      }
    }
	}
</script>

<style>
	.artshop-hide {
		display: none;
	}

</style>