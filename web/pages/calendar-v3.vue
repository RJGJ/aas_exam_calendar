<template>
  <div>
    <my-calendar :events="events" />
  </div>
</template>

<script>
  export default {
    components: {
      MyCalendar: () => import('~/components/global/calendar-v3/MyCalendar'),
    },
    data() {
      return {
        events: []
      }
    },
  }
</script>

<style lang="stylus" scoped>

</style>
