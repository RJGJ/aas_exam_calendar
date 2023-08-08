<template>
  <main v-if="loaded" :class="attr['main']">
    <div :class="attr['main__container']">
      <div :class="attr['main__left']">
        <div :class="attr['main__top']">
          <button type="button" @click="changeMonth(false)" :class="attr['main__prev-month']">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>
          </button>
          <div>{{ date.format('MMMM YYYY') }}</div>
          <button type="button" @click="changeMonth(true)" :class="attr['main__next-month']">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
          </button>
        </div>
        <ValidationObserver
          ref="form"
          tag="form"
          @submit.prevent="addEvent"
          :class="attr['main__form']"
        >
          <ValidationProvider
            tag="div"
            name="title"
            rules="required"
            v-slot="{ errors }"
            :class="attr['main__input-group']"
          >
            <label for="event">Event</label>
            <input
              v-model="form_data.title"
              type="text"
              name="event"
              id="event"
              placeholder="Enter event"
            />
            <validationTemplate :payload="{ errors }" />
          </ValidationProvider>
          <ValidationProvider
            tag="div"
            name="from"
            rules="required"
            v-slot="{ errors }"
            :class="attr['main__input-group']"
          >
            <label for="from">From</label>
            <input
              v-model="form_data.from"
              type="date"
              name="from"
              id="from"
              :min="$moment().format('YYYY-MM-DD')"
            />
            <ValidationTemplate :payload="{ errors }" />
          </ValidationProvider>
          <ValidationProvider
            tag="div"
            name="to"
            rules="required"
            v-slot="{ errors }"
            :class="attr['main__input-group']"
          >
            <label for="to">To</label>
            <input
              v-model="form_data.to"
              type="date"
              name="to" id="to"
              :min="minimumToDate"
            />
            <ValidationTemplate :payload="{ errors }" />
          </ValidationProvider>
          <div :class="attr['main__input-group']">
            <DaysPicker
              v-show="form_data.from && form_data.to"
              v-model="form_data.days"
            />
          </div>

          <div :class="attr['main__actions']">
            <ButtonTemplate
              button_type="submit"
              :label="form_data.id ? 'Update' : 'Add'"
              :status="2"
            />
          </div>
        </ValidationObserver>
      </div>
      <div :class="attr['main__right']">
        <Calendar :payload="calendar_payload" @current-month="setToCurrentMonth"/>
      </div>
    </div>

    <!-- modal -->
    <EventModal />
  </main>
</template>

<script>
import { ValidationProvider } from 'vee-validate'

  export default {
    components: {
      DaysPicker: () => import('~/components/global/calendar/DaysPicker.vue'),
      ValidationProvider
    },
    data: () => ({
      loaded: false,
      records: null,
      page: { title: 'Home' },
      calendar_payload: {
        date: {},
        events: []
      },
      date: {},
      months: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
      ],
      form_data: {
        id: null,
        title: '',
        from: '',
        to: '',
        color: '',
        days: [
          { name: 'Sunday', checked: false, disabled: false },
          { name: 'Monday', checked: false, disabled: false },
          { name: 'Tuesday', checked: false, disabled: false },
          { name: 'Wednesday', checked: false, disabled: true },
          { name: 'Thursday', checked: false, disabled: false },
          { name: 'Friday', checked: false, disabled: false },
          { name: 'Saturday', checked: false, disabled: false },
        ]
      }
    }),
    computed: {
      /**
       * The minimum selectable date for the TO date
       */
      minimumToDate() {
        if (this.form_data.from) {
          return this
            .$moment(this.form_data.from)
            .clone()
            .add(1, 'days')
            .format('YYYY-MM-DD')
        }
        return this.form_data.from
      },
      /**
       * used to watch two properties
       */
      fromAndTo() {
        return [
          this.form_data.from,
          this.form_data.to
        ]
      }
    },
    methods: {
      /**
       * increments and decrements the current month
       * based on the `increment` argument
       *
       * @param {Boolean} increment
       */
      changeMonth(increment) {
        if (increment) {
          this.date = this.date.clone().add(1, 'months')
        } else {
          this.date = this.date.clone().subtract(1, 'months')
        }
        this.calendar_payload.date = this.date
      },
      /**
       * sets the calendar's month to the current month
       */
      setToCurrentMonth() {
        this.date = this.$moment()
        this.calendar_payload.date = this.date
      },
      /**
       * Calculates available days of the week
       * using the FROM and TO dates
       */
      setAvailableDays() {
        const
          from = this.$moment(this.form_data.from),
          to = this.$moment(this.form_data.to),
          difference = to.diff(from, 'days')
        if (Number.isNaN(difference)) return

        let available_date = []
        for (let index = 0; index <= difference; index++) {
          const day_index = this.$moment().add(index, 'days')
          available_date.push(day_index.day())
          console.log(day_index.day())
        }
        // remove duplicates
        available_date = [ ...new Set(available_date) ]

        this.form_data.days = this.form_data.days.map((day, idx) => {
          // console.log(available_date.includes(idx), idx)
          const result = {
            ...day,
            disabled: !available_date.includes(idx),
          }
          // console.log(result)
          return result
        })
      },
      randomColor() {
        const colors = [
          '#CDF1AE',
          '#90DACC',
          '#F7E9D1',
          '#F4D2B5',
          '#EAB7B7',
          '#B89ACF'
        ]

        return colors[Math.floor(Math.random() * colors.length)]
        // return '#' + Math.floor(Math.random()*16777215).toString(16)
      },
      async addEvent() {
        const valid = await this.$refs.form.validate() || null
        if (!valid) {
          this.$scrollTo('.validate', {
            offset: -250
          })
          return
        }
        const to_submit = {
          ...this.form_data,
          from: this.$moment(this.form_data.from).format('YYYY-MM-DD'),
          to: this.$moment(this.form_data.to).format('YYYY-MM-DD'),
          days: this.form_data.days.reduce((acc, day, idx) => {
            if (!day.checked) return acc
            return [ ...acc, idx ]
          }, [])
        }

        if (this.form_data.id) {
          const response = await this.$axios.$post('/web/events/' + this.form_data.id, { ...to_submit, _method: 'PATCH' })
          this.calendar_payload.events = this.calendar_payload.events.map(item => {
            if (item.id === response.record.id) {
              return response.record
            }
            return item
          })
        } else {
          to_submit.color = this.randomColor()
          const response = await this.$axios.$post('/web/events', to_submit)
          this.calendar_payload
            .events
            .push({
              ...response.record,
              days: [
                { name: 'Monday', checked: false, disabled: false },
                { name: 'Tuesday', checked: false, disabled: false },
                { name: 'Wednesday', checked: false, disabled: true },
                { name: 'Thursday', checked: false, disabled: false },
                { name: 'Friday', checked: false, disabled: false },
                { name: 'Saturday', checked: false, disabled: false },
                { name: 'Sunday', checked: false, disabled: false },
              ].map((day, idx) => ({
                ...day,
                checked: !response.record.days.includes(idx)
              }))
            })
        }

        // reset fields
        this.form_data = {
          title: '',
          from: '',
          to: '',
          days: [
            { name: 'Monday', checked: false, disabled: false },
            { name: 'Tuesday', checked: false, disabled: false },
            { name: 'Wednesday', checked: false, disabled: true },
            { name: 'Thursday', checked: false, disabled: false },
            { name: 'Friday', checked: false, disabled: false },
            { name: 'Saturday', checked: false, disabled: false },
            { name: 'Sunday', checked: false, disabled: false },
          ]
        }

        this.$nextTick(() => {
          this.$refs.form.reset()
        })
      },
      async deleteEvent(event) {
        await this.$axios.$delete('/web/events/' + event.id)

        this.calendar_payload.events = this.calendar_payload.events.filter(item => item.id !== event.id)

        this.$nuxt.$emit('close-event-modal')
      },
      updateEvent(event) {
        this.form_data = {
          ...event,
          // days: event.days.map(day => ({
          //   ...day,
          //   checked
          // }))
        }
      }
    },
    watch: {
      fromAndTo() {
        this.setAvailableDays()
      },
      // /**
      //  * clears the TO date to avoid selecting invalid date
      //  */
      // 'form_data.from'() {
      //   this.form_data.to = ''
      // }
    },
    mounted () {
      setTimeout(() => {
        this.toggleModalStatus({ type: 'loader', status: true, item: { start: false } })
        this.loaded = true
      }, 500)
      this.date = this.$moment()
      this.calendar_payload.date = this.date

      // listeners
      this.$nuxt.$on('delete-event', event => this.deleteEvent(event))
      this.$nuxt.$on('update-event', event => {
        this.$nuxt.$emit('close-event-modal')
        this.updateEvent(event)
      })

    },
    beforeDestroy () {
      this.$nuxt.$off('delete-event')
      this.$nuxt.$off('update-event')
    },
    async asyncData ({ $axios, store }) {
      store.commit('global/modal/toggleModalStatus', { type: 'loader', status: true, item: { start: true } })


      const events_response = await $axios.$get('/web/events')
      const events = events_response.records.map(item => ({
          ...item,
          days: [
            { name: 'Monday', checked: false, disabled: false },
            { name: 'Tuesday', checked: false, disabled: false },
            { name: 'Wednesday', checked: false, disabled: true },
            { name: 'Thursday', checked: false, disabled: false },
            { name: 'Friday', checked: false, disabled: false },
            { name: 'Saturday', checked: false, disabled: false },
            { name: 'Sunday', checked: false, disabled: false },
          ].map((day, idx) => ({
            ...day,
            checked: !item.days.includes(idx)
          }))
        }))

      // store.commit('events/setAllEvents', events)

      return {
        calendar_payload: {
          date: {},
          events
        }
      }
    },
    head () {
      let host = process.env.BASE_URL

      return {
        link: [
          {
            rel: 'canonical',
            href: `${host}${this.$route.fullPath}`
          }
        ],
      }
    }
  }
</script>

<style lang="stylus" module="attr">
  .main
    position: relative
    width: 100vw
    height: 100vh
    margin: 0
    padding: 20px
    &__container
      max-width: 1280px
      margin: 10px auto
      display: flex
      flex-flow: row wrap
      & ^[0]__left
        margin-right: 20px
        flex: 0 0 35%
        & ^[0]__top
          display: flex
          justify-content: space-between
          margin-bottom: 10px
          & ^[0]__prev-month, ^[0]__next-month
            cursor: pointer
        & ^[0]__form
          input
            border: solid 1px black
          & ^[0]__input-group
            position: relative
            display: flex
            flex-direction: column
            margin-bottom: 25px
            label
              margin-bottom: 10px
            input
              padding: 10px 15px
          & ^[0]__from-to
            display: flex
            justify-content: space-between
          & ^[0]__actions
            display: flex
            justify-content: space-between
      & ^[0]__right
        flex: 1 0 5%

  @media  (max-width: 768px) and (min-width: 320px)
    .main
      &__container
        & ^[0]__left
          flex: 0 0 100%
          margin-bottom: 50px
</style>
