<template>
<form class="box" @submit.prevent="poster">
      <div class="field">
          <div class="control">
              <h6 class="title is-6">Message </h6>
              <input type="text" class="input" v-model="message" placeholder="tapez votre message..."/>
          </div>
      </div>
      <div class="buttons">
          <button class="button is-info is-outlined"> Poster </button>
      </div>
    </form>                                                    
</template>
<script>
export default {
props : ["event"],
 data(){
        return {
            message : '',
        }
    },
    methods : {
        poster(){
            this.$api.post(`events/${this.event.id}/posts`,{
                channel_id : this.event.id,
                message : this.message,
                member_id : this.$store.state.member.id}).then(response =>{
                   this.$bus.$emit('newMessage',response.data);
                    this.message = "";
                })
            }
        }
}
</script>

<style lang="scss">
 @import "../scss/bulma.scss";
</style>
