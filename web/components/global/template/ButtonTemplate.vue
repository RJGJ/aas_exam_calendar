<template lang="html">
  <nuxt-link
    :to="link"
    custom
    v-slot="{ href, navigate, isActive, isExactActive }"
    v-if="status == 0
  ">
    <a
      :class="[
        attr['button'],
        attr[template],
        attr['button--pointer'],
        isExactActive && attr['button--exact'],
        disabled && attr['button--disabled'],
        minimal && attr['button--minimal'],
        full_width && attr['button--full'],
        with_icon && attr['button--inline']
      ]"
      :href="href"
      @click="navigate"
    >
      <span>{{ label }}</span>
    </a>
  </nuxt-link>

  <a
    :href="link"
    target="_blank"
    :class="[
      attr['button'],
      attr[template],
      attr['button--pointer'],
      disabled && attr['button--disabled'],
      minimal && attr['button--minimal'],
      full_width && attr['button--full'],
      with_icon && attr['button--inline']
    ]"
    v-else-if="status == 1"
  >
    <span>{{ label }}</span>
  </a>

  <button
    :type="button_type"
    :class="[
      attr['button'],
      attr[template],
      attr['button--pointer'],
      disabled && attr['button--disabled'],
      minimal && attr['button--minimal'],
      full_width && attr['button--full'],
      with_icon && attr['button--inline']
    ]"
    v-else-if="status == 2"
  >
    <span>{{ label }}</span>
    <template v-if="with_icon">
      <template v-if="icon_type == 'plus'">
        <svg xmlns="http://www.w3.org/2000/svg" width="14.778" height="14.778" viewBox="0 0 14.778 14.778">
          <path d="M18,7.5V22.278" transform="translate(-10.611 -7.5)" :class="attr['button__icon']"/>
          <path d="M7.5,18H22.278" transform="translate(-7.5 -10.611)" :class="attr['button__icon']"/>
        </svg>
      </template>
      <template v-else-if="icon_type == 'search'">
        <svg xmlns="http://www.w3.org/2000/svg" width="19.018" height="19.018" viewBox="0 0 19.018 19.018">
          <g transform="translate(1.25 1.25)">
            <path d="M18.722,11.611A7.111,7.111,0,1,1,11.611,4.5a7.111,7.111,0,0,1,7.111,7.111Z" transform="translate(-4.5 -4.5)" :class="attr['button__search-icon']"/>
            <path d="M28.842,28.842l-3.867-3.867" transform="translate(-12.842 -12.842)" :class="attr['button__search-icon']"/>
          </g>
        </svg>
      </template>
    </template>
  </button>
</template>

<script>
  export default {
    props: {
      button_type: {
        type: String,
        default: 'button'
      },
      label: {
        type: String,
        default: ''
      },
      link: {
        type: String,
        default: '#'
      },
      status: {
        type: Number,
        default: 0
      },
      disabled: {
        type: Boolean,
        default: false
      },
      minimal: {
        type: Boolean,
        default: false
      },
      full_width: {
        type: Boolean,
        default: false
      },
      with_icon: {
        type: Boolean,
        default: false
      },
      icon_type: {
        type: String,
        default: ''
      },
      template: {
        type: String,
        default: 'button--primary'
      }
    }
  }
</script>

<style lang="stylus" module="attr">
  .button
    position: relative
    display: inline-block
    padding: 16px 40px
    font-weight: var(--med)
    font-family: var(--merri_sans)
    text-align: center
    border-radius: 50px
    transition: .4s ease-in-out
    span
      position: inherit
      z-index: 0
      font-size: 16px
      color: var(--theme_white)
      letter-spacing: 0.24px
      vertical-align: top
      transition: .4s ease-in-out
    svg
      position: inherit
    &__icon
      fill: transparent
      stroke: var(--theme_white)
      stroke-linejoin: round
      stroke-width: 2
      transition: .4s ease-in-out
    &__search-icon
      fill: transparent
      stroke: var(--theme_white)
      stroke-linejoin: round
      stroke-width: 2.5
      transition: .4s ease-in-out
    &--pointer
      cursor: pointer
    &--minimal
      padding: 10px 30px
      span
        font-size: 16px
    &--disabled
      pointer-events: none
      &^[0]--primary,
      &^[0]--primary-long
        border: 1px solid var(--theme_gray)
        background-color: var(--theme_gray)
        span
          color: var(--theme_white)
    &--full
      display: block
      width: 100%
    &--inline
      display: flex
      flex-flow: row wrap
      align-items: center
      justify-content: center
      span
        margin-right: 10px
    &::before
      content: ''
      position: absolute
      top: 0
      left: 0
      bottom: 0
      width: 0
      background-color: transparent
      border-radius: 50px
      opacity: 0
      visibility: hidden
      transition: .4s ease-in-out
    &:hover
      & ^[0]__icon,
      & ^[0]__search-icon
        stroke: var(--theme_primary)
      &::before
        width: 100%
        opacity: 1
        visibility: visible
    &--primary,
    &--primary-long
      border: 1px solid var(--theme_primary)
      background-color: var(--theme_primary)
      &:hover
        &::before
          background-color: var(--theme_white)
        span
          color: var(--theme_primary)
    &--primary-long
      padding: 16px 60px
    &--tertiary
      border: 1px solid var(--theme_tertiary)
      background-color: var(--theme_tertiary)
      &:hover
        &::before
          background-color: var(--theme_white)
        & ^[0]__icon,
        & ^[0]__search-icon
          stroke: var(--theme_tertiary)
        span
          color: var(--theme_tertiary)
    &--outline-black
      border: 1px solid var(--theme_black)
      span
        color: var(--theme_black)
      &:hover
        border: 1px solid var(--theme_primary)
        &::before
          background-color: var(--theme_primary)
        span
          color: var(--theme_white)
    &--outline-white
      border: 1px solid var(--theme_white)
      span
        color: var(--theme_white)
      &:hover
        border: 1px solid var(--theme_black)
        &::before
          background-color: var(--theme_white)
        span
          color: var(--theme_black)
  /**
   * Responsive */
  @media (max-width: 1024px) and (min-width: 280px)
    .button
      padding: 12px 30px
      span
        font-size: 14px
    @media (max-width: 310px) and (min-width: 280px)
      .button
        display: block
        width: 100%
        span
          font-size: 14px
</style>
