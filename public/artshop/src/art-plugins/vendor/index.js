const vendor = {

    register($this, next) {
        $this.theme.submitting();
        var data = $this.vendorObj;

        $this.axios.post('vendor', data)
            .then(response => {
                if (response.data.status == true) {
                    var user = $this.auth.getUser();
                    user.vendor = response.data.vendor;
                    var successMessage = 'Hi ' + user.vendor.brand_name + ', you have been successfully registered';
                    $this.theme.smoke('success', successMessage, 3000);
                    $this.meta.image.vendor_id = response.data.vendor.id;
                    $this.meta.image.slug = response.data.vendor.slug;
                    $this.uploadImage = true;
                    localStorage.setItem('user', JSON.stringify(user));
                    $this.$emit('setAuth');
                    $this.$router.push('/dashboard/create-product');
                }
                else {
                    var errorMessage = response.data.message;
                    $this.theme.smoke('error', errorMessage, 10000);
                }
                $this.theme.submitted();
            })
            .catch(response => {
                $this.theme.submitted();
                var errorMessage = 'Please check your Account Number and Bank Code, then try again';
                $this.theme.smoke('error', errorMessage, 10000);
            });
    },

    updateVendor ($this, next) {
        $this.theme.submitting();
        var data = $this.editedVendor;

        $this.axios.put('vendor/' + $this.editedVendor.slug, data)
            .then(response => {
                $this.theme.submitted();
                var successMessage = 'Profile Updated Successfully';
                $this.theme.smoke('success', successMessage, 10000);
                setTimeout(function(){
                    var user = JSON.parse(localStorage.getItem('user'));
                    var vendor = response.data.vendor;
                    if (user.id === vendor.user_id) {
                        user.vendor = response.data.vendor;
                        localStorage.setItem('user', JSON.stringify(user));
                        $this.$router.push(next);
                    }
                }, 1500);
            })
            .catch(response => {
                $this.theme.submitted();
                var errorMessage = 'There was an issue please try again';
                $this.theme.smoke('error', errorMessage, 10000);
            });
    },

    getVendors ($this) {
        $this.axios.get('vendor')
            .then(response => {
                $this.vendors = response.data.vendors;
            });
    },

    singleVendor ($this) {
        var slug = $this.$route.params.slug;
        var result = $this.vendors.filter(vendor => vendor.slug === slug);
        return result;
    },

    vendorSales (products) {
        var sales = 0;
        products.forEach(product => {
            sales += parseInt(product.quantity_sold);
        });
        return sales;
    },

	createProduct ($this) {
        $this.theme.submitting();
        $this.product.price = parseInt($this.product.price) * 100;

        var data = $this.product;

        $this.axios.post('product', data)
            .then(response => {
                $this.theme.submitted();
                var successMessage = 'Your Product has been added. We will review it and get back to you soon';
                $this.theme.smoke('success', successMessage, 10000);
                $this.meta.image.product_id = response.data.product.id;
                $this.meta.image.slug = response.data.product.slug;
                $this.uploadImage = true;
                $this.$emit('updateProduct');
            })
            .catch(response => {
                $this.theme.submitted();
                var errorMessage = 'There was an issue please try again';
                $this.theme.smoke('error', errorMessage, 3000)
            });
	},

    updateProduct ($this) {
        $this.theme.submitting();
        $this.product.price = parseInt($this.price) * 100;

        var data = $this.product;
        $this.axios.put('product/' + $this.product.slug, data)
            .then(response => {
                $this.theme.submitted();
                var successMessage = 'Product has been updated';
                $this.theme.smoke('success', successMessage, 6000);
                $this.meta.image.product_id = response.data.product.id;
                $this.meta.image.slug = response.data.product.slug;
                $this.meta.upload = true;
            });
    },

    saveDraft ($this, next) {
        localStorage.setItem('productDraft', JSON.stringify($this.product));
        $this.theme.smoke('success', 'Draft saved', 1000);
        $this.$router.push(next);
    },

    getDraft ($this) {
        var product = JSON.parse(localStorage.getItem('productDraft'));
        if (product != null) {
            $this.theme.smoke('success', 'Draft retrieved', 3000);
        }
        return product;
    },

    isMyProduct ($this) {
        if (!$this.auth.checkVendor()) {
            return false;
        }
        else {
            var user = $this.auth.getUser($this);
            if ($this.product.vendor_id === user.vendor.id) {
                return true;
            }
            else {
                return false;
            }
        }
    },
    updateStock ($this) {
        $this.theme.submitting();
        var data = {
            stock: $this.product.stock
        };
        $this.axios.put('product/' + $this.product.slug, data)
            .then(response => {
                $this.theme.submitted();
                $this.theme.smoke('success', 'Stock Updated', 3000);
            })
            .catch(response => {
                $this.theme.submitted();
                $this.theme.smoke('error', 'Stock Not Updated', 3000);
            });
    },
    checkStockUpdate ($this, product) {
        // if(Date.parse(product.meta.stock_updated_at) >= Date.parse(new Date())) {
            if(Date.parse(product.updated_at) >= Date.parse(new Date())) {
            return true
        }
        // if (product.meta.stock_updated_at >= Date.now()) {
        //     return true
        // }
        else {
            false;
        }
    },

    sortVendors ($this, take) {
        config = $this.vendor.sortingConfig;
        if (config.filter === 'search') {
            $this.vendor.search($this, config.value);
        }
    },

    search ($this, value) {
        var result = $this.vendors.filter(vendor => vendor.brand_name.indexOf(value) !== -1);

        if (result.length > 0) {
            $this.displayedVendors = result;
        }
        else {
            $this.theme.submitting();
            $this.axios.post('search/vendor', {search: value})
                .then(response => {
                    $this.theme.submitted();
                    $this.displayedVendors = response.data.result;
                })
                .catch(response => {
                    $this.theme.submitted();
                });
        }
    },

    sortingConfig : {
        filter: null,
        value: null,
        name: null,
    },

    install: function(Vue){
      Object.defineProperty(Vue.prototype, 'vendor', {
        get () { return vendor }
      })
    },

}

export default vendor;