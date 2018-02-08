<template>
<div>
    <br> <span v-show="errors.has(field)">{{errors.first(field)}}</span>
</div>
</template>

<script>
export default {
  props: ['field']
}
</script>

<style>

</style>

