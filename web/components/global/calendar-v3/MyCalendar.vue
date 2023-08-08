<template>
  <div :class="attr['calendar']">
    <div :class="attr['calendar__actions']">
      <button
        @click="initiateAddClass"
        :class="attr['button']"
      >Add a Class</button>
    </div>
    <div :class="attr['calendar__top']">
      <button
        :class="[
          attr['calendar__prev-week']
        ]"
        @click="week_offset--"
      >
        <nuxt-img
          src="assets/placeholders/date-prev.svg"
          :class="attr['calendar__top-btn']"
        />
      </button>
      <span :class="attr['calendar__week-text']">
        {{ startDate.format('DD MMM') }} - {{ startDate.clone().add(7, 'days').format('DD MMM') }}
      </span>
      <button
        :class="[
          attr['calendar__next-week']
        ]"
        @click="week_offset++"
      >
        <nuxt-img
          src="assets/placeholders/date-prev.svg"
          :class="attr['calendar__top-btn']"
        />
      </button>
    </div>
    <div :class="attr['calendar__body']">
      <my-calendar-column
        v-for="key in 8"
        :key="key"
        :class="attr['calendar__column']"
        :offset="key - 1"
        :start_date="startDate"
        :events="getEvents(startDate, key - 1)"
      />
    </div>
    <div v-if="add_form_active" ref="add-modal" :class="attr['add-modal']" @click="closeAddForm">
      <div :class="attr['add-modal__card']">
        <my-calendar-add-form />
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    components: {
      MyCalendarColumn: () => import('./MyCalendarColumn'),
      MyCalendarAddForm: () => import('./MyCalendarAddForm')
    },
    props: {
      events: {
        type: Array,
        required: true
      },
    },
    data({ $moment }) {
      return {
        current_date: $moment(),
        week_offset: 0,
        add_form_active: false
      }
    },
    computed: {
      startDate() {
        return this.current_date
          .clone()
          .day(1)
          .add(this.week_offset * 8, 'days')
      }
    },
    methods: {
      initiateAddClass() {
        this.add_form_active = true
      },
      closeAddForm(ev) {
        if (ev.target != this.$refs['add-modal']) return
        this.add_form_active = false
      },
      getEvents(day, offset) {
        return []
      }
    },
    mounted () {
      //
    },
  }
</script>

<style lang="stylus" module="attr">
  .calendar
    padding: 10px
    position: relative
    &__actions
      margin-bottom: 20px
    &__top
      margin-bottom: 40px
      display: flex
      justify-content: space-between
      align-items: center
      & ^[0]__top-btn
        cursor: pointer
      & ^[0]__next-week
        & ^[0]__top-btn
          transform: rotateZ(180deg)
      & ^[0]__week-text
        font-size: 30px
        text-transform: uppercase
        font-weight: bold
        color: #53565c
    &__body
      display: flex
      & ^[0]__column
        flex: 1 0

  .add-modal
    position: absolute
    top: 0
    left: 0
    width: 100%
    height: 100vh
    display: flex
    justify-content: center
    align-items: center
    background-color: rgba(0, 0, 0, 0.4)
    &__card
      padding: 20px
      background-color: white
      border-radius: 10px

  .button
    padding: 15px 20px
    text-transform: uppercase
    border: solid 1px #26a48b
    color: #26a48b
    font-family: var(--merri_sans)
    font-weight: bold
    transition: 0.4s ease-in-out
    cursor: pointer
    &:hover:not(&:disabled)
      color: var(--theme_white)
      background: #26a48b
      box-shadow: 0 0 30px #26a48bd0
    &:disabled
      cursor: not-allowed
      border: solid 1px #a8a8a8
      color: #a8a8a8
</style>
