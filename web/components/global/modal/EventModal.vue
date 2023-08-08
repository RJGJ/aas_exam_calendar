<template>
  <transition name="fade">
    <div v-if="active" :class="attr['modal']">
      <div :class="attr['modal__card']">
        <svg
          @click="closeModal"
          :class="attr['modal__close']"
          xmlns="http://www.w3.org/2000/svg"
          height="1em"
          viewBox="0 0 384 512"
        >
          <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/>
        </svg>

        <div :class="attr['modal__content']">
          <h2>Events</h2>

          <div :class="attr['modal__events']">
            <div
              v-for="(event, idx) in events"
              :key="idx"
              :class="attr['modal__events-item']"
              :style="{ '--bg_color': event.color }"
            >
              <p>{{ event.title }}</p>
              <svg @click="updateEvent(event)" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/></svg>
              <svg @click="deleteEvent(event)" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
  export default {
    data() {
      return {
        active: false,
        events: []
      }
    },
    methods: {
      closeModal() {
        this.active = false
      },
      openModal() {
        this.active = true
      },
      deleteEvent(event) {
        this.$nuxt.$emit('delete-event', event)
      },
      updateEvent(event) {
        this.$nuxt.$emit('update-event', event)
      }
    },
    mounted() {
      this.$nuxt.$on('open-event-modal', events => {
        this.events = events
        this.openModal()
      })

      this.$nuxt.$on('close-event-modal', this.closeModal)
    },
    beforeDestroy() {
      this.$nuxt.$off('open-event-modal')
      this.$nuxt.$off('close-event-modal')
    }
  }
</script>

<style lang="stylus" module="attr">
  .modal
    position: fixed
    top: 0
    left: 0
    right: 0
    bottom: 0
    display: flex
    justify-content: center
    align-items: center
    background: rgba(0, 0, 0, 0.5)
    &__close
      display: block
      margin-left: auto
      cursor: pointer
    &__card
      width: 500px
      padding: 20px
      background: var(--theme_white)
      border-radius: 10px
    &__events
      &-item
        padding: 15px
        display: flex
        border-radius: 10px
        background: var(--bg_color)
        &:not(:last-child)
          margin-bottom: 20px
        p
          flex: 1 0 5%
        svg
          margin-left: 10px
          cursor: pointer
</style>
