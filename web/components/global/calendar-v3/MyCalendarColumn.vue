<template>
  <div :class="attr['column']">
    <div :class="attr['column__top']">
      <span :class="attr['column__top-day']">{{ date.format('ddd') }}</span>
      <span :class="attr['column__top-month']">{{ date.format('MMM') }}</span>
      <span :class="attr['column__top-date']">{{ date.format('DD') }}</span>
    </div>
    <div :class="attr['column__bottom']">
      <my-calendar-event
        v-for="key in 5"
        :key="key"
        :date="date"
        :finished="date.diff($moment(), 'days') < 0"
      />
    </div>
  </div>
</template>

<script>
  export default {
    components: {
      MyCalendarEvent: () => import('./MyCalendarEvent'),
    },
    props: {
      start_date: {
        type: Object,
        default: () => ({})
      },
      offset: {
        type: Number,
        require: true
      },
      events: {
        type: Array,
        required: true
      }
    },
    data({ start_date, offset }) {
      return {
        date: start_date.clone().add(offset, 'days')
      }
    },
    watch: {
      start_date: {
        handler(new_val, old_val) {
          if ({ ...new_val } == { ...old_val }) return
          this.date = new_val.clone().add(this.offset, 'days')
        },
        deep: true
      }
    }
  }
</script>

<style lang="stylus" module="attr">
  .column
    border: 1px solid hsla(0,0%,54.5%,.2)
    &__top
      padding: 30px 10px
      background-color: #f7f7f7
      display: flex
      flex-direction: column
      span
        text-align: center
      &-day
        margin-bottom: 25px
        font-size: 35px
        font-weight: bold
      &-month
        font-size: 12px
        font-weight: lighter
      &-date
        font-size: 35px
        font-weight: bold
</style>
