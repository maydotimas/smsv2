<template>
  <new-student v-if="user.first_name" :is-edit="true" :user="user" />
</template>

<script>
import Resource from '@/api/resource';
import NewStudent from './components/NewStudent';

const studentResource = new Resource('students');

export default {
  name: 'EditStudent',
  components: { NewStudent },
  data(){
    return {
      user: {},
    };
  },
  created() {
    const id = this.$route.params && this.$route.params.id;
    this.getUser(id);
  },
  methods: {
    async getUser(student_id) {
      const data = await studentResource.list({ id: student_id });
      this.user = data.data[0];
    },
  },
};
</script>

