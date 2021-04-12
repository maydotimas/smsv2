<template>
  <el-card v-if="user.last_name" class="box-card user-bio">
    <div slot="header" class="clearfix">
      <span>My Emergency Contact</span>
    </div>
    <div class="user-education user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="people" />
        <span>Name</span>
      </div>
      <div class="user-bio-section-body">
        <div class="text-muted">
          {{ getFullName(user.emergency) }}
        </div>
      </div>
    </div>
    <div class="user-education user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="role" />
        <span>Mobile Number</span>
      </div>
      <div class="user-bio-section-body">
        <div class="text-muted">
          {{ user.emergency.mobile }}
        </div>
      </div>
    </div>
    <div class="user-education user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="guide" />
        <span>Email Address</span>
      </div>
      <div class="user-bio-section-body">
        <div class="text-muted">
          {{ user.emergency.email }}
        </div>
      </div>
    </div>
    <div class="user-education user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="email" />
        <span>Address</span>
      </div>
      <div class="user-bio-section-body">
        <div class="text-muted">
          {{ user.emergency.address }}
        </div>
      </div>
    </div>
    <div class="user-education user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="star" />
        <span>Relationship to Student</span>
      </div>
      <div class="user-bio-section-body">
        <div class="text-muted">
          {{ user.emergency.type }}
        </div>
      </div>
    </div>
  </el-card>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          first_name: '',
          last_name: '',
          middle_name: '',
          birthdate: '',
          address: '',
          mobile: '',
          avatar: '',
          student_no: '',
          roles: [],
        };
      },
    },
  },
  methods: {
    getFullName(user){
      var name = '';
      name = user.last_name + ', ' + user.first_name;
      if (user.suffix) {
        name = name + ' ' + user.suffix;
      }
      if (user.middle_name){
        name = name + ' ' + user.middle_name;
      }
      return name;
    },
  },
};
</script>

<style lang="scss" scoped>
.user-bio {
  margin-top: 20px;
  color: #606266;
  span {
    padding-left: 4px;
  }
  .user-bio-section {
    font-size: 14px;
    padding: 15px 0;
    .user-bio-section-header {
      border-bottom: 1px solid #dfe6ec;
      padding-bottom: 10px;
      margin-bottom: 10px;
      font-weight: bold;
    }
  }
}
</style>
