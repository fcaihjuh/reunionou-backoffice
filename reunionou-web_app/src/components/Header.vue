<template>
<section class="hero header">

  <!-- rendre le header responsive -->
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <router-link class="navbar-item reu" to="/"><a href="/"><img src="../img/Reunionou.png"/></a></router-link>
    </div>
    <div id="navbarBasicExample" class="navbar-menu">
      <div class="navbar-start">
        <router-link class="navbar-item" to="/events">Mes évènements</router-link>
        <router-link class="navbar-item" to="/createEvent">Nouvel évènement</router-link>
         <router-link class="navbar-item" to="/invitation">Invitation</router-link>
        <router-link class="navbar-item" to="/profil">Profil</router-link>
      </div>
      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <router-link class="navbar-item button is-warning" to="/signout">Déconnexion</router-link>
          </div>
        </div>
      </div>
    </div>
  </nav>
</section>
</template>

<script>
export default {
}
</script>

<style lang="scss">
 @import "../scss/bulma.scss";
</style>
