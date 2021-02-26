<template>
  <new-student :is-edit="false" />
</template>

<script>
import NewStudent from './components/NewStudent';

export default {
  name: 'AddNewStudent',
  components: { NewStudent },
};
</script>

