<template>
  <section class="hero is-fullheight">
    <Header />
    <div class="hero-body">
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-12-tablet is-11-desktop is-10-widescreen">
            <h4 class="title is-4 has-text-centered">Modifier son profil</h4>
                        <div class="card-body">
                            <form @submit.prevent="editProfile()">
                                <div class="field">
                                    <label for="fullname" class="label">Prénom Nom</label>
                                    <div class="col-md-6">
                                        <input v-model="fullname" type="text" id="fullname" class="form-control" required>
                                    </div>
                                </div>

                                <div class="field">
                                    <label for="email" class="label">Adresse mail</label>
                                    <div class="col-md-6">
                                        <input v-model="email" type="email" id="email" class="form-control" required>
                                    </div>
                                </div>

                                <div class="field">
                                    <label for="password" class="label">Mot de passe</label>
                                    <div class="col-md-6">
                                        <input v-model="password" type="password" id="password" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <button class="btn btn-primary">Modifier</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  </section>
</template>

<style lang="scss">
 @import "../scss/bulma.scss";
</style>


<script>
export default {
  components: {
  },
  data() {
      return {
          error: false,
          success: false,
          fullname: this.$store.state.fullname,
          email: this.$store.state.email,
          password: ''
      }
  },
  methods: {
      editProfile() {
          api.post('edit/profile', {
             fullname: this.fullname,
             email: this.email,
             password: this.password 
          }).then(response => {
              if(response.data.post) {
                this.$store.state.fullname = this.fullname;
                this.$store.state.email = this.email;
                this.success = 'Votre profil a bien été modifié';
              } else {
                  this.error = response.data.message;
              }
          });
      }
  }
}
</script>