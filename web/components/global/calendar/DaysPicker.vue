<template>
  <div :class="attr['days']">
    <div :class="attr['days__list']">
      <div
        v-for="(day, idx) in availableDays"
        :key="idx"
        :class="attr['days__item']"
      >
        <input
          type="checkbox"
          :name="day.name"
          :id="day.name"
          v-model="day.checked"
        />
        <label :for="day.name">{{ day.name }}</label>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      payload: {
        type: Array,
        default: () => []
      },
    },
    data({ $attrs }) {
      return {
        dates: $attrs.value
      }
    },
    computed: {
      availableDays() {
        // return this.dates.filter(date => !date.disabled)
        return this.dates
      }
    },
    watch: {
      dates:{
        handler(new_val, old_val) {
          if ({ ...new_val } === { ...old_val }) return
          console.log('payload changed')
          this.$emit('input', new_val)
        },
        deep: true
      },
      '$attrs.value': {
        handler(new_val, old_val) {
          if ({ ...new_val } === { ...old_val }) return
          this.dates = new_val
        },
        deep: true
      }
    },
  }
</script>

<style lang="stylus" module="attr">
  .days
    &__list
      display: flex
      flex-flow: column
      & ^[0]__item
        input
          margin-right: 10px
        &:not(&:last-child)
          margin-bottom: 10px
    input
      -webkit-appearance: auto

</style>
