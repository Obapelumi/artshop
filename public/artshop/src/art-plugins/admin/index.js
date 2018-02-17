const admin = {
    registerAdmin ($this) {
		$this.theme.submitting();
    	var full_name = $this.firstName + ' ' + $this.lastName;
    	var data = {
			name: full_name,
			email: $this.email,
			phone: $this.phone,
			password: $this.password,
			c_password: $this.password,
    	}

    	$this.axios.post('admin', data)
    		.then(response => {
				$this.theme.submitted();
    			$this.theme.smoke('success', 'Administrator has been Invited via email', 10000);
    		})
    		.catch(response => {
				$this.theme.submitted();
    			$this.theme.smoke('error', 'There was an error. Please try again', 10000);	
    		});
	},
	
	getAdmins ($this) {
		$this.axios.get('admin')
			.then(response => {
				$this.admins = response.data.admins;
			});
	},

    registerBlogger ($this) {
		$this.theme.submitting();
    	var full_name = $this.firstName + ' ' + $this.lastName;
    	var data = {
			name: full_name,
			email: $this.email,
			phone: $this.phone,
			password: $this.password,
			c_password: $this.password,
    	}

    	$this.axios.post('blogger', data)
    		.then(response => {
				$this.theme.submitted();
    			$this.theme.smoke('success', 'Administrator has been Invited via email', 10000);
    		})
    		.catch(response => {
				$this.theme.submitted();
    			$this.theme.smoke('error', 'There was an error. Please try again', 10000);	
    		});
	},
	
	submitBlogPost ($this) {
		$this.theme.submitting();
		$this.axios.post('blog', $this.post)
			.then(response => {
				$this.theme.submitted();
				$this.theme.smoke('success', 'Post Submitted successfully!', 5000);
				$this.post.meta.image.blog_id = response.data.post.id;
				$this.post.meta.image.slug = response.data.post.slug;
				$this.uploadImage = true;
				$this.$emit('updatePost');
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was an issue submitting your post please try again', 4000);
			});
	},

	updateBlogPost ($this) {
		$this.theme.submitting();
		$this.axios.put('blog/'+ $this.post.id, $this.post)
			.then(response => {
				$this.theme.submitted();
				$this.$emit('updatePosts');
				$this.theme.smoke('success', 'Post Updated successfully!', 5000);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was an issue submitting your post please try again', 4000);
			});
	},

	deleteBlogPost ($this, post) {
		$this.theme.submitting();
		$this.axios.delete('blog/'+post.id)
			.then(response => {
				$this.theme.submitted();
				$this.theme.smoke('success', 'Post deleted', 4000);
				$this.$emit('updatePosts');
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was an issue please try again', 4000);
				$this.$emit('updatePosts');
			})
	},

	getBlogPosts ($this) {
		$this.axios.get('blog')
			.then(response => {
				$this.posts = response.data.posts;
			});
	},

	postsByBlogger ($this) {
		var id = $this.auth.getUser().id;
		var result = $this.posts.filter(post => post.user.id = id);
		return result;
	},

	singlePost ($this) {
		var slug = $this.$route.params.slug;
		var result = $this.posts.filter(post => post.slug === slug);
		return result[0];
	},

	postComment ($this) {
		$this.theme.submitting();
		$this.commentObj.blog_id = $this.post.id;
		var data = $this.commentObj;

		$this.axios.post('comment', data)
			.then(response => {
				$this.theme.submitted();
				$this.$emit('updatePosts');
				$this.commentObj = {
					blog_id: '',
					name: '',
					comment: '',
					reply: 0
				  };
				  
				$this.replyTo = '';
				$this.theme.smoke('success', 'Your comment was posted', 2000);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was aproblem please try again', 2000);
			});
	},

	submitNewsLetter ($this) {
		$this.theme.submitting();
		$this.axios.post('news-letter', $this.letter)
			.then(response => {
				$this.theme.submitted();
				$this.theme.smoke('success', 'News Letter has been created and will be sent when due', 7000);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was a problem please try again', 5000);
			});
	},

	getNewsLetters ($this) {
		$this.axios.get('news-letter')
			.then(response => {
				$this.newsletters = response.data.newsletters;
			});
	},

	addCategory ($this) {
		$this.theme.submitting();
		$this.axios.post('category', $this.category)
			.then(response => {
				$this.theme.submitted();
				$this.$emit('updateShop');
				$this.theme.smoke('success', 'Category created', 2000);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was an issue please try again', 2000);
			});
	},

	deleteCategory ($this, id) {
		$this.theme.submitting();
		$this.axios.delete('category/'+ id )
			.then(response => {
				$this.theme.submitted();
				$this.$emit('updateShop');
				$this.theme.smoke('success', 'Category deleted', 2000);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was an issue please try again', 2000);
			});
	},

	addTag ($this) {
		$this.theme.submitting();
		$this.axios.post('tag', $this.tag)
			.then(response => {
				$this.theme.submitted();
				$this.$emit('updateShop');
				$this.theme.smoke('success', 'Tag created', 2000);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was an issue please try again', 2000);
			});
	},

	deleteTag ($this, id) {
		$this.theme.submitting();
		$this.axios.delete('tag/'+ id )
			.then(response => {
				$this.theme.submitted();
				$this.$emit('updateShop');
				$this.theme.smoke('success', 'Tag deleted', 2000);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was an issue please try again', 2000);
			});
	},

	addDiscount ($this) {
		$this.theme.submitting();
		$this.discount.discount = parseInt($this.discount.discount) /100;
		$this.axios.post('discount', $this.discount)
			.then(response => {
				$this.theme.submitted();
				$this.$emit('updateShop');
				$this.theme.smoke('success', 'Discount created', 2000);
				$this.admin.getDiscounts($this);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was an issue please try again', 2000);
			});
	},

	getDiscounts ($this) {
		$this.axios.get('discount')
			.then(response => {
				$this.discounts = response.data.discounts;
			});
	},

	deleteDiscount ($this, id) {
		$this.theme.submitting();
		$this.axios.delete('discount/' + id)
			.then(response => {
				$this.theme.submitted();
				$this.admin.getDiscounts($this);
				$this.theme.smoke('success', 'Discount deleted', 2000);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was an issue please try again', 2000);
			});
	},

	removeProductDiscount($this, id) {
		$this.theme.submitting();
		$this.axios.delete('discount-product/' + id)
			.then(response => {
				$this.theme.submitted();
				$this.admin.getDiscounts($this);
				$this.theme.smoke('success', 'Discount created', 2000);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'There was an issue please try again', 2000);
			});
	},

	updateOrderStatus ($this) {
		$this.theme.submitting();
		var data = {
			status: $this.order.status
		}

		$this.axios.put('order/' + $this.order.id, data)
			.then(response => {
				$this.theme.submitted();
				if ($this.order.status === 'confirmed') {
					$this.theme.smoke('success', 'Vendor Payment Confirmed', 2000);
				}
				else {
					$this.theme.smoke('success', 'Status Updated', 2000);
				}
				$this.$emit('updateOrders');
			})
			.catch(response => {
				$this.theme.submitted();
				$this.$emit('updateOrders');
				var errorMessage = response.message;
				$this.theme.smoke('error', errorMessage, 5000);
			});
	},

    install: function(Vue){
      Object.defineProperty(Vue.prototype, 'admin', {
        get () { return admin }
      })
    },

}

export default admin;