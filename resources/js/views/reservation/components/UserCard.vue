<template>
  <el-card>
    <div v-if="user.first_name" class="user-profile">
      <div class="user-avatar box-center">
        <pan-thumb :image="user.avatar" :height="'100px'" :width="'100px'" :hoverable="false" />
      </div>
      <div class="box-center">
        <div class="user-name text-center">
          {{ user.nickname }}
        </div>
        <div class="user-role text-center text-muted">
          {{ user.first_name }} {{ user.last_name }}
        </div>
      </div>
      <div class="user-follow">
        <router-link :to="'/student/edit/'+user.id">
          <el-button type="primary" style="width: 100%;">
            Edit Profile
          </el-button>
        </router-link>
      </div>
    </div>
    <div v-if="user.first_name === undefined" class="user-profile">
      <div class="user-avatar box-center">
        <pan-thumb :image="reservation.reservation_student.avatar" :height="'100px'" :width="'100px'" :hoverable="false" />
      </div>
      <div class="box-center">
        <div class="user-name text-center">
          {{ reservation.reservation_student.first_name }}
        </div>
        <div class="user-role text-center text-muted">
          {{ reservation.reservation_student.first_name }} {{ reservation.reservation_student.last_name }}
        </div>
      </div>
    </div>
  </el-card>
</template>

<script>
import PanThumb from '@/components/PanThumb';

export default {
  components: { PanThumb },
  props: {
    user: {
      type: Object,
      default: () => {
        return {
        };
      },
    },
    reservation: {
      type: Object,
      default: () => {
        return {
        };
      },
    },
  },
  created(){
    console.log(this.user);
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
