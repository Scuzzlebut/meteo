<template>
  <div class="site-content">
    <div class="container" >
      <div class="dropdown">
        <button class="dropdown-toggle" @click="toggleDropdown">
          {{ selectedOption.name || 'Seleziona una località' }}
        </button>
        <ul v-if="showDropdown" class="dropdown-menu">
          <li v-for="(option, index) in options" :key="index" @click="selectOption(option)">
            {{ option.name }}
          </li>
        </ul>
      </div>
    </div>
    <div class="container">
      <Grid v-if="selectedOption"
            :data="gridData"
            :columns="gridColumns">
      </Grid>
    </div>
  </div>
</template>
<script>
import Grid from './components/Grid.vue';
import axios from 'axios';
import moment from 'moment';

export default {
  components: { Grid },
  data() {
    return {
      gridColumns: ['orario','umidita','temperatura','vento'],
      gridData: [],
      options: [
        {
          name:'Milano',
          lat:'45.59',
          long:'9.57',
        },
        {
          name:'Livorno',
          lat:'43.54',
          long:'10.33',
        },
        {
          name:'Padova',
          lat:'45.27',
          long:'11.77',
        },
        {
          name:'Brindisi',
          lat:'40.63',
          long:'17.94',
        },
        {
          name:'Firenze',
          lat:'43.80',
          long:'11.23',
        },
      ],
      selectedOption: '',
      filteredOptions: [],
      showOptions: false,
      showDropdown: false,
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false
      }
    }
  },
  methods: {
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    },
    selectOption(option) {
      this.showDropdown = false;
      this.fetchData(option);
    },
    fetchData(option) {
      this.gridData = []
      axios.get('https://api.open-meteo.com/v1/forecast?latitude='+option.lat+'&longitude='+option.long+'&current_weather=true&hourly=temperature_2m,relativehumidity_2m,windspeed_10m')
          .then(response => {
            var data = response.data.hourly
            for (let i = 0; i < data.time.length; i++) {
              this.gridData.push({
                orario: moment(data.time[i]).format('DD/MM/YYYY hh:mm'),
                umidita: data.relativehumidity_2m[i]+response.data.hourly_units.relativehumidity_2m,
                temperatura: data.temperature_2m[i]+response.data.hourly_units.temperature_2m,
                vento: data.windspeed_10m[i]+response.data.hourly_units.windspeed_10m,
              })
            }
            this.selectedOption = option;
          })
          .catch(error => {
            console.error(error);
          });
    }
  }
}
</script>