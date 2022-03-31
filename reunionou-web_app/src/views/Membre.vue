<template>
  <div>
      <Header />
      <section class="section">
        <template v-if="member">
          <div class="card" v-if="member">
              <div class="card-content">
                <div class="media">
                  <div class="media-left">
                    <figure class="image is-48x48">
                      <img :src="avatar(member)">
                    </figure>
                  </div>
                  <div class="media-content">
                      <p class="title is-4">{{member.fullname}}</p>
                      <p class="subtitle is-6">{{member.email}}</p>
                    <p class="subtitle is-6">Membre depuis le {{created_at}}</p> 
                  </div>
                  
                </div>
              </div> 
          </div>
        </template>
      </section>
  </div>
</template>

<script>
export default {
  data() {
    return{
      member: false,
    };
  },
    mounted(){
        this.member = this.$store.getters.getMembre(this.$route.params.idMembre);
        //console.log(this.$route.params.idMembre)
    },
    computed:{
     /*  avatar(){
        return `https://eu.ui-avatars.com/api/?name=${this.member.fullname}`;
      }, */
      created_at(){
        if(this.member){
          let d = new Date(this.member.created_at);
          return d.toLocaleDateString("fr-FR") + ' à ' + d.toLocaleTimeString("fr-FR");
        }
      }
    }
}
</script>

<style></style>