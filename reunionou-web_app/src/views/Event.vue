<template>
  <div>
    <Header />
    <section class="section">
      <h4 class="title is-4 has-text-centered">Détail de l'évènement</h4>
      
      <l-map
        v-if="showMap"
        :zoom="zoom"
        :center="initialLocation"
        :options="mapOptions"
        style="height: 350px; width: 100%"
        @update:center="centerUpdate"
        @update:zoom="zoomUpdate"
        @click="handleMapClick"
      >
        <l-tile-layer :url="url" :attribution="attribution" />
        <l-marker :key="index" v-for="(m, index) in markers" :lat-lng="m.pos">
          <l-tooltip :options="{ permanent: true, interactive: true }">{{
            location
          }}</l-tooltip>
        </l-marker>
      </l-map>
      <div class="media box" v-if="event">
        <div class="media-content">
          <div class="content">
            <p class="title is-4">
              {{ event.topic }}
              <span class="tag"> {{ messages.length }} message(s)</span>
            </p>
            <p class="subtitle is-6 tag is-rounded is-warning">
              {{ event.label }}
            </p>
            <br />
          </div>
        </div>
        <div class="media-right">
          <router-link
            div="box"
            :to="{ name: 'EditEvent', params: { id: event.id } }"
            class="button is-success is-small"
            >Modifier</router-link
          >
          <button
            @click="deleteEvent"
            class="button button is-danger is-small"
          >
            Supprimer
          </button>
        </div>
      </div>
      <posterMessage :conversation="conversation" />
      <h4 class="title is-4">Conversations</h4>
      <template v-for="message in messages">
        <message :message="message" :key="message.id" />
      </template>
    </section>
  </div>
</template>
<script>
import posterMessage from "../components/posterMessage.vue";
import Message from "../components/Message.vue";
import { latLng } from "leaflet";
import { LMap, LTileLayer, LMarker, LPopup, LTooltip } from "vue2-leaflet";
import { Icon } from "leaflet";
import "leaflet/dist/leaflet.css";

export default {
  components: {
    posterMessage,
    Message,
    LMap,
    LTileLayer,
    LMarker,
    LPopup,
    LTooltip,
  },
  data() {
    return {
      conversation: false,
      messages: [],
      members: this.$store.state.members,
    };
  },
  mounted() {
    let id = this.$route.params.id;
    this.$api.get(`event/${id}`).then((response) => {
      this.conversation = response.data;
      this.chargerMessage();
    });
    this.$bus.$on("newMessage", (data) => {
      this.chargerMessage();
    });
  },
  methods: {
    chargerMessage() {
      this.$api
        .get(`event/${this.conversation.id}/posts`)
        .then((response) => {
          this.messages = response.data;
        });
    },
    deleteConversation() {
      if (confirm("êtes-vous sûre de vouloir supprimer l'évènement' ?")) {
        this.$api
          .delete(`channels/${this.conversation.id}`)
          .then(() => {
            this.$router.push("/");
          })
          .catch((error) => {
            if (error.response.data.message) {
              alert(error.response.data.message);
            }
          });
      }
    },
  },
};
</script>

<style lang="scss">
@import "../scss/bulma.scss";
</style>
