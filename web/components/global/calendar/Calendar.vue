<template>
  <div :class="attr['calendar']">
    <div :class="attr['calendar__top']">
      <button @click="$emit('current-month')">current month</button>
    </div>
    <div :class="attr['calendar__inner']">
      <div :class="attr['calendar__days']">
        <div :class="attr['calendar__day']" v-for="(day, key) in days" :key="key">{{ day }}</div>
      </div>
      <div :class="attr['calendar__rows']">
        <CalendarRow
          v-for="(_, i) in Array(6)"
          :key="i"
          :week="i"
          :first_day="first_day"
          :current_month="first_day.month()"
          :class="attr['calendar__row']"
          :events="payload.events"
        />
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      payload: {
        type: Object,
        default: () => ({
          date: {},
          events: []
        })
      }
    },
    data({ $moment }) {
      return {
        days: [
          'sunday',
          'monday',
          'tuesday',
          'wednesday',
          'thursday',
          'friday',
          'saturday',
        ],
        first_day: $moment()
      }
    },
    mounted() {
      this.first_day = this.payload.date.clone().date(1)
      // console.log(this.first_day)
    },
    watch: {
      'payload': {
        handler(newValue) {
          this.first_day = this.$moment(newValue.date.date(1))
        },
        deep: true
      }
    },
  }
</script>

<style lang="stylus" module="attr">
  .calendar
    &__top
      display: flex
      justify-content: center
      background-color: black
      button
        padding: 5px
        color: white
        cursor: pointer
    &__inner
      & ^[0]__days
        display: flex
        & ^[0]__day
          flex-grow: 1
          flex-basis: calc(100% / 7)
          text-align: center
      & ^[0]__rows
        border-top: solid 1px black
        border-left: solid 1px black
</style>
