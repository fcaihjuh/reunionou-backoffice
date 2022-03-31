<template>
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="https://bulma.io"></a>
    </div>

    <div class="navbar-start">
      <router-link class="navbar-item has-background-warning" to="creerevenement"
        >Créer un événement</router-link>
      <router-link class="navbar-item has-background-warning" to="profil"
        >Gérer mon profil</router-link
      >
      <router-link class="navbar-item has-background-warning" to="/creer">Mes événements</router-link>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <router-link class="button has-background-grey-lighter" to="deconnexion"
            >Se déconnecter</router-link
          >
        </div>
      </div>
      <div class="navbar-item">
        <div>
          <!--<b>{{$store.state.member.fullname}}</b>-->
        </div>
      </div>
    </div>
    <figure class="image is-64x64">
      <img src="../assets/reunionou.png" />
    </figure>
  </nav>
</template>

<script>
export default {};
</script>

<style></style>
