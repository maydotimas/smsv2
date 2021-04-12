<template>
  <el-card>
    <div class="user-profile">
      <div class="box-center">
        <div class="user-name text-center">
          {{ sy.name }}
        </div>
        <div class="user-role text-center text-muted">
          {{ sy.year }} - {{ sy.year + 1 }}
        </div>
      </div>
      <div class="user-follow">
        <router-link :to="'/schoolyear/edit/'+sy.id">
          <el-button type="primary" style="width: 100%;">
            Edit School Year
          </el-button>
        </router-link>
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
          avatar: 'uploads\\default.png',
          student_no: '',
          roles: [],
        };
      },
    },
  },
  methods: {

  },
};
</script>

<style lang="scss" scoped>
.user-profile {
  .user-name {
    font-weight: bold;
  }
  .box-center {
    padding-top: 10px;
  }
  .user-role {
    padding-top: 10px;
    font-weight: 400;
    font-size: 14px;
  }
  .box-social {
    padding-top: 30px;
    .el-table {
      border-top: 1px solid #dfe6ec;
    }
  }
  .user-follow {
    padding-top: 20px;
  }
}
</style>
