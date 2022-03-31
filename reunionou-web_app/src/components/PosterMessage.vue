<template>
    <form class="box" @submit.prevent="poster">
        <div class="field">
          <div class="control">
            <input type="text" class="input" v-model="message">
          </div>
        </div>
        <div class="buttons"><button class="button">Poster un message</button></div>
    </form>


</template>

<script>
export default {
    props:['evenement'],
    data() {
        return{
            message:'',
        }
    },
    methods: {
        poster(){
            this.$api.post(`channels/${this.evenement.id}/posts`,{
                channel_id: this.evenement.id,
                message: this.message,
                member_id: this.$store.state.member.id,
            }).then(response=>{
                this.$bus.$emit('charger-messages',response.data)
                this.message = "";
                //console.log(response.data)
            });
            
        },
    },
}
</script>

<style>
</style>