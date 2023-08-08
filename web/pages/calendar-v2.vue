<template>
  <div class="container">
    <div class="top">
      <div>
        <button id="prev-btn">
          {{ date.clone().subtract(1, 'months').format('MMMM') }}
        </button>
      </div>
      <div>
        <span>{{ date.format('MMMM DD YYYY') }}</span>
      </div>
      <div>
        <button id="next-btn">
          {{ date.clone().add(1, 'months').format('MMMM') }}
        </button>
      </div>
    </div>
    <div class="calendar" id="calendar"></div>
    <div class="add-event add-event--active" id="add-event">
      <div class="add-event__form-wrap">
        <form id="add-form" class="add-event__form">
          <div class="add-event__input-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="add-event__input" placeholder="Enter event title">
            <span class="add-event__error-dispay">&nbsp;</span>
          </div>
          <div class="add-event__two-col">
            <div class="add-event__input-group">
              <label for="from">From</label>
              <input type="date" name="from" id="from" class="add-event__input" placeholder="Enter starting date" readonly>
              <span class="error-display">&nbsp;</span>
            </div>
            <div class="add-event__input-group">
              <label for="to">To</label>
              <input type="date" name="to" id="to" class="add-event__input" placeholder="Enter end date">
              <span class="add-event__error-display">&nbsp;</span>
            </div>
          </div>
          <div class="add-event__days">
            <ul class="add-event__days-wrap">
              <li class="add-event__day day-0">
                <input
                  type="checkbox"
                  data-day="0"
                  name="day-0"
                  id="day-0"
                />
                <label for="day-0">Sunday</label>
              </li>
              <li class="add-event__day day-1">
                <input
                  type="checkbox"
                  data-day="1"
                  name="day-1"
                  id="day-1"
                />
                <label for="day-1">Monday</label>
              </li>
              <li class="add-event__day day-2">
                <input
                  type="checkbox"
                  data-day="2"
                  name="day-2"
                  id="day-2"
                />
                <label for="day-2">Tuesday</label>
              </li>
              <li class="add-event__day day-3">
                <input
                  type="checkbox"
                  data-day="3"
                  name="day-3"
                  id="day-3"
                />
                <label for="day-3">Wednesday</label>
              </li>
              <li class="add-event__day day-4">
                <input
                  type="checkbox"
                  data-day="4"
                  name="day-4"
                  id="day-4"
                />
                <label for="day-4">Thursday</label>
              </li>
              <li class="add-event__day day-5">
                <input
                  type="checkbox"
                  data-day="5"
                  name="day-5"
                  id="day-5"
                />
                <label for="day-5">Friday</label>
              </li>
              <li class="add-event__day day-6">
                <input
                  type="checkbox"
                  data-day="6"
                  name="day-6"
                  id="day-6"
                />
                <label for="day-6">Saturday</label>
              </li>
            </ul>
          </div>
          <div class="form-actions">
            <button type="submit">add</button>
            <button>edit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
  import gsap from 'gsap'

  export default {
    data({ $moment }) {
      return {
        date: $moment(),
        form_data:{
          name: '',
          from: {},
          to: {},
        },
        events: [
          {
            title: 'test event',
            from: $moment(),
            to: $moment().add(7, 'days'),
            days: [0, 1, 2, 3, 4, 5, 6]
          }
        ]
      }
    },
    methods: {
      buildCalendar(current_date) {
        const CALENDAR_SIZE = [7, 6] // days per week, weeks per month
        const calendar_element = document.querySelector('#calendar')
        calendar_element.innerHTML = ''
        let day_counter = this.shiftDates(current_date)
        // week loop
        for (let week_idx = 0; week_idx < CALENDAR_SIZE[1]; week_idx++) {
          const row = this.createRow()
          calendar_element.appendChild(row)
          // days loop
          for (let day_idx = 0; day_idx < CALENDAR_SIZE[0]; day_idx++) {
            const day = this.createDay(day_counter.clone())
            row.appendChild(day)
            day_counter.add(1, 'days')
          }
        }
      },
      addListeners() {
        document.querySelector('#prev-btn').addEventListener('click', () => {
          this.changeMonth(false)
        })
        document.querySelector('#next-btn').addEventListener('click', () => {
          this.changeMonth(true)
        })

        const add_event_el = document.querySelector('#add-event')
        add_event_el.addEventListener('click', (ev) => {
          if (ev.target != add_event_el) return
          this.closeEventForm()
        })

        document.querySelectorAll('.add-event__days input[type="checkbox"]')
          .forEach(checkbox => {
            checkbox.addEventListener('click', () => {
              console.log(checkbox)
            })
          })

        document.querySelector('#to').addEventListener('change', (ev) => {
          const to = ev.target
          const from = document.querySelector('#from')

          if (!to.value && !from.value) return

          // calculate the days of the week that will be visible
          this.showDaysOfWeek(this.$moment(to.value), this.$moment(from.value))
        })

        document.querySelector('#add-form').addEventListener('submit', event => {
          event.preventDefault()
          this.addEvent()
        })
      },
      showDaysOfWeek(to, from){
        console.log('showDaysOfWeek')
        const days_duration = to.diff(from, 'days')
        // const day_checkboxes = [...document.querySelectorAll('.add-event__day')]
        const checkbox_containers = [...new Array(7)].map((_, key) => {
          const container = document.querySelector(`.add-event__day.day-${key}`)
          // container.classList.add('hidden')
          return container
        });
        [...Array(days_duration)].map((_, key) => {
          const date = from.clone().add(key, 'days')
          const tommorow = date.clone().add(1, 'days')
          checkbox_containers[date.day()].classList.remove('hidden')
          checkbox_containers[tommorow.day()].classList.remove('hidden')
        })
      },
      shiftDates(current_date) {
        // set to first day of the month
        const first_date = current_date.clone().date(1)
        return first_date
          .clone()
          .subtract(first_date.day(), 'days')
      },
      createRow() {
        const row = document.createElement('div')
        row.classList.add('calendar__row')
        return row
      },
      createDay(date) {
        const day = document.createElement('div')
        day.classList.add('calendar__day')
        if (date.month() !== this.date.month()) {
          day.classList.add('calendar__day--diff-month')
        }
        const events = this.getEventsOfDay(date)
        day.innerHTML = `
          <div class="calendar__inner">
            <span class="calendar__day-date">${ date.date() }</span>
            ${events.map(event => `
              <div class="calendar__events-wrap">
                <div class="calendar__event" style="--event_color: ${ event.color };">
                  <span class="calendar__event-title">${ event.title }</span>
                </div>
              </div>
            `).join('')}
          </div>
        `
        day.setAttribute('data-date', date.format('YYYY-MM-DD'))
        day.addEventListener('click', () => {
          this.handleDateClick(date)
        })
        return day
      },
      getEventsOfDay(date) {
        return this.events.filter(event => {
          const is_within_range =
            date.date() >= event.from.date() && date.date() <= event.to.date() &&
            date.month() >= event.from.month() && date.month() <= event.to.month()
          const day_included = true
          return is_within_range && day_included
        })
      },
      changeMonth(increment) {
        if (increment) {
          this.date = this.date.clone().add(1, 'months')
        } else {
          this.date = this.date.clone().subtract(1, 'months')
        }
        this.buildCalendar(this.date)
      },
      handleDateClick(date) {
        this.hideDaysOfWeek()
        this.clearToDate()
        this.openEventForm(date)
        this.setToDateLimit(date)
        this.form_data.from = date
      },
      hideDaysOfWeek() {
        [...new Array(7)].map((_, key) => {
          const container = document.querySelector(`.add-event__day.day-${key}`)
          container.classList.add('hidden')
        });
      },
      clearToDate() {
        document.querySelector('#to').value = ''
      },
      setToDateLimit(date) {
        document.querySelector('#to').min = date
          .clone()
          .add(1, 'days')
          .format('YYYY-MM-DD')
      },
      openEventForm(date=null) {
        const add_event_el = document.querySelector('#add-event')
        add_event_el.classList.add('add_event--active')
        const timeline = gsap.timeline()
        timeline
          .to(add_event_el, { zIndex: 2, duration: 0 })
          .to(add_event_el, { opacity: 1, duration: 0.4 })
        timeline.play()
        document.body.style.overflow = 'hidden'
      },
      closeEventForm() {
        const add_event_el = document.querySelector('#add-event')
        add_event_el.classList.remove('add_event--active')
        const timeline = gsap.timeline()
        timeline
          .to(add_event_el, { opacity: 0, duration: 0.4 })
          .to(add_event_el, { zIndex: -1, duration: 0 })
        timeline.play()
        document.body.style.overflow = 'auto'
      },
      addEvent() {
        const days = [...document.querySelectorAll('input[type="checkbox"]:checked')]
            .map(checkbox => parseInt(checkbox.id.split('-')[1]))
        this.events.push({
          title: document.querySelector('#title').value,
          from: this.$moment(document.querySelector('#from').value),
          to: this.$moment(document.querySelector('#to').value),
          days
        })
        this.buildCalendar(this.$moment())
        console.log(this.events)
      },
      editEvent() {
        //
      }
    },
    mounted() {
      this.buildCalendar(this.date)
      this.addListeners()
    },
    watch: {
      'form_data.from'(newValue, oldValue) {
        const datepicker_value = newValue.toDate()
        const from_input = document.querySelector('input#from')
        from_input.value = datepicker_value.toISOString().substring(0,10);
      }
    },
  }
</script>

<style lang="stylus">
  .container
    //

  .top
    display: flex
    justify-content: space-between

  .calendar
    display: flex
    flex-direction: column
    &__row
      display: flex
      flex-direction: row
      & ^[0]__day
        flex-basis: calc(100% / 7)
        aspect-ratio: 1
        &--diff-month
          color: rgba(0, 0, 0, 0.4)
        & ^[0]__inner
          & ^[0]__day-date
            font-size: 16px
          & ^[0]__events-wrap
            & ^[0]__event
              & ^[0]__event-title
                border: solid 1px var(--event_color)
                text-overflow: ellipsis

  .add-event
    display: flex
    opacity: 0
    z-index: -1
    position: fixed
    top: 0
    left: 0
    width: 100vw
    min-height: 100vh
    justify-content: center
    align-items: center
    background-color: rgba(0, 0, 0, 0.3)
    &--active
      // display: flex
    &__form-wrap
      max-width: 600px
      width: 500px
      margin: 0 20px
      padding: 20px
      border-radius: 8px
      background-color: var(--theme_white)
      & ^[0]__form
        & ^[0]__input-group
          display: flex
          flex-flow: column
          & ^[0]__input
            border-bottom: solid 1px black
            padding: 10px 0
          & ^[0]__error-display
            //
        & ^[0]__two-col
          display: flex
          flex-flow: row wrap
          justify-content: space-between
        & ^[0]__days
          & ^[0]__days-wrap
            display: flex
            flex-flow: row wrap
            list-style-type: none
            padding-left: 0
            & ^[0]__day
              padding-right: 5px
              display: flex
              flex-flow: row wrap
              justify-content: flex-start
              align-items: center
              &.hidden
                display: none
              input[type="checkbox"]
                -webkit-appearance: auto
                height: 10px
                width: 100%
</style>
