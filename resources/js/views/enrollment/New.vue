<template>
  <new-enrollment :is-edit="false" />
</template>

<script>
import NewEnrollment from './components/NewEnrollment.vue';

export default {
  name: 'AddNewEnrollment',
  components: { NewEnrollment },
};
</script>

