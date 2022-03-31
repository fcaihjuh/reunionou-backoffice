<template>
  <section class="hero is-fullheight">
    <Header />
    <div class="hero-body">
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-6-tablet is-5-desktop is-10-widescreen">
            <div class="box">
              <h4 class="title is-5 has-text-centered mb-20 title-chat">
                Rencontre entre anciens camarades
              </h4>
              <p>Fête entre anciens camarades.</p>
              <p><b>Date</b> : 05/09/2022</p>
              <p>
                <b>Lieu</b> : 2 B Boulevard Charlemagne, 54100 Nancy, France
              </p>
              <footer class="card-footer">
                <button
                  @click="participateEvent(true)"
                  class="button is-success is-light btn-footer"
                >
                  Je participe
                </button>
                <button
                  @click="participateEvent(false)"
                  class="button is-danger is-light btn-footer"
                >
                  Je participe pas
                </button>
              </footer>
            </div>
            <h4 class="title is-5 has-text-centered mb-20">Localisation :</h4>
            <div class="box" id="map">
              <template>
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
                  <l-marker
                    :key="index"
                    v-for="(m, index) in markers"
                    :lat-lng="m.pos"
                  >
                    <l-tooltip
                      :options="{ permanent: true, interactive: true }"
                      >{{ place }}</l-tooltip
                    >
                  </l-marker>
                </l-map>
              </template>
            </div>
            <div class="box mt-6">
              <h4 class="title is-6 title-chat">
                Réponses:
                <div class="box mt-6">
                  <br />
                  {{ participe }} Je participe
                  <br />
                  {{ participePas }} Je ne participe pas
                </div>
              </h4>
            </div>
            <div class="box mt-6">
              <h4 class="title is-6 title-chat">
                Conversation de l'évènement
                <input
                  class="input box mt-6"
                  type="text"
                  placeholder="Votre message"
                />
                <div class="box mt-6">
                  <article class="media">
                    <div class="media-left">
                      <figure class="image is-32x32 mr-2">
                        <img
                          src="https://bulma.io/images/placeholders/128x128.png"
                          alt="Image"
                        />
                      </figure>
                    </div>
                    <div class="media-content">
                      <div class="content">
                        <p>COOLLLLLL</p>
                      </div>
                    </div>
                  </article>
                </div>
                <div class="box mt-6">
                  <article class="media">
                    <div class="media-left">
                      <figure class="image is-32x32 mr-2">
                        <img
                          src="https://bulma.io/images/placeholders/128x128.png"
                          alt="Image"
                        />
                      </figure>
                    </div>
                    <div class="media-content">
                      <div class="content">
                        <p>Extra</p>
                      </div>
                    </div>
                  </article>
                </div>
                <div class="box mt-6">
                  <article class="media">
                    <div class="media-left">
                      <figure class="image is-32x32 mr-2">
                        <img
                          src="https://bulma.io/images/placeholders/128x128.png"
                          alt="Image"
                        />
                      </figure>
                    </div>
                    <div class="media-content">
                      <div class="content">
                        <p>PAS MAL !</p>
                      </div>
                    </div>
                  </article>
                </div>
              </h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from "axios";
import Vue2LeafletMarkerCluster from "vue2-leaflet-markercluster";
import { latLng } from "leaflet";
import { LMap, LTileLayer, LMarker, LPopup, LTooltip } from "vue2-leaflet";
import { Icon } from "leaflet";
export default {
  components: {
    LMap,
    LTileLayer,
    LMarker,
    LPopup,
    "v-marker-cluster": Vue2LeafletMarkerCluster,
  },
  data() {
    return {
      participe: [],
      participePas: [],
      error: false,
      success: false,
      title: "",
      description: "",
      date: "",
      time: "",
      place: "",
      initialLocation: [48.6829037, 6.1613428],
      markers: [],
      x: false,
      y: false,
      zoom: 15,
      url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
      attribution:
        '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      withPopup: latLng(48.6829037, 6.1613428),
      showParagraph: false,
      withTooltip: latLng(48.6829037, 6.1613428),
      currentZoom: 15,
      currentCenter: latLng(location.x, location.y),
      mapOptions: {
        zoomSnap: 0.5,
      },
      showMap: true,
    };
  },
  mounted() {
  },
  methods: {
    getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(this.updateLocation);
      }
    },
    updateLocation(position) {
      let currentLocation = [
        position.coords.latitude,
        position.coords.longitude,
      ];
      this.initialLocation = currentLocation;
    },

    zoomUpdate(zoom) {
      this.currentZoom = zoom;
    },
    centerUpdate(center) {
      this.currentCenter = center;
    },
    handleMapClick(event) {
      const pos = L.latLng(event.latlng.lat, event.latlng.lng);
      this.markers = [];
      this.markers.push({ pos: pos });
      this.x = event.latlng.lat;
      this.y = event.latlng.lng;
    },
  
  participateEvent(response) {
    if (response == true) {
      this.$refs.participate;
    }
    if (response == false) {
      this.$refs.notparticipate;
    }
    // Appel de l'API pour donné sa réponse à l'invitation d'un évènement, si response est à: true = participe, null = en attente, false = ne participe pas
  },
  },
};
</script>

<style lang="scss">
@import "../scss/bulma.scss";
</style>