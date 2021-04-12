<template>
  <el-card class="box-card user-bio">
    <div slot="header" class="clearfix">
      <span>School Year Details</span>
    </div>
    <div class="user-education user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="people" />
        <span>Description</span>
      </div>
      <div class="user-bio-section-body">
        <div class="text-muted">
          {{ sy.description }}
        </div>
      </div>
    </div>
    <div class="user-education user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="role" />
        <span>Duration</span>
      </div>
      <div class="user-bio-section-body">
        <div class="text-muted">
          {{ sy.start }} to {{ sy.end }}
        </div>
      </div>
    </div>
    <div class="user-education user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="guide" />
        <span>No. Of Enrollees</span>
      </div>
      <div class="user-bio-section-body">
        <div class="text-muted">
          ###
        </div>
      </div>
    </div>
    <div class="user-education user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="guide" />
        <span>Status</span>
      </div>
      <div class="user-bio-section-body">
        <div class="text-muted">
          {{ getStatus(sy.status) }}
        </div>
      </div>
    </div>
  </el-card>
</template>

<script>

export default {
  props: {
    sy: {
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
  data(){
    return {
      //
    };
  },
  created() {
    //
  },
  methods: {
    //
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
