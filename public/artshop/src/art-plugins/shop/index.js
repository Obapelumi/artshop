const shop = {
	getProducts ($this, display, take = null)  {
        var data = {
            display: display,
        }

        if (take !== null) {
            data.take = take;
        }

        $this.axios.post('products', data)
            .then(response => {
                $this.theme.submitted();
                $this.products = response.data.product;
            })
            .catch(response => {
                $this.theme.submitted();
            })
    },

    productStatus (value) {
        var display = ['pending', 'rejected', 'archived', 'approved', 'featured', 'on-sale'];
        return display[value];
    },

    productsByCategory ($this, id) {
        var result = $this.products.filter(product => product.category_id == id);
        return result;
    },

    productsByVendor ($this, id) {
        var result = $this.products.filter(product => product.vendor_id == id);
        return result;
    },

    productsByDisplay ($this, value) {
        var result = $this.products.filter(product => product.display == value);
        return result;
    },

    productsByTag ($this, id, take) {
        var products = $this.products.filter(product => {
            var tags = product.tag.filter(tag => tag.id == id);
            if (tags.length > 0) {
                return true;
            }
        });

        return products;
    },

    productsByPrice () {

    },

    sortByPrice (products, desc) {
        if (desc !== true) {
            return products.sort(function(a, b) {
                return parseFloat(a.price) - parseFloat(b.price);
            });
        }
        else {
            return products.sort(function(a, b) {
                return parseFloat(b.price) - parseFloat(a.price);
            });
        }
    },

    sortByLatest (products) {
        return products.sort(function(a, b) {
            a = new Date(a.created_at);
            b = new Date(b.created_at);
            return a>b ? -1 : a<b ? 1 : 0;
        });
    },

    sortByRating ($this, products) {
        products.forEach(product => {
            product.rating = $this.shop.productRating(product);
        });

        return products.sort(function(a, b) {
            return b.rating - a.rating;
        });
    },

    productFilter ($this, filter, value, take) {
        $this.shop.sortingConfig = {
            filter: filter,
            value: value.id,
            name: value.name,
        }
        return $this.shop.sortProducts($this, take);
    },

    sortProducts ($this, take) {
        var config = $this.shop.sortingConfig;
        if (config.filter === null) {
            return $this.products.slice(0, take);
        }
        else if (config.filter === 'tag') {
            return $this.shop.productsByTag($this, config.value).slice(0, take);
        }
        else if (config.filter === 'category') {
            return $this.shop.productsByCategory($this, config.value).slice(0, take);
        }
    },

    search ($this, value) {
        var result = $this.products.filter(product => product.name.indexOf(value) !== -1);

        if (result.length > 0) {
            $this.shopProducts = result;
        }
        else {
            $this.theme.submitting();
            $this.axios.post('search/product', {search: value})
                .then(response => {
                    $this.theme.submitted();
                    $this.shopProducts = response.data.result;
                });
        }
    },

    getReviews ($this) {
        $this.axios.get('review')
            .then(response => {
                $this.reviews = response.data.reviews;
            });
    },

    sortingConfig: {
        filter: null,
        value: null,
        name: null
    },

    singleProduct ($this) {
        var slug = $this.$route.params.slug;

        var result = $this.products.filter(product => product.slug == slug);
        if (result.length < 1) {
            $this.axios.get('product/' + slug)
                .then(response => {
                    $this.product = response.data.product;
                });
        }
        else {
            return result;    
        }
    },

    addToCart ($this, product, qty = 1, size) {
        var cart = JSON.parse(localStorage.getItem('cart'));
        var id = product.id;
        var storedItem = {};
        // let sizes = [];

        if (cart === null) {
            cart = {
                items: {},
                totalQty: 0,
                totalPrice: 0
            };
            storedItem.qty = qty;
        }
        else {
            storedItem.qty = qty;
            if (cart.items[id] != null){
                storedItem = cart.items[id];
                storedItem.qty += qty;
                // sizes = sizes.push(storedItem['sizes'])
            }
        }

        product.discountedPrice = this.getProductDiscount(product);

        storedItem['cost'] = product.discountedPrice * storedItem.qty;
        storedItem['item'] = product;
        // sizes = sizes.push(size);
        // storedItem['sizes'] = sizes;

        cart.items[id] = storedItem;
        cart.totalQty += qty;
        cart.totalPrice += product.discountedPrice * qty;
        var message;
        if (storedItem.qty <= product.stock) {
            message = product.name + ' has been added to your shopping cart';
            $this.theme.smoke('success', message, 3000);
            $this.$emit('updateCart');
            localStorage.setItem('cart', JSON.stringify(cart));
        }
        else{
            message = product.name + ' is out of stock';
            $this.theme.smoke('error', message, 3000);
            $this.$emit('updateCart');
        }
    },

    removeFromCart ($this,  product, qty = 1) {
        var cart = JSON.parse(localStorage.getItem('cart'));
        var id = product.id;

        product.discountedPrice = this.getProductDiscount(product);
        cart.items[id].qty -= qty;
        cart.items[id].cost -= product.discountedPrice * qty;
        cart.totalQty -= qty;
        cart.totalPrice -= product.discountedPrice * qty;

        if (cart.items[id].qty < 1) {
            delete cart.items[id];
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        var message = product.name + ' has been removed from your shopping cart';
        $this.theme.smoke('info', message, 3000);
        $this.$emit('updateCart');
        if (!$this.shop.checkCart()) {
            localStorage.removeItem('cart');
        }
    },

    getCart () {
        var cart = JSON.parse(localStorage.getItem('cart'));
        return cart;
    },

    checkCart () {
        var cart = this.getCart();
        if (cart == null) {
            return false;
        }
        else if (Object.keys(cart.items).length === 0) {
            return false;
        }
        else {
            return true;
        }
    },

    submitCart($this) {
      $this.theme.submitted();
      var token = $this.auth.getToken();
      var cart = $this.shop.getCart();
      var data = {
        user_id: token.user.id,
        cart: cart,
      }

      $this.axios.post('cart', data)
        .then((response) => {
          $this.theme.submitted();
          localStorage.setItem('cart', JSON.stringify(response.data.cart));
          $this.verifiedCart = response.data;

        });
    },

    addToWishList ($this, product) {
        var wishList = JSON.parse(localStorage.getItem('wishList'));
        var id = product.id;

        if (wishList === null) {
            wishList = {};
        }

        if (!(id in wishList)) {
            wishList[id] = product;
            localStorage.setItem('wishList', JSON.stringify(wishList));
            var message = product.name + ' has been added to your Wish list'
            $this.theme.smoke('success', message, 2000);
        }
        else {
            var message = product.name + ' is already in your Wish list'
            $this.theme.smoke('error', message, 2000);
        }
    },

    removeFromWishList ($this, product) {
        var wishList = JSON.parse(localStorage.getItem('wishList'));
        var id = product.id;
        
        delete wishList[id];
        localStorage.setItem('wishList', JSON.stringify(wishList));
        var message = product.name + ' has been removed from your Wish list'
        $this.theme.smoke('info', message, 2000);
    },

    getWishList () {
        return JSON.parse(localStorage.getItem('wishList'));
    },

    checkWishList () {
        var wishList = this.getWishList();
        if (wishList == null) {
            return false;
        }
        else if (Object.keys(wishList).length === 0) {
            return false;
        }
        else {
            return true;
        }
    },

    checkStock (product) {
        if (product.stock > 0) {
            return true;
        }
        else {
            return false;
        }
    },

    checkDiscount (product) {
        var discount = product.discount;
        if (discount.length > 0) {
            // product.discount.sort(function(a, b) {
            //     a = new Date(a.created_at);
            //     b = new Date(b.created_at);
            //     return a>b ? -1 : a<b ? 1 : 0;
            // });
            if (Date.parse(discount[0].period) >= Date.parse(new Date())) {
                return true; 
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    },

    getProductDiscount (product) {
        if (this.checkDiscount(product)) {
            return product.price * (1- product.discount[0].discount);
        }
        else {
            return product.price
        }
    },

    getCategories ($this) {
        $this.axios.get('category')
            .then(response => {
                $this.categories = response.data.category;
            });
    },

    getTags ($this) {
        $this.axios.get('tag')
            .then(response => {
                $this.tags = response.data.tags;
            });
    },
    
    pay ($this) {
        PaystackPop.setup({
         key: $this.theme.config.PAYSTACK_PK,
         email: $this.user.email,
         amount: parseInt($this.verifiedCart.shipping_cost) + parseInt($this.cart.totalPrice),
         container: 'paystackEmbedContainer',
         callback: function(response){
              var data = {
                user_id: $this.user.id,
                customer_id: $this.user.customer.id,
                cart_id: $this.verifiedCart.id,
                trx_id: response.trxref,
                amount_charged: $this.cart.totalPrice + $this.verifiedCart.shipping_cost,
                order_notes: $this.order_notes,
              }
              $this.theme.submitting();
              $this.axios.post('order', data)
                .then(response => {
                    $this.theme.submitted();
                    var successMessage = 'Thanks ' + $this.user.name + ', Your order has been recieved';
                    $this.theme.smoke('success', successMessage, 4000);
                    $this.$router.push('/dashboard');
                    localStorage.removeItem('cart');
                    
                    var order = response.data.order;
                    $this.shop.sendOrderMails($this, order);                    

                    localStorage.removeItem('cart');
                })
                .catch(response => {
                    $this.theme.submitted();
                    var errorMessage = 'Ooops! There was a problem submitting your order. Please contact support';
                    // $this.theme.smoke('error', errorMessage, 10000);
                });
          },
        });
    },

    sendOrderMails ($this, order) {
        var orderData = {
            order: order,
        };

        $this.axios.post('mail/order', orderData)
            .then(response => {
                $this.theme.smoke('success', 'We have sent you an email to confirm your order', 10000);
            })
            .catch(response => {
                // $this.theme.smoke('error', 'We tried sending you an email to confirm your order but it failed', 10000);
            });

        var products = [];
        for (item in order.cart['items']) {
            var  product = item.item;
            products[product.vendor.user_id] = product;
        }

        $this.axios.post('mail/product-purchase', products);
    },

    getOrders ($this) {
        var orders;
        $this.axios.get('order')
            .then(response => {
                $this.orders = response.data.orders;
            });
    },

    productRating(product) {
        var rating = 0, voters = 0;

        if (product.review.length > 0) {
            product.review.forEach(review => {
                rating += parseInt(review.vote);
                if (review.vote > 0) {
                    voters++;
                }
              });
              rating = parseInt(rating/voters);    
        }

        return rating;
    },

    install: function(Vue){
      Object.defineProperty(Vue.prototype, 'shop', {
        get () { return shop }
      })
    },

}

export default shop;