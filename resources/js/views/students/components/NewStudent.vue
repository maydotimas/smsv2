<template>
  <div class="createPost-container">
    <el-form
      ref="postForm"
      :model="student"
      :rules="rules"
      class="form-container"
    >
      <sticky v-if="isEdit===false && created===false" :class-name="'sub-navbar active'">
        <el-button
          v-loading="loading"
          style="margin-left: 10px"
          type="success"
          @click="confirmation"
        >
          Save New Student Record
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
          Update Student Record
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
                <el-radio-group v-model="student.student_type">
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
                  v-if="student.student_type === 'New Student'"
                  slot="header"
                  class="clearfix"
                >
                  <span>Student Information</span>
                </div>
                <el-form-item
                  v-if="student.student_type === 'Old Student'"
                  style="margin-bottom: 40px"
                  prop="student_no"
                >
                  <MDinput
                    v-model="student.student_no"
                    :maxlength="11"
                    name="student_no"
                    required
                  >
                    Student Number
                  </MDinput>
                </el-form-item>
                <div class="postInfo-container">
                  <el-row>
                    <el-col :span="8">
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
                          placeholder="Student's First Name"
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
                          v-model="student.middle_name"
                          name="middle_name"
                          maxlength="50"
                          placeholder="Student's Middle Name"
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
                          v-model="student.last_name"
                          name="last_name"
                          maxlength="50"
                          placeholder="Student's Last Name"
                        />
                      </el-form-item>
                    </el-col>

                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="Suffix"
                        class="postInfo-container-item"
                        prop="suffix"
                      >
                        <el-input
                          v-model="student.suffix"
                          name="suffix"
                          maxlength="3"
                          placeholder="Student's Suffix"
                        />
                      </el-form-item>
                    </el-col>

                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="Nickname"
                        class="postInfo-container-item"
                        prop="nickname"
                      >
                        <el-input
                          v-model="student.nickname"
                          name="nickname"
                          maxlength="50"
                          placeholder="Student's Nickname"
                        />
                      </el-form-item>
                    </el-col>

                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="LRN"
                        class="postInfo-container-item"
                        prop="lrn"
                      >
                        <el-input
                          v-model="student.lrn"
                          name="lrn"
                          maxlength="11"
                          placeholder="Student's LRN"
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
                          v-model="student.birthdate"
                          name="birthdate"
                          type="date"
                          :picker-options="datePickerOptions"
                          placeholder="Student's Birthdate"
                          style="width: 100%"
                          value-format="yyyy-MM-dd"
                          format="yyyy-MM-dd"
                        />
                      </el-form-item>
                    </el-col>
                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="Gender"
                        class="postInfo-container-item"
                        prop="gender"
                      >
                        <el-select
                          v-model="student.gender"
                          placeholder="Select Gender"
                        >
                          <el-option label="Male" value="male" />
                          <el-option label="Female" value="female" />
                        </el-select>
                      </el-form-item>
                    </el-col>
                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="Handedness"
                        class="postInfo-container-item"
                        prop="handedness"
                      >
                        <el-select
                          v-model="student.handedness"
                          placeholder="Select Handedness"
                        >
                          <el-option label="Left" value="left" />
                          <el-option label="Right" value="right" />
                          <el-option label="Both" value="both" />
                        </el-select>
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
                          v-model="student.mobile"
                          name="mobile"
                          maxlength="11"
                          minlength="11"
                          placeholder="Student's Mobile Number"
                        />
                      </el-form-item>
                    </el-col>
                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="Email"
                        class="postInfo-container-item"
                        prop="email"
                      >
                        <el-input
                          v-model="student.email"
                          name="email"
                          maxlength="100"
                          minlength="5"
                          placeholder="Student's Email Address"
                        />
                      </el-form-item>
                    </el-col>
                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="Religion"
                        class="postInfo-container-item"
                        prop="religion"
                      >
                        <el-input
                          v-model="student.religion"
                          name="religion"
                          maxlength="100"
                          minlength="5"
                          placeholder="Student's Religion"
                        />
                      </el-form-item>
                    </el-col>
                    <el-col :span="24">
                      <el-form-item
                        label-width="120px"
                        label="Address"
                        prop="address"
                      >
                        <el-input
                          v-model="student.address"
                          type="textarea"
                          name="address"
                          maxlength="500"
                          minlength="5"
                          placeholder="Student's Address"
                        />
                      </el-form-item>
                    </el-col>
                  </el-row>
                </div>
              </el-card>
            </el-row>
          </el-col>
          <el-col>
            <el-card
              class="box-card user-bio"
              style="margin-top: 2%; margin-bottom: 2%"
            >
              <el-row>
                <el-col>
                  <el-form-item
                    label="Applicable Family Information"
                    title="Please check all applicable family information"
                    prop="family"
                  >
                    <el-checkbox-group v-model="student.family" @change="check">
                      <el-checkbox label="Father" name="family" />
                      <el-checkbox
                        label="Mother"
                        name="family"
                        value="mother"
                      />
                      <el-checkbox
                        label="Guardian"
                        name="family"
                        value="guardian"
                      />
                    </el-checkbox-group>
                  </el-form-item>
                </el-col>
                <el-col>
                  <el-form-item
                    label="Emergency Contact Person"
                    prop="emergency"
                  >
                    <el-radio-group v-model="student.emergency">
                      <el-radio
                        v-if="checkIfApplicableFamilyContact('Father')"
                        label="Father"
                        name="family"
                      />
                      <el-radio
                        v-if="checkIfApplicableFamilyContact('Mother')"
                        label="Mother"
                        name="family"
                      />
                      <el-radio
                        v-if="checkIfApplicableFamilyContact('Guardian')"
                        label="Guardian"
                        name="family"
                      />
                      <el-radio
                        v-if="checkIfApplicableFamilyContact('None')"
                        label="Please select applicable family information"
                        name="family"
                        disabled
                      />
                    </el-radio-group>
                  </el-form-item>
                </el-col>
              </el-row>
            </el-card>
            <el-row v-show="checkIfApplicableFamilyContact('Father')">
              <el-card class="box-card user-bio">
                <div slot="header" class="clearfix">
                  <span>Father's Information</span>
                </div>
                <div class="postInfo-container">
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="First Name"
                      class="postInfo-container-item"
                      prop="father_first_name"
                    >
                      <el-input
                        v-model="student.father_first_name"
                        name="first_name"
                        maxlength="50"
                        placeholder="Father's First Name"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Middle Name"
                      class="postInfo-container-item"
                      prop="father_middle_name"
                    >
                      <el-input
                        v-model="student.father_middle_name"
                        name="first_name"
                        maxlength="50"
                        placeholder="Father's Middle Name"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Last Name"
                      class="postInfo-container-item"
                      prop="father_last_name"
                    >
                      <el-input
                        v-model="student.father_last_name"
                        name="last_name"
                        maxlength="50"
                        placeholder="Father's Last Name"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item
                      label-width="120px"
                      label="Address"
                      prop="father_address"
                    >
                      <el-input
                        v-model="student.father_address"
                        type="textarea"
                        name="address"
                        maxlength="100"
                        minlength="5"
                        placeholder="Father's Address"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Mobile"
                      class="postInfo-container-item"
                      prop="father_mobile"
                    >
                      <el-input
                        v-model="student.father_mobile"
                        name="mobile"
                        maxlength="11"
                        minlength="11"
                        placeholder="Father's Mobile Number"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Email"
                      class="postInfo-container-item"
                      prop="father_email"
                    >
                      <el-input
                        v-model="student.father_email"
                        name="email"
                        maxlength="100"
                        minlength="5"
                        placeholder="Father's Email Addess"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Occupation"
                      class="postInfo-container-item"
                      prop="father_occupation"
                    >
                      <el-input
                        v-model="student.father_occupation"
                        name="occupation"
                        maxlength="100"
                        minlength="5"
                        placeholder="Father's Occupation"
                      />
                    </el-form-item>
                  </el-col>
                </div>
              </el-card>
              <br>
            </el-row>
            <el-row v-show="checkIfApplicableFamilyContact('Mother')">
              <el-card class="box-card user-bio">
                <div slot="header" class="clearfix">
                  <span>Mother's Information</span>
                </div>
                <div class="postInfo-container">
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="First Name"
                      class="postInfo-container-item"
                      prop="mother_first_name"
                    >
                      <el-input
                        v-model="student.mother_first_name"
                        name="first_name"
                        maxlength="50"
                        placeholder="Mother's First Name"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Middle Name"
                      class="postInfo-container-item"
                      prop="mother_middle_name"
                    >
                      <el-input
                        v-model="student.mother_middle_name"
                        name="first_name"
                        maxlength="50"
                        placeholder="Mother's Middle Name"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Last Name"
                      class="postInfo-container-item"
                      prop="mother_last_name"
                    >
                      <el-input
                        v-model="student.mother_last_name"
                        name="last_name"
                        maxlength="50"
                        placeholder="Mother's Last Name"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item
                      label-width="120px"
                      label="Address"
                      prop="mother_address"
                    >
                      <el-input
                        v-model="student.mother_address"
                        type="textarea"
                        name="address"
                        maxlength="100"
                        minlength="5"
                        placeholder="Mother's Address"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Mobile"
                      class="postInfo-container-item"
                      prop="mother_mobile"
                    >
                      <el-input
                        v-model="student.mother_mobile"
                        name="mobile"
                        maxlength="11"
                        minlength="11"
                        placeholder="Mother's Mobile Number"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Email"
                      class="postInfo-container-item"
                      prop="mother_email"
                    >
                      <el-input
                        v-model="student.mother_email"
                        name="email"
                        maxlength="100"
                        minlength="5"
                        placeholder="Mother's Email Addess"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Occupation"
                      class="postInfo-container-item"
                      prop="mother_occupation"
                    >
                      <el-input
                        v-model="student.mother_occupation"
                        name="occupation"
                        maxlength="100"
                        minlength="5"
                        placeholder="Mother's Occupation"
                      />
                    </el-form-item>
                  </el-col>
                </div>
              </el-card>
              <br>
            </el-row>
            <el-row v-show="checkIfApplicableFamilyContact('Guardian')">
              <el-card class="box-card user-bio">
                <div slot="header" class="clearfix">
                  <span>Guardian's Information</span>
                </div>
                <div class="postInfo-container">
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="First Name"
                      class="postInfo-container-item"
                      prop="guardian_first_name"
                    >
                      <el-input
                        v-model="student.guardian_first_name"
                        name="first_name"
                        maxlength="50"
                        placeholder="Guardian's First Name"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Middle Name"
                      class="postInfo-container-item"
                      prop="guardian_middle_name"
                    >
                      <el-input
                        v-model="student.guardian_middle_name"
                        name="first_name"
                        maxlength="50"
                        placeholder="Guardian's Middle Name"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Last Name"
                      class="postInfo-container-item"
                      prop="guardian_last_name"
                    >
                      <el-input
                        v-model="student.guardian_last_name"
                        name="last_name"
                        maxlength="50"
                        placeholder="Guardian's Last Name"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="16">
                    <el-form-item
                      label-width="120px"
                      label="Address"
                      prop="guardian_address"
                    >
                      <el-input
                        v-model="student.guardian_address"
                        type="textarea"
                        name="address"
                        maxlength="100"
                        minlength="5"
                        placeholder="Guardian's Address"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="16">
                    <el-form-item
                      label-width="120px"
                      label="Relationship"
                      class="postInfo-container-item"
                      prop="relationship"
                    >
                      <el-select
                        v-model="student.relationship"
                        placeholder="Select Relationship"
                      >
                        <el-option label="Auntie" value="Auntie" />
                        <el-option label="Cousin" value="Cousin" />
                        <el-option label="Grandfather" value="Grandfather" />
                        <el-option label="Grandmother" value="Grandmother" />
                        <el-option label="Sibling" value="Sibling" />
                        <el-option label="Uncle" value="Uncle" />
                        <el-option label="Other" value="Other" />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Mobile"
                      class="postInfo-container-item"
                      prop="guardian_mobile"
                    >
                      <el-input
                        v-model="student.guardian_mobile"
                        name="mobile"
                        maxlength="11"
                        minlength="11"
                        placeholder="Guardian's Mobile Number"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Email"
                      class="postInfo-container-item"
                      prop="guardian_email"
                    >
                      <el-input
                        v-model="student.guardian_email"
                        name="email"
                        maxlength="100"
                        minlength="5"
                        placeholder="Guardian's Email Addess"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Occupation"
                      class="postInfo-container-item"
                      prop="guardian_occupation"
                    >
                      <el-input
                        v-model="student.guardian_occupation"
                        name="occupation"
                        maxlength="100"
                        minlength="5"
                        placeholder="Guardian's Occupation"
                      />
                    </el-form-item>
                  </el-col>
                </div>
              </el-card>
            </el-row>
          </el-col>
        </el-row>
      </div>
    </el-form>
  </div>
</template>

<script>
import Sticky from '@/components/Sticky'; // Sticky header
import MDinput from '@/components/MDinput';

import Resource from '@/api/resource';
import permission from '@/directive/permission';
import waves from '@/directive/waves';

const studentResource = new Resource('students');

const defaultForm = {
  student: {
    id: '',
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

export default {
  name: 'NewStudent',
  components: {
    Sticky,
    // Pagination,
    MDinput,
  },
  directives: { permission, waves },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          avatar: '',
          roles: [],
        };
      },
    },
  },
  data() {
    const validateRequire = (rule, value, callback) => {
      var field = '';
      var type = '';
      if (rule.field === 'student_no') {
        field = 'Student Number';
        type = 'Student';
      } else if (rule.field === 'father_first_name') {
        field = 'First Name';
        type = 'Father';
      } else if (rule.field === 'father_last_name') {
        field = 'Last Name';
        type = 'Father';
      } else if (rule.field === 'father_address') {
        field = 'Address';
        type = 'Father';
      } else if (rule.field === 'father_email') {
        field = 'Email';
        type = 'Father';
      } else if (rule.field === 'father_mobile') {
        field = 'Mobile';
        type = 'Father';
      } else if (rule.field === 'mother_first_name') {
        field = 'First Name';
        type = 'Mother';
      } else if (rule.field === 'mother_last_name') {
        field = 'Last Name';
        type = 'Mother';
      } else if (rule.field === 'mother_address') {
        field = 'Address';
        type = 'Mother';
      } else if (rule.field === 'mother_email') {
        field = 'Email';
        type = 'Mother';
      } else if (rule.field === 'mother_mobile') {
        field = 'Mobile';
        type = 'Mother';
      } else if (rule.field === 'guardian_first_name') {
        field = 'First Name';
        type = 'Guardian';
      } else if (rule.field === 'guardian_last_name') {
        field = 'Last Name';
        type = 'Guardian';
      } else if (rule.field === 'guardian_address') {
        field = 'Address';
        type = 'Guardian';
      } else if (rule.field === 'guardian_email') {
        field = 'Email';
        type = 'Guardian';
      } else if (rule.field === 'guardian_mobile') {
        field = 'Mobile';
        type = 'Guardian';
      } else if (rule.field === 'relationship') {
        field = 'Relationship';
        type = 'Guardian';
      } else {
        field = rule.field;
      }
      // check if applicable for validation
      if (this.checkIfApplicableFamilyContact(type)) {
        if (
          value === '' &&
          rule.field !== 'father_mobile' &&
          rule.field !== 'guardian_mobile' &&
          rule.field !== 'mother_mobile'
        ) {
          callback(new Error(field + ' is required'));
        } else {
          if (value !== '') {
            if (
              rule.field === 'guardian_email' ||
              rule.field === 'mother_email' ||
              rule.field === 'father_email'
            ) {
              const regex = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/);
              if (!regex.test(value)) {
                callback(new Error('Invalid ' + field.toLowerCase() + ' format.'));
              }
            } else if (
              rule.field === 'guardian_address' ||
              rule.field === 'mother_address' ||
              rule.field === 'father_address'
            ) {
              const regex = new RegExp(/^[\w\-\s]+$/);
              if (!regex.test(value)) {
                callback(new Error('Invalid ' + field.toLowerCase() + ' format.'));
              }
            } else if (
              rule.field === 'father_mobile' ||
              rule.field === 'guardian_mobile' ||
              rule.field === 'mother_mobile'
            ) {
              const regex = new RegExp(/^(09|\+639)\d{9}$/);
              if (!regex.test(value)) {
                callback(new Error('Invalid ' + field.toLowerCase() + ' format.'));
              }
            }
          }
          callback();
        }
      } else {
        if (type === 'Student') {
          const regex = new RegExp(/[0-9]{4}-[0-9]{7}$/);
          if (regex.test(value)) {
            callback(new Error('Invalid ' + field.toLowerCase() + ' format.'));
          } else {
            this.checkDuplicateStudentNo().then(
              this.validateStudentNo.bind(null, callback)
            );
          }
        }
        callback();
      }
    };
    const validateNames = (rule, value, callback) => {
      const regex = new RegExp(/^((?:[A-Z](?:('|(?:[a-z]{1,3}))[A-Z])?[a-z]+)|(?:[A-Z]\.))(?:([ -])((?:[A-Z](?:('|(?:[a-z]{1,3}))[A-Z])?[a-z]+)|(?:[A-Z]\.)))?$/);
      var field = '';
      if (rule.field === 'first_name') {
        field = 'First Name';
      } else if (rule.field === 'last_name') {
        field = 'Last Name';
      }
      if (value.length === 0) {
        callback(new Error(field + ' is required'));
      }
      if (!regex.test(value)) {
        callback(new Error('Invalid format for ' + field));
      } else {
        callback();
      }
    };
    return {
      formTitle: 'Confirm Student Details',
      hasStudentNoError: false,
      student: Object.assign({}, defaultForm.student),
      father: Object.assign({}, defaultForm.father),
      mother: Object.assign({}, defaultForm.mother),
      guardian: Object.assign({}, defaultForm.guardian),
      loading: false,
      created: false,
      /* Date Picker */
      datePickerOptions: {
        disabledDate(date) {
          return date > new Date();
        },
      },
      rules: {
        student_no: [
          { required: true, validator: validateRequire, trigger: 'blur' },
          // { min: 3, max: 5, message: 'First Name should be at l', trigger: 'blur' }
        ],
        first_name: [
          {
            required: true,
            message: 'First Name is required.',
            trigger: 'change',
          },
          { required: true, validator: validateNames, trigger: 'blur' },
          // { min: 3, max: 5, message: 'First Name should be at l', trigger: 'blur' }
        ],
        middle_name: [{ min: 1, max: 50, trigger: 'change' }],
        last_name: [
          {
            required: true,
            message: 'Last Name is required.',
            trigger: 'change',
          },
          { required: true, validator: validateNames, trigger: 'blur' },
        ],
        lrn: [
          { required: true, message: 'LRN is required.', trigger: 'change' },
        ],
        birthdate: [
          {
            required: true,
            message: 'Birthdate is required.',
            trigger: 'change',
          },
        ],
        gender: [
          { required: true, message: 'Gender is required', trigger: 'change' },
        ],
        handedness: [
          {
            required: true,
            message: 'Handedness is required',
            trigger: 'change',
          },
        ],
        mobile: [
          {
            required: true,
            message: 'Mobile number is required',
            trigger: 'change',
          },
          {
            required: true,
            pattern: /^(09|\+639)\d{9}$/,
            message: 'Invalid Mobile Number format.',
            trigger: 'change',
          },
        ],
        email: [
          {
            required: true,
            message: 'Email is required',
            trigger: 'blur',
          },
          {
            required: true,
            pattern: /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/,
            message: 'Invalid email format.',
            trigger: 'change',
          },
        ],
        address: [
          {
            required: true,
            pattern: /^[\w\-\s]+$/,
            message: 'Invalid address format.',
            trigger: 'change',
          },
        ],
        family: [
          {
            type: 'array',
            required: true,
            message: 'Please select at least one family member.',
            trigger: 'blur',
          },
          {
            type: 'array',
            required: true,
            message: 'Please select at least one family member.',
            trigger: 'change',
          },
        ],
        emergency: [
          {
            required: true,
            message: 'Please select an emergency contact.',
            trigger: 'blur',
          },
          {
            required: true,
            message: 'Please select an emergency contact.',
            trigger: 'change',
          },
        ],
        father_first_name: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        father_last_name: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        father_address: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        father_email: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        father_mobile: [{ required: true, validator: validateRequire, trigger: 'blur' }],
        mother_first_name: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        mother_last_name: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        mother_address: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        mother_email: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        mother_mobile: [{ required: true, validator: validateRequire, trigger: 'blur' }],
        guardian_first_name: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        guardian_last_name: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        relationship: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        guardian_address: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        guardian_email: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
        guardian_mobile: [
          { required: true, validator: validateRequire, trigger: 'blur' },
        ],
      },
    };
  },
  created(){
    this.checkIfEdit();
  },
  methods: {
    async checkDuplicateStudentNo() {
      const { data } = await studentResource.list({
        student_no: this.student.student_no,
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
          message: 'Student Number already exists.',
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
                'Do you want to create ' + this.student.first_name + "'s record?",
                'Confirm Student Details',
                {
                  confirmButtonText: 'OK',
                  cancelButtonText: 'Cancel',
                  center: true,
                }
              )
                .then(() => {
                  studentResource
                    .store(this.student)
                    .then((response) => {
                      this.$message({
                        message:
                          'New Student ' +
                          this.student.first_name +
                          ' ' +
                          this.student.last_name +
                          ' has been created successfully.',
                        type: 'success',
                        duration: 5 * 1000,
                      });
                      console.log(response);
                      this.student.id = response.data.id;
                      this.created = true;
                      this.loading = false;
                    })
                    .catch((error) => {
                      console.log(error);
                    });
                })
                .catch(() => {
                  this.$message({
                    type: 'info',
                    message: 'Create cancelled',
                  });
                });
            } else {
              this.$confirm(
                'Do you want to update ' + this.student.first_name + "'s record?",
                'Confirm Save Student Details',
                {
                  confirmButtonText: 'OK',
                  cancelButtonText: 'Cancel',
                  center: true,
                }
              )
                .then(() => {
                  studentResource
                    .update(this.student.id, this.student)
                    .then((response) => {
                      this.$message({
                        message:
                          'Student ' +
                          this.student.first_name +
                          ' ' +
                          this.student.last_name +
                          ' has been updated successfully.',
                        type: 'success',
                        duration: 5 * 1000,
                      });
                      console.log(response);
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
        this.student.student_no === '' &&
        this.student.student_type === 'Old Student'
      ) {
        this.$message({
          message: 'Student number is required.',
          type: 'error',
        });
        return;
      } else {
        this.checkDuplicateStudentNo().then(this.doWork.bind(null, 'text'));
      }
    },
    // check to display family information
    checkIfApplicableFamilyContact(type) {
      if (type === 'None' && this.student.family.length === 0) {
        return true;
      }
      return this.student.family.includes(type);
    },
    // check if selected family, if not selected remove from emergency
    check() {
      if (!this.checkIfApplicableFamilyContact(this.student.emergency)) {
        this.student.emergency = '';
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
    /* load edit data to form data */
    checkIfEdit(){
      console.log(this.user);
      if (this.isEdit === true){
        this.student.id = this.user.id;
        this.student.student_no = this.user.student_no;
        this.student.lrn = this.user.lrn;
        this.student.first_name = this.user.first_name;
        this.student.middle_name = this.user.middle_name;
        this.student.last_name = this.user.last_name;
        this.student.suffix = this.user.suffix;
        this.student.nickname = this.user.nickname;
        this.student.handedness = this.user.handedness;
        this.student.religion = this.user.religion;
        this.student.email = this.user.email;
        this.student.mobile = this.user.mobile;
        this.student.address = this.user.address;
        this.student.avatar = this.user.avatar;
        this.student.gender = this.user.gender;
        this.student.birthdate = this.user.birthdate;
        this.student.student_type = this.user.student_type;
        this.student.family = [];
        if (this.user.father){
          this.student.family.push('Father');
          if (this.user.father.id === this.user.emergency_contact) {
            this.student.emergency = 'Father';
          }
          this.student.father_id = this.user.father_id;
          this.student.father_first_name = this.user.father.first_name;
          this.student.father_middle_name = this.user.father.middle_name;
          this.student.father_last_name = this.user.father.last_name;
          this.student.father_mobile = this.user.father.mobile;
          this.student.father_email = this.user.father.email;
          this.student.father_occupation = this.user.father.occupation;
          this.student.father_address = this.user.father.address;
        }
        if (this.user.mother){
          this.student.family.push('Mother');
          if (this.user.mother.id === this.user.emergency_contact) {
            this.student.emergency = 'Mother';
          }
          this.student.mother_id = this.user.mother.id;
          this.student.mother_first_name = this.user.mother.first_name;
          this.student.mother_middle_name = this.user.mother.middle_name;
          this.student.mother_last_name = this.user.mother.last_name;
          this.student.mother_mobile = this.user.mother.mobile;
          this.student.mother_email = this.user.mother.email;
          this.student.mother_occupation = this.user.mother.occupation;
          this.student.mother_address = this.user.mother.address;
        }
        if (this.user.guardian){
          this.student.family.push('Guardian');
          if (this.user.guardian.id === this.user.emergency_contact) {
            this.student.emergency = 'Guardian';
          }
          this.student.guardian_id = this.user.guardian.id;
          this.student.guardian_first_name = this.user.guardian.first_name;
          this.student.guardian_middle_name = this.user.guardian.middle_name;
          this.student.guardian_last_name = this.user.guardian.last_name;
          this.student.guardian_mobile = this.user.guardian.mobile;
          this.student.guardian_email = this.user.guardian.email;
          this.student.guardian_occupation = this.user.guardian.occupation;
          this.student.guardian_address = this.user.guardian.address;
          this.student.relationship = this.user.guardian.type;
        }
        console.log(this.student);
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
.createPost-container label.el-form-item__label {
  text-align: left;
  padding-left: 2%;
}
</style>
