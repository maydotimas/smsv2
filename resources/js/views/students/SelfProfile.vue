<template>
  <div class="app-container">
    <el-form v-if="is_loaded" :model="user">
      <el-row>
        <el-col :span="6">
          <user-card :user="user" />
          <user-bio :user="user" />
        </el-col>
        <el-col :span="18">
          <user-activity :user="user" />
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>

<script>
import UserBio from './components/UserBio';
import UserCard from './components/UserCard';
import UserActivity from './components/UserActivity';
import Resource from '@/api/resource';
const studentResource = new Resource('students');

export default {
  name: 'StudentProfile',
  components: { UserBio, UserCard, UserActivity },
  data() {
    return {
      user: false,
      is_loaded: false,
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
      this.is_loaded = true;
      console.log('aaaa');
      console.log(this.user);
    },
  },
};
</script>
