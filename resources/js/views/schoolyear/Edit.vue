<template>
  <new-school-year v-if="schoolyear.name" :is-edit="true" :sy="schoolyear" />
</template>

<script>
import Resource from '@/api/resource';
import NewSchoolYear from './components/NewSchoolYear';

const schoolYearResource = new Resource('schoolyears');

export default {
  name: 'EditSchoolYear',
  components: { NewSchoolYear },
  data(){
    return {
      schoolyear: {},
    };
  },
  created() {
    const id = this.$route.params && this.$route.params.id;
    this.getSchoolYear(id);
  },
  methods: {
    async getSchoolYear(id) {
      const data = await schoolYearResource.list({ id: id });
      this.schoolyear = data.data[0];
    },
  },
};
</script>

