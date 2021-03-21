<template>
  <new-reservation :is-edit="false" />
</template>

<script>
import NewReservation from './components/NewReservation.vue';

export default {
  name: 'AddNewReservation',
  components: { NewReservation },
};
</script>

