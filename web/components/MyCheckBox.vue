<template>
  <input type="checkbox" class="checkbox">
</template>

<script>
  export default {

  }
</script>

<style lang="stylus" scoped>
  .checkbox
    --checkbox_size: 20px
    position: relative
    // margin-left: var(--checkbox_size)
    &:before
      position: absolute
      // transform: translateY(50%)
      content: ''
      bottom: 0
      left: 0
      width: var(--checkbox_size)
      height: var(--checkbox_size)
      border: solid 2px red
    &:after
      position: absolute
      // transform: translateY(50%)
      bottom: 0
      left: 0
      content: ''
      width: var(--checkbox_size)
      height: var(--checkbox_size)
      background-color: yellow
</style>
