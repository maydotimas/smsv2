<template>
  <div class="createPost-container">
    <el-form
      ref="postForm"
      :model="reservation"
      :rules="rules"
      class="form-container"
    >
      <sticky v-if="isEdit===false && created === false" :class-name="'sub-navbar active'">
        <el-button
          v-loading="loading"
          style="margin-left: 10px"
          type="success"
          @click="confirmation"
        >
          Save New Reservation Record
        </el-button>
        <el-button v-loading="loading" type="warning" @click="confirmCancel">
          Cancel
        </el-button>
      </sticky>

      <sticky v-if="isEdit===true || created === true" :class-name="'sub-navbar active'">
        <el-button
          v-loading="loading"
          style="margin-left: 10px"
          type="success"
          @click="confirmation"
        >
          Update Reservation Record
        </el-button>
        <el-button v-loading="loading" type="warning" @click="confirmCancel">
          Cancel
        </el-button>
      </sticky>

      <div class="createPost-main-container">
        <el-row>
          <el-card class="box-card" style="margin-top: 2%">
            <el-col>
              <el-form-item label="Student Type">
                <el-radio-group v-model="reservation.student_type" :disabled="isEdit === true" @change="confirmChange">
                  <el-radio
                    label="Old Student"
                    name="student_type"
                    value="Old Student"
                    class="selected"
                  />
                  <el-radio label="New Student" name="student_type" value="New Student" />
                </el-radio-group>
              </el-form-item>
            </el-col>
          </el-card>
        </el-row>
        <el-row>
          <el-col>
            <el-row>
              <el-card class="box-card user-bio" style="margin-top: 2%">
                <div
                  slot="header"
                  class="clearfix"
                >
                  <span>Student Information</span>
                </div>
                <el-row>
                  <div class="postInfo-container">
                    <el-col :span="16">
                      <el-form-item
                        v-if="reservation.student_type === 'Old Student'"
                        label-width="160px"
                        label="Search Student No:"
                        style="margin-bottom: 40px"
                        prop="student_no"
                        class="postInfo-container-item"
                      >
                        <el-input
                          v-model="reservation.student_no"
                          name="student_no"
                          maxlength="50"
                          placeholder="Student Number"
                          @blur="changeStudentNo"
                        >
                          <el-button v-if="isEdit === false" slot="append" class="primary" icon="el-icon-search" @click="searchStudent" />
                        </el-input>
                      </el-form-item>
                    </el-col>
                  </div>
                </el-row>
                <el-row>
                  <div v-if="reservation.first_name !== '' || reservation.student_type === 'New Student'" class="postInfo-container">
                    <el-row>
                      <el-col :span="8">
                        <el-form-item
                          label-width="120px"
                          label="First Name"
                          class="postInfo-container-item"
                          prop="first_name"
                        >
                          <el-input
                            v-model="reservation.first_name"
                            name="first_name"
                            maxlength="50"
                            placeholder="Student's First Name"
                            :readonly="reservation.student_type === 'Old Student'"
                          />
                        </el-form-item>
                      </el-col>

                      <el-col :span="8">
                        <el-form-item
                          label-width="120px"
                          label="Middle Name"
                          class="postInfo-container-item"
                          prop="middle_name"
                        >
                          <el-input
                            v-model="reservation.middle_name"
                            name="middle_name"
                            maxlength="50"
                            placeholder="Student's Middle Name"
                            :readonly="reservation.student_type === 'Old Student'"
                          />
                        </el-form-item>
                      </el-col>

                      <el-col :span="8">
                        <el-form-item
                          label-width="120px"
                          label="Last Name"
                          class="postInfo-container-item"
                          prop="last_name"
                        >
                          <el-input
                            v-model="reservation.last_name"
                            name="last_name"
                            maxlength="50"
                            placeholder="Student's Last Name"
                            :readonly="reservation.student_type === 'Old Student'"
                          />
                        </el-form-item>
                      </el-col>
                      <el-col :span="8">
                        <el-form-item
                          label-width="120px"
                          label="Birthdate"
                          class="postInfo-container-item"
                          prop="birthdate"
                        >
                          <el-date-picker
                            v-model="reservation.birthdate"
                            name="birthdate"
                            type="date"
                            :picker-options="datePickerOptions"
                            placeholder="Student's Birthdate"
                            style="width: 100%"
                            value-format="yyyy-MM-dd"
                            format="yyyy-MM-dd"
                            :readonly="reservation.student_type === 'Old Student'"
                          />
                        </el-form-item>
                      </el-col>
                      <el-col :span="8">
                        <el-form-item
                          label-width="120px"
                          label="Mobile"
                          class="postInfo-container-item"
                          prop="mobile"
                        >
                          <el-input
                            v-model="reservation.mobile"
                            name="mobile"
                            maxlength="11"
                            minlength="11"
                            placeholder="Student's Mobile Number"
                            :readonly="reservation.student_type === 'Old Student'"
                          />
                        </el-form-item>
                      </el-col>
                    </el-row>
                  </div>
                </el-row>
              </el-card>
            </el-row>
          </el-col>
          <el-col style="margin-top: 2%;">
            <el-row>
              <el-card class="box-card user-bio">
                <div slot="header" class="clearfix">
                  <span>Reservation Information for School Year {{ school_year.name }} </span>
                </div>
                <div class="postInfo-container">
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Department"
                      class="postInfo-container-item"
                      prop="department"
                    >
                      <el-select
                        v-model="reservation.department"
                        class="filter-item"
                        placeholder="Please Select Department"
                        @change="filterGrade"
                      >
                        <el-option
                          v-for="item in filtered_departments"
                          :key="item.id"
                          :label="item.name | uppercaseFirst"
                          :value="item.id"
                        />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Grade"
                      class="postInfo-container-item"
                      prop="grade"
                    >
                      <el-select
                        v-model="reservation.grade"
                        class="filter-item"
                        placeholder="Please Select Grade"
                        @change="filterSection"
                      >
                        <el-option
                          v-for="item in grades"
                          :key="item.id"
                          :label="item.name | uppercaseFirst"
                          :value="item.id"
                        />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Section"
                      class="postInfo-container-item"
                      prop="section"
                    >
                      <el-select
                        v-model="reservation.section"
                        class="filter-item"
                        placeholder="Please Select Section"
                      >
                        <el-option
                          v-for="item in sections"
                          :key="item.id"
                          :label="item.name | uppercaseFirst"
                          :value="item.id"
                        />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Reservation Fee"
                      class="postInfo-container-item"
                      prop="fee"
                    >
                      <el-input
                        v-model="reservation.fee"
                        class="filter-item"
                        readonly
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item
                      label-width="120px"
                      label="Remarks"
                      prop="remarks"
                    >
                      <el-input
                        v-model="reservation.remarks"
                        type="textarea"
                        name="remarks"
                        maxlength="500"
                        minlength="5"
                        placeholder="Remarks"
                      />
                    </el-form-item>
                  </el-col>
                </div>
              </el-card>
              <br>
            </el-row>
          </el-col>
        </el-row>
      </div>
    </el-form>
    <!-- DIALOG -->
    <el-dialog
      v-if="student.first_name"
      v-permission="['manage reservation']"
      :title="formTitle"
      :visible.sync="reservationConfirmDialog"
    >
      <div class="form-container">
        <el-form
          ref="studentForm"
          :model="student"
          label-position="left"
          label-width="150px"
        >
          <el-row>
            <el-col>
              <el-row>
                <div class="postInfo-container">
                  <el-col>
                    <el-form-item
                      label-width="120px"
                      label="Student No"
                      class="postInfo-container-item"
                      prop="student_no"
                    >
                      <el-input
                        v-model="student.student_no"
                        name="student_no"
                        maxlength="50"
                        placeholder="Student No."
                        readonly
                      />
                    </el-form-item>
                  </el-col>
                </div>
              </el-row>
              <el-row>
                <div class="postInfo-container">
                  <el-col>
                    <el-form-item
                      label-width="120px"
                      label="First Name"
                      class="postInfo-container-item"
                      prop="first_name"
                    >
                      <el-input
                        v-model="student.first_name"
                        name="first_name"
                        maxlength="50"
                        placeholder="First Name"
                        readonly
                      />
                    </el-form-item>
                  </el-col>
                  <el-col>
                    <el-form-item
                      label-width="120px"
                      label="Middle Name"
                      class="postInfo-container-item"
                      prop="middle_name"
                    >
                      <el-input
                        v-model="student.middle_name"
                        name="middle_name"
                        maxlength="50"
                        placeholder="Middle Name"
                        readonly
                      />
                    </el-form-item>
                  </el-col>
                  <el-col>
                    <el-form-item
                      label-width="120px"
                      label="Last Name"
                      class="postInfo-container-item"
                      prop="last_name"
                    >
                      <el-input
                        v-model="student.last_name"
                        name="last_name"
                        maxlength="50"
                        placeholder="Last Name"
                        readonly
                      />
                    </el-form-item>
                  </el-col>
                  <el-col>
                    <el-form-item
                      label-width="120px"
                      label="Address"
                      prop="address"
                    >
                      <el-input
                        v-model="student.address"
                        type="textarea"
                        name="address"
                        maxlength="100"
                        minlength="5"
                        placeholder="Address"
                        readonly
                      />
                    </el-form-item>
                  </el-col>
                  <el-col>
                    <el-form-item
                      label-width="120px"
                      label="Mobile"
                      class="postInfo-container-item"
                      prop="mobile"
                    >
                      <el-input
                        v-model="student.mobile"
                        name="mobile"
                        maxlength="11"
                        minlength="11"
                        placeholder="Mobile Number"
                        readonly
                      />
                    </el-form-item>
                  </el-col>
                  <el-col>
                    <el-form-item
                      label-width="120px"
                      label="Birthdate"
                      class="postInfo-container-item"
                      prop="birthdate"
                    >
                      <el-input
                        v-model="student.birthdate"
                        name="birthdate"
                        maxlength="100"
                        minlength="5"
                        placeholder="Birthdate"
                        readonly
                      />
                    </el-form-item>
                  </el-col>
                </div>
                <br>
              </el-row>
            </el-col>
          </el-row>
        </el-form>
        <div slot="footer" class="dialog-footer" style="text-align:center; margin-top: 2%">
          <el-button @click="cancelConfirmReservation">Cancel</el-button>
          <el-button :disabled="submitted" type="primary" @click="confirmReservation">Confirm</el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Sticky from '@/components/Sticky'; // Sticky header

import Resource from '@/api/resource';
import permission from '@/directive/permission';
import waves from '@/directive/waves';

const reservationResource = new Resource('reservations');
const studentResource = new Resource('students');
const departmentResource = new Resource('departments');
const gradeResource = new Resource('grades');
const sectionResource = new Resource('sections');
const schoolYearResource = new Resource('schoolyears');

const defaultForm = {
  reservation: {
    id: '',
    student_id: '',
    student_no: '',
    first_name: '',
    middle_name: '',
    last_name: '',
    birthdate: '',
    mobile: '',
    department: '',
    grade: '',
    section: '',
    school_year_id: '',
    fee: '1000',
    student_type: 'Old Student',
  },
};

export default {
  name: 'NewReservation',
  components: {
    Sticky,
    // Pagination,
    // MDinput,
  },
  directives: { permission, waves },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
    editReservation: {
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
          mobile: '',
          department: '',
          grade: '',
          section: '',
          school_year_id: '',
          fee: '',
          student_type: '',
        };
      },
    },
  },
  data() {
    return {
      formTitle: 'Confirm Reservation Details',
      hasStudentNoError: false,
      reservation: Object.assign({}, defaultForm.reservation),
      student: [{ student_no: '' }],
      school_year: [],
      departments: [],
      grades: [],
      sections: [],
      filtered_departments: [],
      filtered_grades: [],
      filtered_section: [],
      loading: false,
      changeStudNo: false,
      reservationConfirmDialog: false,
      reservationPaymentDialog: false,
      submitted: false,
      created: false,
      /* Date Picker */
      datePickerOptions: {
        disabledDate(date) {
          return date > new Date();
        },
      },
      rules: {
        student_no: [
          { required: true, trigger: 'blur' },
        ],
        first_name: [
          {
            required: true,
            message: 'First Name is required.',
            trigger: 'change',
          },
        ],
        middle_name: [{ min: 1, max: 50, trigger: 'change' }],
        last_name: [
          {
            required: true,
            message: 'Last Name is required.',
            trigger: 'change',
          },
        ],
        birthdate: [
          {
            required: true,
            message: 'Birthdate is required.',
            trigger: 'change',
          },
        ],
        department: [
          {
            required: true,
            message: 'Department is required.',
            trigger: 'change',
          },
        ],
        grade: [
          {
            required: true,
            message: 'Grade is required.',
            trigger: 'change',
          },
        ],
        section: [
          {
            required: true,
            message: 'Section is required.',
            trigger: 'change',
          },
        ],
      },
    };
  },
  created(){
    this.checkIfEdit();
    this.getSchoolYear();
    this.getDepartments();
    this.getGrades();
    this.getSections();
  },
  methods: {
    async searchStudent() {
      const { data } = await studentResource.list({
        student_no: this.reservation.student_no,
        student_id: this.reservation.student_id,
      });
      this.student = Object.assign({}, data[0]);
      if (data.length === 0) {
        this.$message({
          message: 'Student details does not exist.',
          type: 'error',
        });
        var temp_type = this.reservation.student_type;
        var temp_stud_no = this.reservation.student_no;
        this.$refs['postForm'].resetFields();
        this.reservation = {
          id: '',
          student_id: '',
          student_no: temp_stud_no,
          first_name: '',
          middle_name: '',
          last_name: '',
          birthdate: '',
          mobile: '',
          department: '',
          grade: '',
          section: '',
          fee: '',
          school_year_id: '',
          student_type: temp_type,
        };
      } else {
        this.reservationConfirmDialog = true;
      }
    },
    confirmReservation(){
      this.reservationConfirmDialog = false;
      this.reservation.student_id = this.student.id;
      this.reservation.first_name = this.student.first_name;
      this.reservation.middle_name = this.student.middle_name;
      this.reservation.last_name = this.student.last_name;
      this.reservation.birthdate = this.student.birthdate;
      this.reservation.mobile = this.student.mobile;
    },
    cancelConfirmReservation(){
      this.reservationConfirmDialog = false;
      this.student = { student_no: '' };
    },
    async getSchoolYear() {
      const { data } = await schoolYearResource.list({
        active: 'active',
      });
      if (data.length === 0) {
        this.$alert('No Active School Year. Please Configure First.', 'Reservation Error', {
          confirmButtonText: 'OK',
          callback: action => {
            this.$message({
              type: 'info',
              message: `Redirecting to School Year module.`,
            });
            this.$router.push('/schoolyear');
          },
        });
      } else {
        this.school_year = data[0];
        this.reservation.school_year_id = this.school_year.id;
      }
    },
    async getDepartments() {
      const { data } = await departmentResource.list({});
      this.departments = data;
      var temp_dept = [];
      this.school_year.department_fees.forEach(element => {
        if (!temp_dept.includes(element.department_id)) {
          this.departments.forEach(dept_element => {
            if (element.department_id === dept_element.id) {
              this.filtered_departments.push(dept_element);
              temp_dept.push(element.department_id);
            }
          });
        }
      });
    },
    async filterGrade() {
      this.reservation.grade = '';
      this.reservation.section = '';
      this.getGrades();
    },
    async filterSection() {
      this.reservation.section = '';
      this.getSections();
    },
    async getGrades() {
      const { data } = await gradeResource.list({ dept_id: this.reservation.department });
      this.grades = data;
    },
    async getSections() {
      const { data } = await sectionResource.list({ grade_id: this.reservation.grade });
      this.sections = data;
    },
    /* old methods */
    async checkDuplicateReservation() {
      const { data } = await reservationResource.list({
        student_no: this.reservation.student_no,
        school_year: this.school_year.id,
        student_id: this.student.id,
      });
      if (data.length > 0) {
        return true;
      } else {
        return false;
      }
    },
    validateStudentNo(callback, data) {
      if (data === true) {
        callback(new Error('Student Number is required'));
      } else {
        callback();
      }
    },
    // call back in saving data
    doWork(text, data) {
      if (data) {
        this.$message({
          message: 'Student Reservation already exists.',
          type: 'error',
        });
        this.hasDuplicate = true;
        // hide dialog view
        this.confirmDialogView = false;
      } else {
        this.hasDuplicate = false;
        this.$refs.postForm.validate((valid) => {
          if (valid) {
            if (this.isEdit === false && this.created === false){
              this.$confirm(
                'Do you want to create ' + this.reservation.first_name + "'s record?",
                'Confirm Student Reservation Details',
                {
                  confirmButtonText: 'OK',
                  cancelButtonText: 'Cancel',
                  center: true,
                }
              )
                .then(() => {
                  reservationResource
                    .store(this.reservation)
                    .then((response) => {
                      this.$message({
                        message:
                          'New Student ' +
                          this.reservation.first_name +
                          ' ' +
                          this.reservation.last_name +
                          ' has been created successfully.',
                        type: 'success',
                        duration: 5 * 1000,
                      });
                      this.loading = false;
                      this.created = true;
                    })
                    .catch((error) => {
                      console.log(error);
                    });
                })
                .catch(() => {
                  this.$message({
                    type: 'info',
                    message: 'Reservation cancelled',
                  });
                });
            } else {
              this.$confirm(
                'Do you want to update ' + this.reservation.first_name + "'s record?",
                'Confirm Save Student Details',
                {
                  confirmButtonText: 'OK',
                  cancelButtonText: 'Cancel',
                  center: true,
                }
              )
                .then(() => {
                  reservationResource
                    .update(this.reservation.id, this.reservation)
                    .then((response) => {
                      this.$message({
                        message:
                          'Student ' +
                          this.reservation.first_name +
                          ' ' +
                          this.reservation.last_name +
                          ' has been updated successfully.',
                        type: 'success',
                        duration: 5 * 1000,
                      });
                      this.loading = false;
                    })
                    .catch((error) => {
                      console.log(error);
                    });
                })
                .catch(() => {
                  this.$message({
                    type: 'info',
                    message: 'Update cancelled',
                  });
                });
            }
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      }
    },
    // proceed with saving or updating
    confirmation() {
      if (
        this.reservation.student_no === '' &&
        this.reservation.student_type === 'Old Student'
      ) {
        this.$message({
          message: 'Student number is required.',
          type: 'error',
        });
        return;
      } else {
        this.checkDuplicateReservation().then(this.doWork.bind(null, 'text'));
      }
    },
    // cancel checker
    confirmCancel() {
      this.$confirm(
        'Changes will be discarded. Continue?',
        'Warning',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning',
        }
      )
        .then(() => {
          this.$router.go(-1);
        });
    },
    // cancel checker
    confirmChange() {
      if ((this.reservation.student_no !== '') || (this.reservation.first_name !== '') || (this.reservation.middle_name !== '') || (this.reservation.last_name !== '') || (this.reservation.birthdate !== '') || (this.reservation.mobile !== '')) {
        this.reservationConfirmDialog = false;
        this.$confirm(
          'Changes will be discarded. Continue?',
          'Warning',
          {
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
            type: 'warning',
          }
        )
          .then(() => {
            if (this.changeStudNo === false) {
              var temp_type = this.reservation.student_type;
              this.reservation = {
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
                fee: '2000',
                school_year_id: this.school_year.id,
                student_type: temp_type,
              };
              this.$refs['postForm'].resetFields();
            } else {
              this.searchStudent();
            }
          })
          .catch(err => {
            if (this.changeStudNo === true) {
              this.changeStudNo = false;
              this.reservation.student_no = this.student.student_no;
            } else {
              if (this.reservation.student_type === 'Old Student') {
                this.reservation.student_type = 'New Student';
              } else {
                this.reservation.student_type = 'Old Student';
              }
            }
            console.error(err);
          });
      }
      if (this.reservation.student_type === 'New Student') {
        this.reservation.fee = '2000';
      } else if (this.reservation.student_type === 'Old Student') {
        this.reservation.fee = '1000';
      } else {
        this.reservation.fee = '0';
      }
    },
    // cancel checker
    changeStudentNo() {
      if (this.reservation.student_no !== this.student.student_no && this.student.student_no !== undefined) {
        this.changeStudNo = true;
        this.confirmChange();
      }
    },
    /* load edit data to form data */
    checkIfEdit() {
      if (this.isEdit === true) {
        this.reservation.id = this.editReservation.id;
        if (this.reservation.student === null) {
          this.reservation.student_no = this.editReservation.student.student_no;
        } else {
          this.reservation.student_no = '';
          this.student.first_name = this.editReservation.first_name;
          this.student.middle_name = this.editReservation.middle_name;
          this.student.last_name = this.editReservation.last_name;
          this.student.mobile = this.editReservation.mobile;
          this.student.birthdate = this.editReservation.birthdate;
        }
        this.reservation.student_id = this.editReservation.student_id;
        this.reservation.first_name = this.editReservation.first_name;
        this.reservation.middle_name = this.editReservation.middle_name;
        this.reservation.last_name = this.editReservation.last_name;
        this.reservation.mobile = this.editReservation.mobile;
        this.reservation.birthdate = this.editReservation.birthdate;
        this.reservation.department = this.editReservation.department_id;
        this.reservation.grade = this.editReservation.grade_id;
        this.reservation.section = this.editReservation.section_id;
        this.reservation.remarks = this.editReservation.remarks;
        if (this.editReservation.type === 'OLD'){
          this.reservation.student_type = 'Old Student';
        } else {
          this.reservation.student_type = 'New Student';
        }
      }
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import '~@/styles/mixin.scss';
.createPost-container {
  position: relative;
  .createPost-main-container {
    padding: 0 45px 20px 50px;
    .postInfo-container {
      position: relative;
      @include clearfix;
      margin-bottom: 10px;
      .postInfo-container-item {
        float: left;
        padding-left: 2%;
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
  padding-left: 2%;
}
</style>
