<template>
  <div id="app">
    <NavBar />
    <router-view/>
  </div>
</template>

<script>
// @ is an alias to /src
import './assets/css/app.scss'
import NavBar from './components/NavBar/index'

export default {
  name: 'app',
  components: {
    NavBar,
  }
}
</script>