<template>
  <div
    :class="[
      attr['event'],
      finished && attr['event--finished']
    ]"
  >
    <div :class="attr['event__top']">
      <nuxt-img
        src="assets/placeholders/info-booker-icon.svg"
        :class="attr['event__info-icon']"
      />
      <nuxt-img
        :src="event.instructor.image.path"
        :class="attr['event__instructor-image']"
      />
    </div>
    <div :class="attr['event__middle']">
      <div :class="attr['event__time']">{{ event.time }}</div>
      <div :class="attr['event__instructor-name']">{{ event.instructor.name }}</div>
      <div :class="attr['event__duration']">{{ event.duration }}</div>
      <div :class="attr['event__location']">{{ event.location }}</div>
    </div>
    <div :class="attr['event__bottom']">
      <button
        @click="!finished && book()"
        :class="attr['event__button']"
        :disabled="finished"
      >{{ finished ? 'Class is over' : 'Book now' }}</button>
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
      finished: {
        type: Boolean,
        required: true
      }
    },
    data({ date }) {
      return {
        event: {
          details: 'Ride Revolution’s signature 45-minute class. This beat-based high-intensity indoor cycling workout aims to harness your power and build your endurance. Your instructor will take you through jogs, sprints, hills, and dynamic choreographies—all while grooving to music that engages you and gets you moving.',
          instructor: {
            name: 'Tina Moran',
            image: {
              path: '/assets/placeholders/MENGMANAPULPERET.png'
            }
          },
          time: date.format('hh:mm a'),
          location: 'Online Class',
          duration: '45 Minutes',
        }
      }
    },
    methods: {
      book() {
        alert(1)
      }
    },
  }
</script>

<style lang="stylus" module="attr">
  .event
    padding: 10px
    text-align: center
    &:not(&:last-child)
      border-bottom: solid 1px hsla(0,0%,54.5%,.2)
    &--finished
      color: #53565c
      & ^[0]__top
        & ^[0]__instructor-image
          filter: saturate(0)
      & ^[0]__middle
        & ^[0]__location
          color: inherit
    &__top
      margin-bottom: 10px
      display: flex
      flex-direction: column
      // align-items: center
      & ^[0]__info-icon
        height: 18px
        margin-left: auto
        width: 18px
        cursor: pointer
      & ^[0]__instructor-image
        width: 65px
        height: 65px
        margin: 0 auto
    &__middle
      margin-bottom: 20px
      *
        margin-bottom: 5px
      & ^[0]__time
        font-size: 18px
        text-transform: uppercase
        font-weight: bold
      & ^[0]__instructor-name
        font-size: 16px
      & ^[0]__duration
        font-size: 14px
        font-weight: lighter
        text-transform: uppercase
      & ^[0]__location
        font-size: 14px
        color: rgb(28, 171, 149)
    &__bottom
      & ^[0]__button
        padding: 15px 20px
        text-transform: uppercase
        border: solid 1px #26a48b
        color: #26a48b
        font-family: var(--merri_sans)
        font-weight: bold
        transition: 0.4s ease-in-out
        width: 100%
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
