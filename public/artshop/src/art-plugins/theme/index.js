const theme = {
	carousel ()  {
        $('.sofani-owl-carousel').each(function () {

            var $this = $(this),
                $loop = $this.attr('data-loop') == 'yes',
                $numberItem = parseInt($this.attr('data-number')),
                $Nav = $this.attr('data-navcontrol') == 'yes',
                $Dots = $this.attr('data-dots') == 'yes',
                $autoplay = $this.attr('data-autoplay') == 'yes',
                $autoplayTimeout = parseInt($this.attr('data-autoplaytimeout')),
                $marginItem = parseInt($this.attr('data-margin')),
                $rtl = $this.attr('data-rtl') == 'yes',
                $autoHeight = $this.attr('data-autoheight') == 'yes',
                $resNumber = {
                    0: {
                        items: 1
                    }
                }; // Responsive Settings

            $numberItem = (isNaN($numberItem)) ? 1 : $numberItem;
            $autoplayTimeout = (isNaN($autoplayTimeout)) ? 6000 : $autoplayTimeout;
            $marginItem = (isNaN($marginItem)) ? 0 : $marginItem;

            if (!$this.is('.owl-carousel')) {
                $this.addClass('owl-carousel');
            }

            switch ($numberItem) {

                case 1 :
                    $resNumber = {
                        0: {
                            items: 1
                        }
                    }
                    break;

                case 2 :
                    $resNumber = {
                        0: {
                            items: 1
                        },
                        480: {
                            items: 1
                        },
                        768: {
                            items: 2
                        },
                        992: {
                            items: $numberItem
                        }
                    }
                    break;

                case 3 :
                case 4 :
                    $resNumber = {
                        0: {
                            items: 1
                        },
                        480: {
                            items: 2
                        },
                        768: {
                            items: 2
                        },
                        992: {
                            items: 3
                        },
                        1200: {
                            items: $numberItem
                        }
                    }
                    break;

                default : // $numberItem > 4
                    $resNumber = {
                        0: {
                            items: 1
                        },
                        480: {
                            items: 2
                        },
                        768: {
                            items: 3
                        },
                        992: {
                            items: 3
                        },
                        1200: {
                            items: 4
                        },
                        1500: {
                            items: 5
                        },
                        1800: {
                            items: $numberItem
                        }
                    }
                    break;
            } // Endswitch


            $(this).owlCarousel({
                items: $numberItem,
                loop: $loop,
                nav: $Nav,
                navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
                navContainer: false,
                dots: $Dots,
                autoplay: $autoplay,
                autoplayTimeout: $autoplayTimeout,
                autoHeight: $autoHeight,
                margin: $marginItem,
                //responsiveClass:true,
                rtl: $rtl,
                responsive: $resNumber,
                autoplayHoverPause: true,

                //center: true,
                onRefreshed: function () {
                    var total_active = $this.find('.owl-item.active').length;
                    var i = 0;

                    $this.find('.owl-item').removeClass('active-first active-last');
                    $this.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('active-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('active-last');
                        }
                    });
                },
                onTranslated: function () {
                    var total_active = $this.find('.owl-item.active').length;
                    var i = 0;

                    $this.find('.owl-item').removeClass('active-first active-last');
                    $this.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('active-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('active-last');
                        }
                    });
                },
                onResized: function () {
                    //essence_set_equal_columns();
                }
            });

        });
	},

    carousel2 () {
        var sync1 = $("#sync1");
        var sync2 = $("#sync2");
        var slidesPerPage = 4; 
        var syncedSecondary = true;

        sync1.owlCarousel({
          items : 1,
          slideSpeed : 2000,
          nav: true,
          autoplay: false,
          dots: true,
          loop: true,
          responsiveRefreshRate : 200,
          navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
        }).on('changed.owl.carousel', syncPosition);

        sync2
          .on('initialized.owl.carousel', function () {
            sync2.find(".owl-item").eq(0).addClass("current");
          })
          .owlCarousel({
          items : slidesPerPage,
          dots: true,
          nav: true,
          smartSpeed: 200,
          slideSpeed : 500,
          slideBy: slidesPerPage, 
          responsiveRefreshRate : 100
        }).on('changed.owl.carousel', syncPosition2);

        function syncPosition(el) {
          var count = el.item.count-1;
          var current = Math.round(el.item.index - (el.item.count/2) - .5);
          
          if(current < 0) {
            current = count;
          }
          if(current > count) {
            current = 0;
          }
          
          //end block

          sync2
            .find(".owl-item")
            .removeClass("current")
            .eq(current)
            .addClass("current");
          var onscreen = sync2.find('.owl-item.active').length - 1;
          var start = sync2.find('.owl-item.active').first().index();
          var end = sync2.find('.owl-item.active').last().index();
          
          if (current > end) {
            sync2.data('owl.carousel').to(current, 100, true);
          }
          if (current < start) {
            sync2.data('owl.carousel').to(current - onscreen, 100, true);
          }
        }
        
        function syncPosition2(el) {
          if(syncedSecondary) {
            var number = el.item.index;
            sync1.data('owl.carousel').to(number, 100, true);
          }
        }
        
        sync2.on("click", ".owl-item", function(e){
          e.preventDefault();
          var number = $(this).index();
          sync1.data('owl.carousel').to(number, 300, true);
        });
    },

    customerSpotLight () {
        var recent_news = $('.slick-slider');
        recent_news.slick({
          centerMode: true,
          centerPadding: '375px',
          slidesToShow: 1,
          slidesToScroll: 1,
          // variableWidth: true, // slidesToShow doesn't work, must use css to have width
          autoplay: true,
          autoplaySpeed: 4000,
          responsive: [
            {
              breakpoint: 1500,
              settings: {
                centerPadding: '200px',
                slidesToShow: 1
              }
            },
            {
              breakpoint: 1130,
              settings: {
                centerPadding: '200px',
                slidesToShow: 1
              }
            },
            {
              breakpoint: 850,
              settings: {
                centerPadding: '150px',
                slidesToShow: 1
              }
            },
            {
              breakpoint: 767,
              settings: {
                centerPadding: '0px',
                slidesToShow: 1
              }
            },
            {
              breakpoint: 500,
              settings: {
                centerPadding: '0px',
                slidesToShow: 1
              }
            },
            {
              breakpoint: 479,
              settings: {
                centerPadding: '0',
                slidesToShow: 1
              }
            },
          ]
        });
    },

    imageUpload ($this) {
        $this.theme.submitting();
        if ($this.index) {
            $this.imageObj.slug = $this.imageObj.slug + '-' + $this.index; 
        }
        $this.axios.post('file', $this.imageObj)
            .then(response => {
                $this.theme.submitted();
                $this.theme.smoke('success', 'Image Uploaded', 3000);
                $this.show = false;
            })
            .catch(response => {
                $this.theme.submitted();
                $this.theme.smoke('error', 'Image Upload failed', 3000);
            });
    },

    imageUpdate ($this) {
        $this.theme.submitting();
        $this.axios.put('file/' + $this.file.id, $this.imageObj)
            .then(response => {
                $this.theme.submitted();
                $this.theme.smoke('success', 'Image Updated', 3000);
            })
            .catch(response => {
                $this.theme.submitted();
                $this.theme.smoke('error', 'Image Upload failed', 3000);
            });
    },

    imagePath (path) {
        var API_URL = this.config.SITE_URL + '/api/';
        return API_URL + 'file/' + path;
    },

    getCountries ($this) {
        $this.axios.get('get-countries')
            .then(response => {
                $this.countries = response.data;
            });
    },

    smoke (type, info, time = 3000) {
        smoke.signal(info, function(e){

        }, {
            duration: time,
            classname: type
        });

        $(".smoke-base").hide();
        $(".smoke-base").slideDown();
    },

    getCKEditor ($this, id, returnValue) {
      if (document.getElementById(id).id !== 'editor') {
        document.getElementById(id).id = 'editor';
        CKEDITOR.replace('editor');
      }
      if (id === 'edit_blog_body') {
        CKEDITOR.instances.editor.setData($this.post.body);
      }
      function updateValue () {
          var editorText = CKEDITOR.instances.editor.getData();
          $this[returnValue] = editorText;
      }
      $this.timer = setInterval(updateValue,100);
    },

    showLoading ($this) {
        $this.$emit('showLoader');
    },

    submitting () {
        $("#artshop-submitting").delay(150).fadeIn(950);
    },

    submitted () {
        $("#artshop-submitting").fadeOut(950);
    },

    checkWidth () {
        var width = $(window).width();
        if (width >= 992) {
          return true
        }
        else {
          return false
        }
    },

    handle422 ($this, response) {
        for (const [k, v] of Object.entries(response.response.data)) {
          $this.error += v[0] + ' ';
        }
        
        setTimeout(function(){
            $this.error = false;
        }, 10000);  
    },

    searchResult: 'beans',

    config: {
        SITE_URL: 'http://artshop.com.ng',
        NEXT: '',
        PREV: '',
        TODAY () {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();
            if(dd<10){
                    dd='0'+dd
                } 
                if(mm<10){
                    mm='0'+mm
                } 

            today = yyyy+'-'+mm+'-'+dd;
            return today;
        },
        PAYSTACK_PK: 'pk_test_63fbe44f64ec22de32b7ad8e1ed84c1375d50f7c',
    },

    getConfig ($this) {
        $this.axios.get('config')
            .then(response => {
                $this.theme.config.PAYSTACK_PK = response.data.PAYSTACK_PK;
            })
    },
    
    install: function(Vue){
      Object.defineProperty(Vue.prototype, 'theme', {
        get () { return theme }
      })
    },

}

export default theme;