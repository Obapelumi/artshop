<template>
<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
  <!-- Recently Favorited -->
  <div id="customer_details" class="widget dashboard-container my-adslist">
    <h2 class="widget-header">PRODUCTS</h2>
    <h4 class="widget-header" style="line-height:50px">
      <a class="btn btn-transparent" @click.prevent="setProducts(0)">PENDING</a>  
      <a class="btn btn-transparent" @click.prevent="setProducts(1)">REJECTED </a>  
      <a class="btn btn-transparent" @click.prevent="setProducts(2)">ARCHIVED</a> 
      <a class="btn btn-transparent" @click.prevent="setProducts(3)">APPROVED</a> 
      <a class="btn btn-transparent" @click.prevent="setProducts(4)">FEATURED</a> 
      <a class="btn btn-transparent" @click.prevent="setProducts(5)">DISCOUNTED</a> 
    </h4>
    <h3><span v-if="searchValue">{{searchValue}}</span><span v-else>{{display}}</span></h3>
      <transition name="slideRight">
        <div class="sidebar-shop-search" v-if="showSearch">
          <form style="margin-bottom: 20px;" @submit.prevent="search">
            <input type="text" v-model="searchValue" placeholder="Search product"/>
            <a href="#" @click.prevent="search"><i class="fa fa-search"></i></a>
          </form>
        </div>
      </transition>
    <div class="cart-form">
      <table>
          <tbody>
          <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Vendor</th>
            <th>Created</th>
            <th>Status</th>
          </tr>
          <tr v-for="product in shopProducts" :key="product.id" v-if="shopProducts.length > 0">
            <td data-title="Product">
              <a href="#" @click.prevent="approveProduct(product.slug)" class="image-product">
                        <img 
                            :src="theme.imagePath(product.file[0].path)" 
                            :alt="product.name" 
                            width="180" height="220" style="height: 80px; width: 180px"
                            v-if="product.file.length > 0"
                        >
                        <img 
                            src="/images/logo/logo-black.jpg"
                            :alt="product.name" 
                            width="180" height="220" style="height: 80px; width: 180px" 
                            v-else
                        >
              </a>
            </td>
            <td data-title="Name">
              <a @click.prevent="approveProduct(product.slug)" href="" class="name-product">
            <h4>{{product.name}}</h4>
              </a>
            </td>
            <td data-title="Price">
              <span class="price">&#8358;{{product.price | money}}</span>
            </td>
            <td data-title="Vendor">
              <span class="quantity" v-if="product.vendor">   {{product.vendor.brand_name}}</span>
            </td>
            <td data-title="Created">
              <span class="total">{{product.created_at | moment("dddd, MMMM Do YYYY")}}</span>
            </td>
            <td data-title="Status"> 
              <router-link to="">
               {{shop.productStatus(product.display)}}
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
      <h1 v-if="shopProducts.length <= 0" class="capitalize">no products yet</h1>
    </div>
  </div>
</div>
</template>

<script>

  export default{
    props: ['user', 'orders', 'products'],
    data(){
      return{
        shopProducts: [],
        display: null,
        searchValue: null,
        showSearch: false,
      }
    },
    methods: {
      approveProduct (slug) {
        var push = '/dashboard/pending-product/' + slug;
        this.$router.push(push);
      },
      setProducts (value) {
        this.display = this.shop.productStatus(value);
        if (value === 5) {
          var $this = this;
          this.shopProducts = this.products.filter(product => {
            return $this.shop.checkDiscount(product)
          });
        }
        else {
          this.shopProducts = this.shop.productsByDisplay (this, value);
        }
      },
			searchShower() {
        this.showSearch = !this.showSearch;
      },
      search () {
        var $this = this;
        this.shop.search(this, this.searchValue);
      },
    },
    watch: {
      products () {
        this.setProducts(0);
      },
    },
    created () {
      this.setProducts(0);
    },
    mounted () {
      this.searchShower();
    },
  }
</script>

<style>
  
</style>