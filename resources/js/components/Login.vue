<template>
  <div>
    Login: <br/>
    Username: <input v-model="username" placeholder="username"> <br/>
    Password: <input v-model="password" type=password> <br/>
    <button @click="login">Login</button>
  </div>
</template>
<script>
import Authentication from '@/js/services/Authentication.service.js'
export default {
  data() {
    return {
      username: "",
      password: ""
    }
  },
  methods: {
    async login() {
      try {
        let response = await Authentication.login(this.username, this.password);
      } catch(error){
        console.log("Error:", error)
      }
    }
  }
}
</script>
<style scoped>

</style>