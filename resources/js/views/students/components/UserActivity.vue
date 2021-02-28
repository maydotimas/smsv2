<template>
  <view-student v-if="user" :user="user" />
</template>

<script>
// import Resource from '@/api/resource';
// const userResource = new Resource('users');
import ViewStudent from './ViewStudent';

export default {
  components: { ViewStudent },
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          student_no: '',
          lrn: '',
          first_name: '',
          middle_name: '',
          last_name: '',
          suffix: '',
          nickname: '',
          handedness: '',
          religion: '',
          brgy: '',
          town: '',
          province: '',
          email: '',
          mobile: '',
          avatar: '',
          gender: '',
          emergency: '',
          birthdate: '',
          student_type: 'Old Student',
          family: [],
          father_id: '',
          father_first_name: '',
          father_middle_name: '',
          father_last_name: '',
          father_mobile: '',
          father_email: '',
          father_occupation: '',
          father_address: '',
          mother_id: '',
          mother_first_name: '',
          mother_middle_name: '',
          mother_last_name: '',
          mother_mobile: '',
          mother_email: '',
          mother_occupation: '',
          mother_address: '',
          guardian_id: '',
          guardian_first_name: '',
          guardian_middle_name: '',
          guardian_last_name: '',
          guardian_mobile: '',
          guardian_email: '',
          guardian_occupation: '',
          guardian_address: '',
          relationship: '',
        };
      },
    },
  },
  data() {
    return {
      activeActivity: 'first',
      carouselImages: [
        'https://cdn.laravue.dev/photo1.png',
        'https://cdn.laravue.dev/photo2.png',
        'https://cdn.laravue.dev/photo3.jpg',
        'https://cdn.laravue.dev/photo4.jpg',
      ],
      updating: false,
    };
  },
};
</script>

<style lang="scss" scoped>
.user-activity {
  .user-block {
    .username, .description {
      display: block;
      margin-left: 50px;
      padding: 2px 0;
    }
    img {
      width: 40px;
      height: 40px;
      float: left;
    }
    :after {
      clear: both;
    }
    .img-circle {
      border-radius: 50%;
      border: 2px solid #d2d6de;
      padding: 2px;
    }
    span {
      font-weight: 500;
      font-size: 12px;
    }
  }
  .post {
    font-size: 14px;
    border-bottom: 1px solid #d2d6de;
    margin-bottom: 15px;
    padding-bottom: 15px;
    color: #666;
    .image {
      width: 100%;
    }
    .user-images {
      padding-top: 20px;
    }
  }
  .list-inline {
    padding-left: 0;
    margin-left: -5px;
    list-style: none;
    li {
      display: inline-block;
      padding-right: 5px;
      padding-left: 5px;
      font-size: 13px;
    }
    .link-black {
      &:hover, &:focus {
        color: #999;
      }
    }
  }
  .el-carousel__item h3 {
    color: #475669;
    font-size: 14px;
    opacity: 0.75;
    line-height: 200px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }
}
</style>
