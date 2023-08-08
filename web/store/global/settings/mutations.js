export default {
  enteredMobile (state, payload) {
    state.mobile = payload.status
  },
  setLazy (state, payload) {
    state.lazy = payload.status
  },
  userAuthentication (state, payload) {
    state.authenticated = payload.status
  }
}
