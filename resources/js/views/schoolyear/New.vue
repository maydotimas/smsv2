<template>
  <new-school-year :is-edit="false" />
</template>

<script>
import NewSchoolYear from './components/NewSchoolYear.vue';

export default {
  name: 'CreateNewSchoolYear',
  components: { NewSchoolYear },
};
</script>
