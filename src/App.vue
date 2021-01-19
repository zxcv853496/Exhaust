<template>
  <div id="app">
    <NavBar />
    <router-view/>
    <Footer />
  </div>
</template>

<script>
// @ is an alias to /src
import './assets/css/app.scss'
import NavBar from './components/NavBar/index'
import Footer from './components/Footer/index'

export default {
  name: 'app',
  components: {
    NavBar,
    Footer,
  }
}
</script>