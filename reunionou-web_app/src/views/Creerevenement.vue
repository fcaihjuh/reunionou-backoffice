<template>
  <div class="container">
    <Header />
    <br>
    <div class="columns is-centered">
      <div class="column is-half">
        <article class="media notification">
          <figure class="media-left">
            <span class="icon">
              <i class="fa fa-calendar" aria-hidden="true"></i
            ></span>
          </figure>
          <div class="media-content">
            <div class="content">
              <h2 class="title">Créer un événement</h2>
              <form class="box" @submit.prevent="validation">
                <!-- form pour interagir avec le formulaire -->
                              <div class="field">
                <label class="label">Titre</label>
                <div class="control">
                  <input class="input" v-model="evenement.topic" />
                </div>
              </div>
              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <input class="input" v-model="evenement.label" />
                </div>
              </div>
              <div class="field">
                <label class="label">Lieu</label>
                <div class="control">
                  <input class="input" v-model="evenement.place" />
                </div>
              </div>
               <div class="field">
                <label class="label">Date</label>
                <div class="control">
                  <input class="input" v-model="evenement.date" />
                </div>
              </div>
               <div class="field">
                <label class="label">Heure</label>
                <div class="control">
                  <input class="input" v-model="evenement.time" />
                </div>
              </div>
              <div class="buttons">
                <button class="button is-info is-outlined">Créer</button>
                <router-link class="button is-outlined" to="/"
                  >Annuler</router-link
                >
              </div>
              </form>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
  components: {  },
      data() {
        return {
          evenement: {
            topic: "",
            label: "",
            place:"",
            date:"",
            time:"",
          },
        };
      },
      methods: {
        validation() {
          this.$api
            .post("createevent", this.evenement)
            .then((response) => {
              this.$router.push({name:'Evenement', params:{id:response.data.id}})
            })
            .catch((error) => {
              alert(error.response.data.message);
            });
        },
      },
    };
</script>

</script>

<style scoped lang="scss">
.button {
  background-color: rgb(238, 234, 234)
}
</style>
