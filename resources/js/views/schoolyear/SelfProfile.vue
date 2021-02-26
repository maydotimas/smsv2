<template>
  <div class="app-container">
    <el-form v-if="user" :model="user">
      <el-row :gutter="20">
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
      user: {
        avatar: 'uploads\\default.png',
      },
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
      console.log(this.user);
    },
  },
};
</script>
