<template>
  <div>
    <div>
      isLoggedIn? {{ isLoggedIn }} <br/>
      hasExpired? {{ tokenExpired }}
    </div>
    <router-link tag="li" to="/">Home</router-link>
    <router-link tag="li" to="/about">About</router-link>
    <router-link v-if="isLoggedIn" tag="li" to="/post/new">New Post</router-link>
    <router-view></router-view>
  </div>

</template>
<script>
import moment from 'moment';
export default {
  data() {
    return {
      isLoggedIn: localStorage.getItem('user-token') != null,
    }
  },
  computed: {
    // Check if the current logged in user's JWT has expired
    tokenExpired() {
      let userTokenExpires = localStorage.getItem('user-token-expires');
      return moment(userTokenExpires).isBefore(moment.utc());
    }
  }
}
</script>