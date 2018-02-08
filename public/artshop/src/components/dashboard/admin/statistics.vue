<template>
<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
  <!-- Recently Favorited -->
  <div class="widget dashboard-container my-adslist">
    <h4 class="widget-header" style="line-height:50px">
      <a href="" @click.prevent="setProducts(0)">PENDING PRODUCTS</a> / 
      <a href="" @click.prevent="setProducts(1)">REJECTED PRODUCTS</a> / 
      <a href="" @click.prevent="setProducts(2)">ARCHIVED PRODUCTS</a> / <br>
      <a href="" @click.prevent="setProducts(3)">APPROVED PRODUCTS</a> /
      <a href="" @click.prevent="setProducts(4)">FEATURED PRODUCTS</a> /
      <a href="" @click.prevent="setProducts(5)">DISCOUNTED PRODUCTS</a> 
    </h4>
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
          <tr v-for="product in pendingProducts" :key="product.id" v-if="pendingProducts">
            <td data-title="Product">
              <a href="#" class="image-product">
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
      <h1 v-if="!pendingProducts">You have no products yet</h1>
    </div>
  </div>
</div>
</template>

<script>

  export default{
    props: ['user', 'orders', 'products'],
    data(){
      return{
        pendingProducts: [],
      }
    },
    methods: {
      approveProduct (slug) {
        var push = '/dashboard/pending-product/' + slug;
        this.$router.push(push);
      },
      setProducts (value) {
        this.pendingProducts = this.shop.productsByDisplay (this, value);
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

  }
</script>

<style>
  
</style>