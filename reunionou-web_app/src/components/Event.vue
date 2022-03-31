<template>
      <transition name="fade">
            <article class="media box" v-if="event && afficher">
                  <div class="media-content">
                  <div class="content">
                        <p>
                        <router-link :to="{name : 'Event', params :{id: event.id}}">
                              <h5 class="title is-5">{{event.topic}}</h5>
                              <p class="subtitle is-6 tag is-rounded is-warning">{{event.label}}</p>
                        </router-link>
                        </p>
                  </div>
                  </div>
                  <div class="media-right">
                        <router-link div="box" :to="{name : 'EditEvent', params :{id: event.id}}" class="button is-success is-small" >Modifier</router-link>     <a div="box" @click="deleteevent" class="button is-danger is-small">Supprimer</a>
                  </div>
            </article>    
      </transition>                                   
</template>
<script>
export default {
props : ["event"],
data() {
      return {
            afficher : true,
            editer : false
      }
},
methods : {
      deleteevent(){
            if(confirm("êtes-vous sûre de vouloir supprimer cette évènement ?")){
       this.$api.delete(`events/${this.event.id}`)
       .then( () =>{
         this.afficher = false;
       })
       .catch((error) => {
         if(error.response.data.message){
           alert(error.response.data.message)
         }
       })
       }
      },
  }
}
</script>

<style lang="scss">
 @import "../scss/bulma.scss";
</style>


