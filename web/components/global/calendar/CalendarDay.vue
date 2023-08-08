<template>
  <div
    :class="[
      attr['date'],
      date.month() !== current_month && attr['date--inactive']
    ]"
    @click="openModal"
  >
    <span :class="attr['date__the-date']">{{ date.date() }}</span>
    <div v-if="dayHasEvent" :class="attr['date__events-wrap']">
      <div
        v-for="(event, idx) in eventsInDay"
        :key="idx"
        :style="{ '--bg_color': event.color }"
        :class="attr['date__event']"
      >{{ event.title }}</div>
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
          const checked_days = event.days.reduce((acc, day, idx) => {
            return day.checked ? [ ...acc, idx ] : acc
          }, [])

          console.log(checked_days)

          if (checked_days.includes(this.date.day())) {
            return false
          }
          return this.date.isBetween(
            this.$moment(event.from),
            this.$moment(event.to).clone().add(1, 'days')
          )
        })
      }
    },
    methods: {
      openModal() {
        if (!this.eventsInDay.length) return
        this.$nuxt.$emit('open-event-modal', this.eventsInDay)
      }
    }
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
        padding: 5px 10px
        border-radius: 5px
        background-color: var(--bg_color)
        font-size: 10px
        font-weight: normal
</style>
