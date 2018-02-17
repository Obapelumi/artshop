<template>
  <transition name="fade">
        <div class="artshop-modal" v-if="toggler">
			<button class="closer" @click="togglePopUp()"><i class="fa fa-times"></i></button>
            <div class="section">
                <div class="sn-newletter-style1">
                    <div class="container">
                    <div class="sperator text-center">
                        <p>ARE YOU SURE?</p>
                        <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
                    </div>
                    <div class="newletter-content">
                        <p>Are you sure you want to {{info}}</p>
                        <form>
                        <button type="submit" @click.prevent="confirm()" class="btn btn-color-white btn-background-white">Yes</button>
                        <button style="margin-left: 20px;" type="submit" @click.prevent="togglePopUp()" class="btn btn-color-white btn-background-white">No</button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>                
    </transition>  
</template>
<script>
export default {
  props: ['info'],
  data () {
      return {
          toggler: true,
      }
  },
  methods: {
    togglePopUp () {
        this.toggler = !this.toggler;
        this.$emit('close');
    },
    confirm () {
        this.$emit('confirm');
        this.togglePopUp();
    },
  },
}
</script>
<style>

</style>

