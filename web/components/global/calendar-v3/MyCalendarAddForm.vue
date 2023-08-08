<template>
  <validation-observer
    @click="() => {}"
    tag="form"
    :class="attr['form']"
  >
    <div :class="attr['form__group']">
      <h2>Add a Class</h2>
    </div>

    <validation-provider
      :class="attr['form__group']"
      name="time"
    >
      <label for="time">Time</label>
      <input type="time" name="time" id="time" v-model="form_data.time" />
      <span :class="attr['form__error']">&nbsp;</span>
    </validation-provider>

    <validation-provider
      :class="[
        attr['form__group'],
        attr['form__group--half']
      ]"
      name="from"
    >
      <label for="from">From</label>
      <input type="date" name="from" id="from" v-model="form_data.from" />
      <span :class="attr['form__error']">&nbsp;</span>
    </validation-provider>

    <validation-provider
      :class="[
        attr['form__group'],
        attr['form__group--half']
      ]"
      name="to"
    >
      <label for="to">To</label>
      <input type="date" name="to" id="to" v-model="form_data.to" />
      <span :class="attr['form__error']">&nbsp;</span>
    </validation-provider>

    <validation-provider
      :class="attr['form__group']"
      name="location"
    >
      <label for="location">Location</label>
      <select name="location" id="location" v-model="form_data.location">
        <option value="Online Class">Online Class</option>
        <option value="Greenbelt">Greenbelt</option>
      </select>
      <span :class="attr['form__error']">&nbsp;</span>
    </validation-provider>

    <validation-provider
      :class="attr['form__group']"
      name="duration"
    >
      <label for="duration">Duration</label>
      <input
        type="number"
        name="duration"
        id="duration"
        v-model="form_data.duration"
        placeholder="Enter Duration in minutes"
      />
      <span :class="attr['form__error']">&nbsp;</span>
    </validation-provider>

    <validation-provider
      :class="attr['form__group']"
      name="instructor"
    >
      <label for="instructor">Instructor</label>
      <select name="instructor" id="instructor" v-model="form_data.instructor">
        <option value="Tina Moran">Tina Moran</option>
        <option value="Kapitantutan">Kapitantutan</option>
      </select>
      <span :class="attr['form__error']">&nbsp;</span>
    </validation-provider>

    <validation-provider
      :class="attr['form__group']"
      name="details"
    >
      <label for="details">Details</label>
      <textarea
        name="details"
        id="details"
        v-model="form_data.details"
        placeholder="Enter Duration in minutes"
      ></textarea>
      <span :class="attr['form__error']">&nbsp;</span>
    </validation-provider>
  </validation-observer>
</template>

<script>
  export default {
    data() {
      return {
        form_data: {
          details: '',
          instructor: '',
          time: '',
          location: '',
          duration: null,
          from: '',
          to: '',
        }
      }
    },
  }
</script>

<style lang="stylus" module="attr">
  .form
    max-width: 500px
    display: flex
    flex-flow: row wrap
    justify-content: space-between
    &__group
      margin-bottom: 5px
      display: flex
      flex-direction: column
      flex: 0 0 100%
      label
        font-size: 14px
        margin-bottom: 3px
      input[type="text"],
      input[type="number"],
      input[type="time"],
      input[type="date"],
      select,
      textarea
        border: solid 1px var(--theme_gray)
        padding: 15px 20px
        border-radius: 3px
      &--half
        flex: 0 0 calc(50% - 10px)
        // margin-right: 50px
      & ^[0]__error
        font-size: 12px
        color: red
</style>
