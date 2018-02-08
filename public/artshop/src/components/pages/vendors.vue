<template>
<div class="has-header">
        <art-page-header :page="'Vendors'" :subPage="vendor.sortingConfig.name"></art-page-header>
      <section data-js-module="filtering-demo" class="portfolio-full masonry-bottom-title big-demo go-wide container">
        <!-- <div class="filter-button-group button-group js-radio-button-group">
          <button @click.prevent="setVendors()" class="button is-checked">All</button>
          <button  class="button is-checked" 
            v-for="category in categories" 
            :key="category.id"
            @click.prevent="vendorByCategory(category.id)">{{category.name}}</button>
        </div> -->
              <div class="ul-list-shop">
                <ul class="products-tab-shop nav nav-pills">
                  <li class="active">
                    <a href="#1a" @click="setVendors()" data-toggle="tab">All</a></li>
                  <li v-for="category in categories" :key="category.id">
                    <a href="#1a" @click.prevent="vendorByCategory(category.id, category.name)" data-toggle="tab">{{category.name}}</a>
                  </li>
                </ul>
              </div>
              <transition name="slideRight">
                <div class="sidebar-shop-search" v-if="showSearch">
                  <form style="margin-bottom: 20px;" @submit.prevent="search">
                    <input type="text" v-model="searchValue" placeholder="Search Vendors"/>
                    <a href="#" @click.prevent="search"><i class="fa fa-search"></i></a>
                  </form>
                </div>
              </transition>
        <transition name="fade">
        <ul class="grid da-thumbs woocommerce columns-4" v-if="displayedVendors.length > 0">
          <li data-category="transition" class="element-item photograph product-item-wrap" v-for="thisVendor in displayedVendors" :key="thisVendor.id">
            <div class="hover-dir">
              <figure @click.prevent="singleVendor(thisVendor.slug)">
                <img 
                  :src="theme.imagePath(thisVendor.file.path)" 
                  :alt="thisVendor.brand_name"
                  v-if="thisVendor.file"
                  class="attachment-shop_catalog size-shop_catalog wp-post-image" 
                >
                <img 
                  src="/images/logo/favicon.png"
                  :alt="thisVendor.brand_name"
                  v-else
                  class="attachment-shop_catalog size-shop_catalog wp-post-image" 
                >
              </figure>
              <div>
                <div class="in-slider">
                  <div class="in-slider-content"><a href="images/demo/portfolio-4.jpg" rel="prettyPhoto[gallery1]"><i class="fa fa-search"></i></a><a href="portfolio-vertical-slider.html">
                      <h6>{{thisVendor.brand_name}}</h6></a>
                    <p>Sales: {{vendor.vendorSales(thisVendor.product)}}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="title-bottom"><a href="#" @click.prevent="singleVendor(thisVendor.slug)">{{thisVendor.brand_name}}</a>
              <p v-if="vendor.vendorSales(thisVendor.product) > 5">Sales: {{vendor.vendorSales(thisVendor.product)}}</p>
            </div>
          </li>
        </ul>
        <art-loading v-else-if="firstLoading"></art-loading>
        <h1 v-else>No Vendors</h1>
        </transition>
        <div class="clearfix"></div>
        <!-- <div class="button" style="margin-top: 20px;"><a href="#" @click.prevent="loadMore">Load More</a></div> -->
      </section>
</div>
</template>

<script>
export default {
	props: ['vendors', 'categories'],
	data () {
		return {
      displayedVendors: [],
      take: 20,
      showSearch: false,
      searchValue: null,
      firstLoading: true,
		}
	},
	methods: {
		loadMore () {
      this.take += 10;
      this.setVendors();
    },
		searchShower() {
        if (this.showSearch === true ) {
          this.showFilter = false;
          this.showSearch = false;  
        }
        else {
          this.showFilter = false; 
          this.showSearch = true; 
        }
    },
      search () {
        var $this = this;
        this.vendor.sortingConfig = {
          filter: 'search',
          value: $this.searchValue,
          name: $this.searchValue,
        };
        this.checkSearch();
      },
    setVendors () {
      this.vendor.sortingConfig.name = '';
      this.displayedVendors = this.vendors.slice(0, this.take);
      this.firstLoading = false;
    },
    vendorByCategory (id, name) {
      this.vendor.sortingConfig.name = name;
      this.displayedVendors = this.vendors.filter(vendor => vendor.category.id === id);
    },
    singleVendor (slug) {
      this.$router.push('/vendors/' + slug);
    },
    checkSearch () {
        if (this.vendor.sortingConfig.filter === 'search') {
            this.vendor.search(this, this.vendor.sortingConfig.value);
        }
    },
  },
  watch: {
    vendors () {
      this.setVendors();
    },
  },
  created () {
    if (this.vendors.length > 0) {
      this.setVendors();
      this.checkSearch();
    }
    this.searchShower();
  },
  beforeDestroy () {
    this.vendor.sortingConfig = {
      filter: null,
      value: null,
      name: null
    }
  }
}
</script>

<style>
  .portfolio-full figure {
    cursor: pointer;
  }
</style>