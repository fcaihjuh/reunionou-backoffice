<template>
    <div>
        <Header />
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div v-if="error" class="alert alert-danger" role="alert">
                        <font-awesome-icon icon="exclamation-circle"></font-awesome-icon> {{ error }}
                    </div>
                    <div class="card">
                        <h4 class="title is-4 has-text-centered">Nouvel évènement</h4>
                        <div class="card-body">
                            <form @submit.prevent="postEvent()">
                               <div class="field">
                <label class="label">Titre</label>
                <div class="control">
                  <input class="input" v-model="title" />
                </div>
              </div>
              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <input class="input" v-model="description" />
                </div>
              </div>
              <div class="field">
                <label class="label">Lieu</label>
                <div class="control">
                  <input class="input" v-model="location" />
                </div>
              </div>
              <div class="field">
                <label class="label">Date</label>
                <div class="control">
                  <input class="input" v-model="date" />
                </div>
              </div>
              <div class="field">
                <label class="label">Heure</label>
                <div class="control">
                  <input class="input" v-model="time" />
                </div>
              </div>

                                <l-map v-if="showMap" :zoom="zoom" :center="initialLocation" :options="mapOptions"
                                    style="height: 350px; width: 100%;" @update:center="centerUpdate"
                                    @update:zoom="zoomUpdate" @click="handleMapClick">
                                    <l-tile-layer :url="url" :attribution="attribution" />
                                    <l-marker :key="index" v-for="(m, index) in markers" :lat-lng="m.pos">
                                        <l-tooltip :options="{ permanent: true, interactive: true }">{{ location }}</l-tooltip>
                                    </l-marker>
                                </l-map>
                                 <div @click="postEvent()" class="buttons">
                <button class="button is-info is-outlined">Créer</button>
                <router-link class="button is-outlined" to="/"
                  >Annuler</router-link
                >
              </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
 @import "../scss/bulma.scss";
</style>

<script>
import { latLng } from "leaflet";
import { LMap, LTileLayer, LMarker, LPopup, LTooltip } from "vue2-leaflet";
import { Icon } from 'leaflet';
import 'leaflet/dist/leaflet.css';

delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});


export default {
  components: {
    LMap,
    LTileLayer,
    LMarker,
    LPopup,
    LTooltip
  },
  data() {
      return {
        error: false,
        success: false,
        title: '',
        description: '',
        date: '',
        time: '',
        location: '',
        initialLocation: [46.227638, 2.213749],
        markers: [],
        x: false,
        y: false,

        zoom: 15,
        center: latLng(location.x, location.y),
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        withPopup: latLng(47.41322, -1.219482),
        showParagraph: false,
        withTooltip: latLng(47.41422, -1.250482),
        currentZoom: 15,
        currentCenter: latLng(location.x, location.y),
        mapOptions: {
            zoomSnap: 0.5
        },
        showMap: true,
      }
  },
  methods: {
    postEvent() {
        this.$api.post('event', {
             title: this.title,
             description: this.description,
             date: this.date + ' ' + this.time,
             location: this.location,
             x: this.x,
             y: this.y
          }).then(response => {
              if(response.data.post) {
                alert("L'évènement a été créé !");
                this.$router.push('/events');
              } else {
                  this.error = response.data.message;
              }
          });
    },

    getLocation() {
        if (navigator.geolocation){
            navigator.geolocation.getCurrentPosition(this.updateLocation);
        }
    },

    updateLocation(position) {
        let currentLocation = [position.coords.latitude, position.coords.longitude];
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
    }
  },

  mounted() {
      this.getLocation();
  }
}
</script>