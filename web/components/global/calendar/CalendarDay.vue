<template>
  <div :class="[
    attr['date'],
    date.month() !== current_month && attr['date--inactive']
  ]">
    <span :class="attr['date__the-date']">{{ date.date() }}</span>
    <!-- <pre>{{ dayHasEvent }}</pre> -->
    <div v-if="dayHasEvent" :class="attr['date__events-wrap']">
      <div
        v-for="(event, idx) in eventsInDay"
        :key="idx"
        :style="{ '--bg_color': event.color }"
        :class="attr['date__event']"
      >&nbsp;</div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      date: {
        type: Object,
        default: () => ({})
      },
      current_month: {
        type: Number,
        default: () => 0
      },
      events: {
        type: Array,
        required: true
      }
    },
    computed: {
      dayHasEvent() {
        const today = this.$moment()
        return this.events
          .map(event => {
            return this.date.isBetween(
              this.$moment(event.from),
              this.$moment(event.to).clone().add(1, 'days')
            )
          })
          .filter(is_between => is_between)
          .length > 0
      },
      eventsInDay() {
        return this.events.filter(event => {
          // check if day in selected
          console.log(event.days.map((_, i) => i ), this.date.day())
          if (!event.days.map((_, i) => i ).includes(this.date.day())) {
            return []
          }
          return this.date.isBetween(
            this.$moment(event.from),
            this.$moment(event.to).clone().add(1, 'days')
          )
        })
      }
    },
  }
</script>

<style lang="stylus" module="attr">
  .date
    aspect-ratio: 1
    padding: 5px
    transition: 200ms ease
    border-right: solid 1px black
    &--inactive
      color: #aaa
    &:hover
      background-color: #eee
    &__events-wrap
      & ^[0]__event
        margin: 5px
        height: 8px
        aspect-ratio: 1
        border-radius: 50%
        background-color: var(--bg_color)
</style>
