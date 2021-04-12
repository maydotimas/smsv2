<template>
  <div v-if="user" class="createPost-container">
    <el-form
      ref="postForm"
      :model="user"
      class="form-container"
      readonly
    >
      <div v-if="user.last_name" class="createPost-main-container">
        <el-row>
          <el-col style="margin-bottom:2%;">
            <el-row>
              <el-col>
                <el-row>
                  <el-card class="box-card student-bio">
                    <div slot="header" class="clearfix">
                      <span>Reservation Information</span>
                    </div>
                    <div class="postInfo-container">
                      <el-row>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="School Year:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.school_year.name"
                            class="postInfo-container-item"
                          />
                        </el-col>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="Status:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.status"
                            class="postInfo-container-item"
                          />
                        </el-col>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="Department:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.department.name"
                            class="postInfo-container-item"
                          />
                        </el-col>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="Grade:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.grade.name"
                            class="postInfo-container-item"
                          />
                        </el-col>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="Section:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.section.name"
                            class="postInfo-container-item"
                          />
                        </el-col>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="Remarks:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.remarks"
                            class="postInfo-container-item"
                          />
                        </el-col>
                      </el-row>
                    </div>
                  </el-card>
                  <br>
                </el-row>
              </el-col>
              <el-card class="box-card student-bio" style="">
                <div
                  slot="header"
                  class="clearfix"
                >
                  <span>Student Information</span>
                </div>
                <el-form-item
                  v-if="user.student_type === 'Old Student'"
                  style="margin-bottom: 40px"
                  prop="student_no"
                >
                  <MDinput
                    v-model="user.student_no"
                    :maxlength="11"
                    name="student_no"
                    required
                    readonly
                  >
                    Student Number
                  </MDinput>
                </el-form-item>
                <div class="postInfo-container">
                  <el-row>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Student Name:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="getFullName(user)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Nick Name:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.nickname)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Student Type:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.student_type)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="LRN:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.lrn)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Birthdate:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.birthdate)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Gender:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.gender)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Handedness:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.handedness)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Mobile Number:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.mobile)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Email:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.email)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Religion:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.religion)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Address:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.address)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                  </el-row>
                </div>
              </el-card>
            </el-row>
          </el-col>
          <br>
        </el-row>
      </div>
      <div v-if="reservation.reservation_student.last_name" class="createPost-main-container">
        <el-row>
          <el-col style="margin-bottom:2%;">
            <el-row>
              <el-col>
                <el-row>
                  <el-card class="box-card student-bio">
                    <div slot="header" class="clearfix">
                      <span>Reservation Information</span>
                    </div>
                    <div class="postInfo-container">
                      <el-row>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="School Year:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.school_year.name"
                            class="postInfo-container-item"
                          />
                        </el-col>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="Status:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.status"
                            class="postInfo-container-item"
                          />
                        </el-col>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="Department:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.department.name"
                            class="postInfo-container-item"
                          />
                        </el-col>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="Grade:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.grade.name"
                            class="postInfo-container-item"
                          />
                        </el-col>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="Section:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.section.name"
                            class="postInfo-container-item"
                          />
                        </el-col>
                        <el-col :span="12">
                          <el-form-item
                            label-width="120px"
                            label="Remarks:"
                            class="postInfo-container-item"
                          />
                          <el-form-item
                            :label="reservation.remarks"
                            class="postInfo-container-item"
                          />
                        </el-col>
                      </el-row>
                    </div>
                  </el-card>
                  <br>
                </el-row>
              </el-col>
              <el-card class="box-card student-bio" style="">
                <div
                  slot="header"
                  class="clearfix"
                >
                  <span>Student Information</span>
                </div>
                <div class="postInfo-container">
                  <el-row>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Student Name:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="getFullName(reservation.reservation_student)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Nick Name:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(reservation.first_name)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Student Type:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(reservation.type)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Birthdate:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(reservation.reservation_student.birthdate)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                    <el-col :span="12">
                      <el-form-item
                        label-width="120px"
                        label="Mobile Number:"
                        class="postInfo-container-item"
                      />
                      <el-form-item
                        :label="checkIfNull(user.mobile)"
                        class="postInfo-container-item"
                      />
                    </el-col>
                  </el-row>
                </div>
              </el-card>
            </el-row>
          </el-col>
          <br>
        </el-row>
      </div>
    </el-form>
  </div>
</template>

<script>
// import Sticky from '@/components/Sticky'; // Sticky header
import MDinput from '@/components/MDinput';
import permission from '@/directive/permission';
import waves from '@/directive/waves';

export default {
  name: 'ViewStudent',
  components: {
    MDinput,
  },
  directives: { permission, waves },
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
    reservation: {
      type: Object,
      default: () => {
        return {
          id: '',
          student_id: '',
          student_no: '',
          first_name: '',
          middle_name: '',
          last_name: '',
          birthdate: '',
          department: '',
          grade: '',
          section: '',
          fee: '',
          school_year_id: '',
          student_type: '',
          school_year: {},
          student: {},
        };
      },
    },
  },
  data() {
    return {
      student: {
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
      },
    };
  },
  created(){
    console.log(this.reservation);
    console.log(this.user);
  },
  methods: {
    checkIfNull(data){
      if (data === null || data === undefined){
        return 'N/A';
      }
      return data.toUpperCase();
    },
    getFullName(user){
      var name = '';
      name = user.last_name + ', ' + user.first_name;
      if (user.suffix) {
        name = name + ' ' + user.suffix;
      }
      if (user.middle_name) {
        name = name + ' ' + user.middle_name;
      }
      return name;
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import '~@/styles/mixin.scss';
.createPost-container {
  position: relative;
  .createPost-main-container {
    padding: 0 2% 3% 2%;
    .postInfo-container {
      position: relative;
      @include clearfix;
      margin-bottom: 10px;
      .postInfo-container-item {
        float: left;
      }
    }
  }
  .word-counter {
    width: 40px;
    position: absolute;
    right: -10px;
    top: 0px;
  }
}
</style>
<style>
.createPost-container label.el-form-item__label {
  text-align: left;
}
</style>
