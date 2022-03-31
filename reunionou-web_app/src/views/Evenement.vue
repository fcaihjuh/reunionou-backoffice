<template>
  <div>
    <Header />
    <section class="section">
      <div class="box" v-if="evenement">
        <p class="title is-4">{{evenement.topic}}
          <span class="tag">{{messages.length}} message(s)</span>
        </p>
      </div>
      <poster-message :evenement = "evenement" />
      <h5 class="title is-5 has-text-warning">Les messages</h5>
      <template v-for="message in messages">
          <message :message="message" :key="message.id" />
      </template>
    </section>
    </div>
</template>

<script>
import PosterMessage from "../components/PosterMessage.vue";
import Message from "../components/Message.vue";

export default {
  components:{
    PosterMessage,
    Message
  },
  data(){
    return{
      evenement: false,
      messages: [],
      members: this.$store.state.members
    };
  },
  mounted(){
    this.chargerConversation();
    //this.$bus.$on('charger-conversation',(data) =>{this.chargerConversation} )
    this.$bus.$on('charger-messages',(data) =>{this.chargerMessage();
    });
  },
    methods:{
    chargerEvenement(){
      this.$api.get(`event/${this.$route.params.id}`).then((response) => {
      this.evenement = response.data;
      this.chargerMessage();
      });
    },
    chargerMessage(){
      this.$api.get(`channels/${this.evenement.id}/posts`).then((response) => {
      this.messages = response.data;
     });
    }
  }
 
};
</script>