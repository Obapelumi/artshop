<template>
<form @submit.prevent="uploadImage">
<div class="dropbox col-md-4 col-sm-6">
  <p v-show="!image">
     <input type="file" id="chooseFiles" @change="readImage" class="input-file" accept="image/*"/>
     Drag your file(s) here to begin<br> or click to browse <br>
     <i class="fa fa-undo" style="cursor: pointer;" @click="restore"> Reset</i>
  </p>
  <img :src="imagePath" v-if="true" alt="" :id="imageId"/>
  <div class="remove-image" v-if="image"  @click="deleteImage"><i class="fa fa-pencil"></i></div>
</div> 
<transition name="fade">
  <div class="button-cart-right col-md-2" v-if="update && show">
    <button class="btn btn-transparent" style="" type="submit"> <i class="fa fa-upload"></i> Upload Image</button>
  </div>
</transition> <div class="clearfix"></div>
</form>
</template>
<script>
export default {
	props: ['meta', 'index', 'upload', 'file'],
	data () {
		return {
      image: true,
      update: false,
      imageId: 'art-image-' + this.index,
			show: true,
			tempObj: {
				image: this.image,
			},
			metaObj: this.meta.image,
		}
	},
	computed: {
		imageObj () {
			this.tempObj.image = this.image;
			var data = Object.assign(this.tempObj, this.metaObj);
			return data;
    },
    imagePath () {
      if (this.file) {
        if (this.file.isfake === true) {
          return '/images/logo/favicon.png';
        }
        else {
          return this.theme.imagePath(this.file.path);
        }
      }
      else {
        return '/images/logo/favicon.png';
      }
    }
	},
	methods: {
        readImage (e) {
          var fileReader = new FileReader();
          var $this = this;
          fileReader.onload = (e) => {
            $this.image = e.target.result;
            document.getElementById($this.imageId).src = e.target.result;
          }
          fileReader.readAsDataURL(e.target.files[0]);
          this.update = true;
        },
        deleteImage () {
          this.image = false;
          this.update = false;
        	document.getElementById(this.imageId).src = '';
        },
        restore () {
          this.image = true;
          this.update = false;
        	document.getElementById(this.imageId).src = this.imagePath;
        },
        uploadImage () {
          var $this = this;
          if (this.file) {
            if ($this.file.isfake === true) {
              $this.theme.imageUpload($this);
            }
            else {
              $this.theme.imageUpdate($this);
            }
          }
          else {
            $this.theme.imageUpload($this);
          }
        }
	},
	watch: {
		upload () {
			if (this.upload === true) {
				this.theme.imageUpload(this);
			}
		},
	}
}
</script>

<style>
  .dropbox {
    outline: 2px dashed grey; /* the dash box */
    outline-offset: -10px;
    /*background: lightcyan;*/
    color: dimgray;
    padding: 10px 10px;
    height: 180px; /* minimum height */
    position: relative;
    border-radius: 5px;
    margin-bottom: 30px;
  }

  .input-file {
    opacity: 0; /* invisible but it's there! */
    width: 100%;
    height: 400px;
    position: absolute;
    cursor: pointer;
    z-index: 15;
  }

  .dropbox:hover {
    background: rgba(196, 214, 176, 0.2); /* when mouse over to the drop zone, change color */
  }

  .dropbox p {
    font-size: 1.2em;
    text-align: center;
    padding: 50px 0;
  }
  .dropbox img {
    padding: 10px 10px;
    max-height: 180px; /* minimum height */
    width: 120%;
    position: relative;  
    top: -10px;
    /*opacity: 0.4;	*/
    z-index: 0;
  }
  .dropbox .remove-image {
  	position: absolute;
  	top: -10px;
  	left: 90%;
  	text-align: center;
  	color: white;
  	font-size: 15px;
	  cursor: pointer;
  	height: 25px;
  	width: 25px;
  	background-color: #0dab76;
    padding: 2px;
  	border-radius: 50%;
  	opacity: 0.7
  }
</style>