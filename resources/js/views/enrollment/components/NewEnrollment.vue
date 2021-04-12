<template>
  <div class="createPost-container">
    <el-form
      ref="postForm"
      :model="enrollment"
      :rules="rules"
      class="form-container"
    >
      <sticky v-if="isEdit===false && created === false" :class-name="'sub-navbar active'">
        <el-button
          v-loading="loading"
          style="margin-left: 10px"
          type="danger"
          @click="askReservation"
        >
          Search Reservation
        </el-button>
        <el-button
          v-loading="loading"
          style="margin-left: 10px"
          type="success"
          @click="confirmation"
        >
          Save New Enrollment Record
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
          Update Enrollment Record
        </el-button>
        <el-button v-loading="loading" type="warning" @click="confirmCancel">
          Cancel
        </el-button>
      </sticky>

      <div class="createPost-main-container">
        <el-row v-if="hasReservation === false">
          <el-card class="box-card" style="margin-top: 2%">
            <el-col>
              <el-form-item label="Student Type">
                <el-radio-group v-model="enrollment.student_type" :disabled="isEdit === true" @change="confirmChange">
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
        <!-- NEW ENROLLMENT DETAILS  -->
        <el-row>
          <el-col v-if="hasReservation === false">
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
                        v-if="enrollment.student_type === 'Old Student'"
                        label-width="160px"
                        label="Search Student No:"
                        style="margin-bottom: 40px"
                        prop="student_no"
                        class="postInfo-container-item"
                      >
                        <el-input
                          v-model="enrollment.student_no"
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
                  <div v-if="enrollment.first_name !== '' || enrollment.student_type === 'New Student'" class="postInfo-container">
                    <el-row>
                      <el-col :span="8">
                        <el-form-item
                          label-width="120px"
                          label="First Name"
                          class="postInfo-container-item"
                          prop="first_name"
                        >
                          <el-input
                            v-model="enrollment.first_name"
                            name="first_name"
                            maxlength="50"
                            placeholder="Student's First Name"
                            :readonly="enrollment.student_type === 'Old Student'"
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
                            v-model="enrollment.middle_name"
                            name="middle_name"
                            maxlength="50"
                            placeholder="Student's Middle Name"
                            :readonly="enrollment.student_type === 'Old Student'"
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
                            v-model="enrollment.last_name"
                            name="last_name"
                            maxlength="50"
                            placeholder="Student's Last Name"
                            :readonly="enrollment.student_type === 'Old Student'"
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
                            v-model="enrollment.birthdate"
                            name="birthdate"
                            type="date"
                            :picker-options="datePickerOptions"
                            placeholder="Student's Birthdate"
                            style="width: 100%"
                            value-format="yyyy-MM-dd"
                            format="yyyy-MM-dd"
                            :readonly="enrollment.student_type === 'Old Student'"
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
                            v-model="enrollment.mobile"
                            name="mobile"
                            maxlength="11"
                            minlength="11"
                            placeholder="Student's Mobile Number"
                            :readonly="enrollment.student_type === 'Old Student'"
                          />
                        </el-form-item>
                      </el-col>
                    </el-row>
                  </div>
                </el-row>
              </el-card>
            </el-row>
          </el-col>
          <el-col v-if="hasReservation === false" style="margin-top: 2%;">
            <el-row>
              <el-card class="box-card user-bio">
                <div slot="header" class="clearfix">
                  <span>Enrollment Information for School Year {{ school_year.name }} </span>
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
                        v-model="enrollment.department"
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
                        v-model="enrollment.grade"
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
                        v-model="enrollment.section"
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
                      label="Enrollment Fee"
                      class="postInfo-container-item"
                      prop="fee"
                    >
                      <el-input
                        v-model="enrollment.fee"
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
                        v-model="enrollment.remarks"
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
          <!-- RESERVATION DETAILS -->
          <el-col v-if="hasReservation === true">
            <el-row>
              <el-card class="box-card user-bio" style="margin-top: 2%">
                <div
                  slot="header"
                  class="clearfix"
                >
                  <span>Create Enrollment for {{ enrollment.first_name }} {{ enrollment.last_name }}</span>
                </div>
                <el-row>
                  <div v-if="enrollment.first_name !== '' || enrollment.student_type === 'New Student'" class="postInfo-container">
                    <el-row>
                      <el-col :span="8">
                        <el-form-item
                          label-width="120px"
                          label="First Name"
                          class="postInfo-container-item"
                          prop="first_name"
                        >
                          <el-input
                            v-model="enrollment.first_name"
                            name="first_name"
                            maxlength="50"
                            placeholder="Student's First Name"
                            readonly
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
                            v-model="enrollment.middle_name"
                            name="middle_name"
                            maxlength="50"
                            placeholder="Student's Middle Name"
                            readonly
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
                            v-model="enrollment.last_name"
                            name="last_name"
                            maxlength="50"
                            placeholder="Student's Last Name"
                            readonly
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
                            v-model="enrollment.birthdate"
                            name="birthdate"
                            type="date"
                            :picker-options="datePickerOptions"
                            placeholder="Student's Birthdate"
                            style="width: 100%"
                            value-format="yyyy-MM-dd"
                            format="yyyy-MM-dd"
                            readonly
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
                            v-model="enrollment.mobile"
                            name="mobile"
                            maxlength="11"
                            minlength="11"
                            placeholder="Student's Mobile Number"
                            readonly
                          />
                        </el-form-item>
                      </el-col>
                      <el-col :span="8">
                        <el-form-item
                          label-width="120px"
                          label="Student No."
                          class="postInfo-container-item"
                          prop="mobile"
                        >
                          <el-input
                            v-model="enrollment.student_no"
                            name="mobile"
                            maxlength="11"
                            minlength="11"
                            placeholder="Student's Mobile Number"
                            readonly
                          />
                        </el-form-item>
                      </el-col>
                    </el-row>
                  </div>
                </el-row>
              </el-card>
            </el-row>
            <el-row>
              <el-card class="box-card user-bio" style="margin-top: 2%">
                <div
                  slot="header"
                  class="clearfix"
                >
                  <span>Reservation Information</span>
                </div>
                <el-row>
                  <div class="postInfo-container">
                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="Department"
                        class="postInfo-container-item"
                        prop="department"
                      >
                        <el-input
                          v-model="student_reservation.department.name"
                          readonly
                        />
                      </el-form-item>
                    </el-col>
                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="Grade"
                        class="postInfo-container-item"
                        prop="grade"
                      >
                        <el-input
                          v-model="student_reservation.grade.name"
                          readonly
                        />
                      </el-form-item>
                    </el-col>
                    <el-col :span="8">
                      <el-form-item
                        label-width="120px"
                        label="Section"
                        class="postInfo-container-item"
                        prop="section"
                      >
                        <el-input
                          v-model="student_reservation.section.name"
                          readonly
                        />
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
                          v-model="student_reservation.reservation_fee"
                          class="filter-item"
                          readonly
                        />
                      </el-form-item>
                    </el-col>
                    <el-col :span="8">
                      <el-form-item
                        label-width="150px"
                        label="Reservation Date"
                        class="postInfo-container-item"
                        prop="section"
                      >
                        <el-input
                          v-model="student_reservation.date_reserve"
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
                          v-model="student_reservation.remarks"
                          type="textarea"
                          name="remarks"
                          maxlength="500"
                          minlength="5"
                          placeholder="Remarks"
                        />
                      </el-form-item>
                    </el-col>
                  </div>
                </el-row>
              </el-card>
            </el-row>
          </el-col>
          <el-col v-if="hasReservation === true" style="margin-top: 2%;">
            <el-row>
              <el-card class="box-card user-bio">
                <div slot="header" class="clearfix">
                  <span>Enrollment Information for School Year {{ school_year.name }} </span>
                </div>
                <div class="postInfo-container">
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Department"
                      class="postInfo-container-item"
                      prop="department"
                    >
                      <el-input
                        v-model="student_reservation.department.name"
                        readonly
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Grade"
                      class="postInfo-container-item"
                      prop="grade"
                    >
                      <el-input
                        v-model="student_reservation.grade.name"
                        readonly
                      />
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
                        v-model="enrollment.section"
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
                  <el-col :span="24">
                    <el-form-item
                      label-width="120px"
                      label="Remarks"
                      prop="remarks"
                    >
                      <el-input
                        v-model="enrollment.remarks"
                        type="textarea"
                        name="remarks"
                        maxlength="500"
                        minlength="5"
                        placeholder="Remarks"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Discount"
                      class="postInfo-container-item"
                      prop="section"
                    >
                      <el-select
                        v-model="enrollment.discount"
                        class="filter-item"
                        placeholder="Please Select Section"
                        @change="setStudentTuition"
                      >
                        <el-option
                          v-for="item in discounts"
                          :key="item"
                          :label="item | uppercaseFirst"
                          :value="item"
                        />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col v-if="enrollment.discount!=='NONE' && enrollment.discount!==''" :span="8">
                    <el-form-item
                      label-width="120px"
                      label="Percentage"
                      class="postInfo-container-item"
                      prop="section"
                    >
                      <el-input
                        v-model="enrollment.percent"
                        type="number"
                        name="percent"
                        :min="0"
                        :max="100"
                        maxlength="3"
                        placeholder="discount percent"
                        @change="setStudentTuition"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item
                      label-width="150px"
                      label="Payment Mode"
                      class="postInfo-container-item"
                      prop="section"
                    >
                      <el-select
                        v-model="enrollment.payment_mode"
                        class="filter-item"
                        placeholder="Please Select Section"
                        @change="setStudentTuition"
                      >
                        <el-option
                          v-for="item in payment_modes"
                          :key="item"
                          :label="item | uppercaseFirst"
                          :value="item"
                        />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col v-if="enrollment.tuition && enrollment.payment_mode !== ''" :span="24">
                    <el-table :data="enrollment.tuition" style="width: 100%">
                      <el-table-column
                        prop="type"
                        label="Payment Mode"
                        width="150"
                      />
                      <el-table-column label="Less">
                        <el-table-column
                          prop="reservation_fee"
                          label="Reservation"
                          width="100"
                        />
                        <el-table-column
                          prop="discount"
                          label="Discount"
                          width="100"
                        />
                      </el-table-column>
                      <el-table-column label="Tuition Info">
                        <el-table-column
                          prop="total_tuition_fee"
                          label="Tuition Fee"
                          width="100"
                        />
                        <el-table-column
                          prop="total_misc_fee"
                          label="Misc Fee"
                          width="100"
                        />
                        <el-table-column label="Enrollment">
                          <el-table-column
                            prop="enrollment_tuition_fee"
                            label="Tuition Fee"
                            width="120"
                          />
                          <el-table-column
                            prop="enrollment_misc_fee"
                            label="Misc Fee"
                            width="120"
                          />
                        </el-table-column>
                        <el-table-column label="Installment">
                          <el-table-column
                            prop="monthly_tuition_fee"
                            label="Monthly TF"
                            width="120"
                          />
                          <el-table-column
                            prop="monthly_misc_fee"
                            label="Monthly Misc"
                            width="120"
                          />
                          <el-table-column
                            prop="months"
                            label="Payments"
                            width="120"
                          />
                        </el-table-column>
                      </el-table-column>
                      <el-table-column
                        prop="total"
                        label="Total"
                        width="150"
                      />
                    </el-table>
                  </el-col>
                  <el-col v-if="enrollment.payment_schedule && enrollment.payment_mode !== ''" :span="24">
                    <el-table :data="enrollment.payment_schedule" style="width: 100%; margin-top:5%;">
                      <el-table-column label="Payment Details">
                        <el-table-column
                          prop="payment_no"
                          label="Payment No."
                        />
                        <el-table-column
                          prop="tuition_fee"
                          label="Tuition Fee"
                        />
                        <el-table-column
                          prop="misc_fee"
                          label="Misc Fee"
                        />
                        <el-table-column
                          prop="total"
                          label="Total"
                        />
                        <el-table-column
                          prop="due_date"
                          label="Due Date"
                        />
                        <el-table-column
                          prop="remarks"
                          label="Remarks"
                        />
                      </el-table-column>
                    </el-table>
                  </el-col>
                </div>
              </el-card>
            </el-row>
          </el-col>
        </el-row>
      </div>
    </el-form>
    <!-- DIALOG -->
    <el-dialog
      v-if="student.first_name"
      v-permission="['manage enrollment']"
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

const enrollmentResource = new Resource('enrollments');
const reservationResource = new Resource('reservations');
const studentResource = new Resource('students');
const departmentResource = new Resource('departments');
const gradeResource = new Resource('grades');
const sectionResource = new Resource('sections');
const schoolYearResource = new Resource('schoolyears');

const defaultForm = {
  enrollment: {
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
    discount: '',
    remarks: '',
    payment_mode: '',
    percent: 0,
    school_year_id: '',
    reservation_id: '',
    tuition: [],
    payment_schedule: [],
    fee: '1000',
    student_type: 'Old Student',
    type: '',
    with_reservation: 'Yes',
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
      formTitle: 'Confirm Enrollment Details',
      hasStudentNoError: false,
      enrollment: Object.assign({}, defaultForm.enrollment),
      student: [{ student_no: '' }],
      student_reservation: [{ id: '' }],
      reservation_code: '',
      discounts: ['NONE', 'SIBLING', 'SCHOLAR'],
      payment_modes: ['ANNUAL', 'SEMESTRAL', 'QUARTERLY', 'MONTHLY'],
      fees: [],
      annual: [],
      semestral: [],
      quarterly: [],
      monthly: [],
      school_year: [],
      departments: [],
      grades: [],
      sections: [],
      filtered_departments: [],
      filtered_grades: [],
      filtered_section: [],
      hasReservation: false,
      loading: false,
      changeStudNo: false,
      reservationConfirmDialog: false,
      reservationPaymentDialog: false,
      submitted: false,
      created: false,
      hasReservationDialog: true,
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
    this.getSchoolYear().then(this.askReservation.bind(null, 'text'));
    this.checkIfEdit();
    this.getDepartments();
    this.getGrades();
    this.getSections();
  },
  methods: {
    formatData(value){
      var fee = parseFloat(Math.ceil(value)).toFixed(2);
      return fee;
    },
    formatDate(date) {
      var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
      if (month.length < 2) {
        month = '0' + month;
      }
      if (day.length < 2) {
        day = '0' + day;
      }
      return [year, month, day].join('-');
    },
    setStudentTuition(){
      var discount = this.enrollment.percent;
      if (discount > 100) {
        this.enrollment.percent = 100;
        discount = 100;
      } else if (discount < 0) {
        this.enrollment.percent = 0;
        discount = 0;
      }
      var d = new Date();
      var less_discount = 0;
      var less = 0;
      var less_reservation_fee = 0;
      var less_monthly = 0;
      var less_enrollment = 0;
      if (discount === '' || this.enrollment.discount === 'NONE') {
        discount = 0;
      }
      this.enrollment.tuition = [];
      this.enrollment.payment_schedule = [];
      if (this.hasReservation === true){
        if (this.enrollment.student_type === 'Old Student'){
          less_reservation_fee = 1000;
        } else {
          less_reservation_fee = 2000;
        }
      }
      if (this.hasReservation === true) {
        this.enrollment.payment_schedule.push({
          payment_no: 'Reservation Fee',
          tuition_fee: this.formatData(this.student_reservation.reservation_fee),
          misc_fee: 0.00,
          total: this.formatData(this.student_reservation.reservation_fee),
          due_date: this.formatDate(this.enrollment.reservation_date),
          remarks: 'PAID',
        });
      }
      if (this.enrollment.payment_mode === 'ANNUAL'){
        if (discount > 0) {
          less_discount = this.formatData(this.annual[0].total_tuition_fee * (discount / 100));
          less = this.formatData(this.annual[0].total_tuition_fee * (discount / 100) + less_reservation_fee);
          less_enrollment = this.formatData(this.annual[0].enrollment_tuition_fee * (discount / 100) + less_reservation_fee);
          if (less > this.annual[0].total_tuition_fee) {
            less = this.formatData(this.annual[0].total_tuition_fee);
            less_enrollment = this.formatData(this.annual[0].enrollment_tuition_fee);
          }
        } else {
          less = this.formatData(less_reservation_fee);
          less_enrollment = this.formatData(less_reservation_fee);
        }
        this.enrollment.tuition.push({
          type: 'ANNUAL',
          months: '1',
          reservation_fee: this.formatData(less_reservation_fee),
          discount: this.formatData(less_discount),
          total: this.formatData(this.annual[0].total_tuition_fee - less + this.annual[0].total_misc_fee),
          total_tuition_fee: this.formatData(this.annual[0].total_tuition_fee - less),
          total_misc_fee: this.formatData(this.annual[0].total_misc_fee),
          monthly_tuition_fee: this.formatData(this.annual[0].monthly_tuition_fee - less_monthly),
          monthly_misc_fee: this.formatData(this.annual[0].monthly_misc_fee),
          enrollment_tuition_fee: this.formatData(this.annual[0].enrollment_tuition_fee - less_enrollment),
          enrollment_misc_fee: this.formatData(this.annual[0].enrollment_misc_fee),
        });
        this.enrollment.payment_schedule.push({
          payment_no: 1,
          tuition_fee: this.formatData(this.annual[0].enrollment_tuition_fee - less_enrollment),
          misc_fee: this.formatData(this.annual[0].enrollment_misc_fee),
          total: this.formatData(this.annual[0].enrollment_tuition_fee - less_enrollment + this.annual[0].enrollment_misc_fee),
          due_date: this.formatDate(d.toString()),
          remarks: 'Enrollment Payment',
        });
      } else if (this.enrollment.payment_mode === 'SEMESTRAL'){
        if (discount > 0) {
          less_discount = this.formatData(this.semestral[0].total_tuition_fee * (discount / 100));
          less = this.formatData(this.semestral[0].total_tuition_fee * (discount / 100) + less_reservation_fee);
          less_monthly = this.formatData(this.semestral[0].monthly_tuition_fee * (discount / 100) + (less_reservation_fee / 2));
          less_enrollment = this.formatData(this.semestral[0].enrollment_tuition_fee * (discount / 100) + (less_reservation_fee / 2));
          if (less > this.semestral[0].total_tuition_fee) {
            less = this.formatData(this.semestral[0].total_tuition_fee);
            less_enrollment = this.formatData(this.semestral[0].enrollment_tuition_fee);
          }
        } else {
          less = this.formatData(less_reservation_fee);
          less_monthly = this.formatData(less_reservation_fee / 2);
          less_enrollment = this.formatData(less_reservation_fee / 2);
        }
        this.enrollment.tuition.push({
          type: 'SEMESTRAL',
          months: '2',
          reservation_fee: less_reservation_fee,
          discount: less_discount,
          total: this.formatData(this.semestral[0].total_tuition_fee - less + this.semestral[0].total_misc_fee),
          total_tuition_fee: this.formatData(this.semestral[0].total_tuition_fee - less),
          total_misc_fee: this.formatData(this.semestral[0].total_misc_fee),
          monthly_tuition_fee: this.formatData(this.semestral[0].monthly_tuition_fee - less_monthly),
          monthly_misc_fee: this.formatData(this.semestral[0].monthly_misc_fee),
          enrollment_tuition_fee: this.formatData(this.semestral[0].enrollment_tuition_fee - less_enrollment),
          enrollment_misc_fee: this.formatData(this.semestral[0].enrollment_misc_fee),
        });
        this.enrollment.payment_schedule.push({
          payment_no: 1,
          tuition_fee: this.formatData(this.semestral[0].enrollment_tuition_fee - less_enrollment),
          misc_fee: this.formatData(this.semestral[0].enrollment_misc_fee),
          due_date: this.formatDate(d.toString()),
          total: this.formatData(this.semestral[0].enrollment_tuition_fee - less_enrollment + this.semestral[0].enrollment_misc_fee),
          remarks: 'Enrollment Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 2,
          tuition_fee: this.formatData(this.semestral[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.semestral[0].monthly_misc_fee),
          due_date: this.formatDate(d.getFullYear() + '-12-15'),
          total: this.formatData(this.semestral[0].monthly_tuition_fee - less_monthly + this.semestral[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
      } else if (this.enrollment.payment_mode === 'QUARTERLY'){
        if (discount > 0) {
          less_discount = this.formatData(this.quarterly[0].total_tuition_fee * (discount / 100));
          less = this.formatData(this.quarterly[0].total_tuition_fee * (discount / 100) + less_reservation_fee);
          less_monthly = this.formatData(this.quarterly[0].monthly_tuition_fee * (discount / 100) + (less_reservation_fee / 4));
          less_enrollment = this.formatData(this.quarterly[0].enrollment_tuition_fee * (discount / 100) + (less_reservation_fee / 4));
          if (less > this.quarterly[0].total_tuition_fee) {
            less = this.formatData(this.quarterly[0].total_tuition_fee);
            less_enrollment = this.formatData(this.quarterly[0].enrollment_tuition_fee);
          }
        } else {
          less = this.formatData(less_reservation_fee);
          less_enrollment = this.formatData((less_reservation_fee / 4));
          less_monthly = this.formatData((less_reservation_fee / 4));
        }
        this.enrollment.tuition.push({
          type: 'QUARTERLY',
          months: '4',
          reservation_fee: less_reservation_fee,
          discount: less_discount,
          total: this.formatData(this.quarterly[0].total_tuition_fee - less + this.quarterly[0].total_misc_fee),
          total_tuition_fee: this.formatData(this.quarterly[0].total_tuition_fee - less),
          total_misc_fee: this.formatData(this.quarterly[0].total_misc_fee),
          monthly_tuition_fee: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly),
          monthly_misc_fee: this.formatData(this.quarterly[0].monthly_misc_fee),
          enrollment_tuition_fee: this.formatData(this.quarterly[0].enrollment_tuition_fee - less_enrollment),
          enrollment_misc_fee: this.formatData(this.quarterly[0].enrollment_misc_fee),
        });
        this.enrollment.payment_schedule.push({
          payment_no: 1,
          tuition_fee: this.formatData(this.quarterly[0].enrollment_tuition_fee - less_enrollment),
          misc_fee: this.formatData(this.quarterly[0].enrollment_misc_fee),
          due_date: this.formatDate(d.toString()),
          total: this.formatData(this.quarterly[0].enrollment_tuition_fee - less_enrollment + this.quarterly[0].enrollment_misc_fee),
          remarks: 'Enrollment Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 2,
          tuition_fee: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.quarterly[0].monthly_misc_fee),
          due_date: this.formatDate(d.getFullYear() + '-10-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 3,
          tuition_fee: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.quarterly[0].monthly_misc_fee),
          due_date: this.formatDate(d.getFullYear() + '-12-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 4,
          tuition_fee: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.quarterly[0].monthly_misc_fee),
          due_date: this.formatDate(parseInt(d.getFullYear() + 1) + '-02-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
      } else if (this.enrollment.payment_mode === 'MONTHLY'){
        if (discount > 0) {
          less_discount = this.formatData(this.semestral[0].total_tuition_fee * (discount / 100));
          less = this.formatData(this.monthly[0].total_tuition_fee * (discount / 100) + less_reservation_fee);
          less_monthly = this.formatData(this.monthly[0].monthly_tuition_fee * (discount / 100) + (less_reservation_fee / 9));
          less_enrollment = this.formatData(this.monthly[0].enrollment_tuition_fee * (discount / 100) + (less_reservation_fee / 9));
          if (less > this.monthly[0].total_tuition_fee) {
            less = this.formatData(this.monthly[0].total_tuition_fee);
            less_enrollment = this.formatData(this.monthly[0].enrollment_tuition_fee);
          }
        } else {
          less = this.formatData(less_reservation_fee);
          less_monthly = this.formatData((less_reservation_fee / 9));
          less_enrollment = this.formatData((less_reservation_fee / 9));
        }
        this.enrollment.tuition.push({
          type: 'MONTHLY',
          months: '9',
          reservation_fee: less_reservation_fee,
          discount: less_discount,
          total: this.formatData(this.monthly[0].total_tuition_fee - less + this.monthly[0].total_misc_fee),
          total_tuition_fee: this.formatData(this.monthly[0].total_tuition_fee - less),
          total_misc_fee: this.formatData(this.monthly[0].total_misc_fee),
          monthly_tuition_fee: this.formatData(this.monthly[0].monthly_tuition_fee - less_monthly),
          monthly_misc_fee: this.formatData(this.monthly[0].monthly_misc_fee),
          enrollment_tuition_fee: this.formatData(this.monthly[0].enrollment_tuition_fee - less_enrollment),
          enrollment_misc_fee: this.formatData(this.monthly[0].enrollment_misc_fee),
        });
        this.enrollment.payment_schedule.push({
          payment_no: 1,
          tuition_fee: this.formatData(this.monthly[0].enrollment_tuition_fee - less_enrollment),
          misc_fee: this.formatData(this.monthly[0].enrollment_misc_fee),
          due_date: this.formatDate(d.toString()),
          total: this.formatData(this.quarterly[0].enrollment_tuition_fee - less_enrollment + this.quarterly[0].enrollment_misc_fee),
          remarks: 'Enrollment Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 2,
          tuition_fee: this.formatData(this.monthly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.monthly[0].monthly_misc_fee),
          due_date: this.formatDate(d.getFullYear() + '-09-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 3,
          tuition_fee: this.formatData(this.monthly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.monthly[0].monthly_misc_fee),
          due_date: this.formatDate(d.getFullYear() + '-10-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 4,
          tuition_fee: this.formatData(this.monthly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.monthly[0].monthly_misc_fee),
          due_date: this.formatDate(d.getFullYear() + '-11-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 5,
          tuition_fee: this.formatData(this.monthly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.monthly[0].monthly_misc_fee),
          due_date: this.formatDate(d.getFullYear() + '-12-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 6,
          tuition_fee: this.formatData(this.monthly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.monthly[0].monthly_misc_fee),
          due_date: this.formatDate(parseInt(d.getFullYear() + 1) + '-01-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 7,
          tuition_fee: this.formatData(this.monthly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.monthly[0].monthly_misc_fee),
          due_date: this.formatDate(parseInt(d.getFullYear() + 1) + '-02-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 8,
          tuition_fee: this.formatData(this.monthly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.monthly[0].monthly_misc_fee),
          due_date: this.formatDate(parseInt(d.getFullYear() + 1) + '-03-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
        this.enrollment.payment_schedule.push({
          payment_no: 9,
          tuition_fee: this.formatData(this.monthly[0].monthly_tuition_fee - less_monthly),
          misc_fee: this.formatData(this.monthly[0].monthly_misc_fee),
          due_date: this.formatDate(parseInt(d.getFullYear() + 1) + '-04-15'),
          total: this.formatData(this.quarterly[0].monthly_tuition_fee - less_monthly + this.quarterly[0].monthly_misc_fee),
          remarks: 'Monthly Payment',
        });
      }
    },
    async askReservation(data, text) {
      if (this.enrollment.school_year_id === '' || this.enrollment.school_year_id === undefined){
        return false;
      } else {
        this.$confirm('Do you have a reservation for ' + this.school_year.name + '?', 'Reservation', {
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
          type: 'info',
          center: true,
        }).then(() => {
          this.$prompt('Please Enter Reservation Code', 'Search Reservation', {
            confirmButtonText: 'OK',
            cancelButtonText: 'No, I don\'t have a reservation',
          }).then(({ value }) => {
            this.reservation_code = value;
            this.searchReservation().then(this.getTuition.bind(null, 'text'));
          }).catch(() => {
            // do nothing
          });
        }).catch(() => {
          this.$message({
            type: 'info',
            message: 'No Reservation',
          });
        });
      }
    },
    async searchReservation(){
      const { data } = await reservationResource.list({
        reservation_code: this.reservation_code,
      });
      if (data.length > 0){
        this.student_reservation = data[0];
        var temp_reservation = false;
        temp_reservation = true;
        this.hasReservation = true;
        console.log(this.student_reservation);
        this.enrollment = {
          reservation_id: this.student_reservation.id,
          student_id: this.student_reservation.student.id,
          student_no: this.student_reservation.student.student_no,
          first_name: this.student_reservation.student.first_name,
          middle_name: this.student_reservation.student.middle_name,
          last_name: this.student_reservation.student.last_name,
          birthdate: this.student_reservation.student.birthdate,
          mobile: this.student_reservation.student.mobile,
          department: this.student_reservation.department_id,
          grade: this.student_reservation.grade_id,
          section: this.student_reservation.section_id,
          reservation_date: this.student_reservation.date_reserve,
          discount: 'NONE',
          percent: 0,
          fee: '',
          payment_mode: '',
          remarks: '',
          tuition: [],
          payment_schedule: [],
          school_year_id: this.student_reservation.school_year_id,
          student_type: this.student_reservation.type === 'OLD' ? 'Old Student' : 'New Student',
          type: this.student_reservation.type,
          with_reservation: temp_reservation,
        };
        this.filterSection();
      } else {
        this.$message({
          message: 'Reservation ' + this.reservation_code + ' not found.',
          type: 'error',
        });
        this.hasReservation = false;
      }
    },
    async getTuition(){
      if (this.hasReservation === true) {
        const { data } = await schoolYearResource.list({
          enrollment_fees: true,
          school_year_id: this.enrollment.school_year_id,
          department: this.enrollment.department,
        });
        if (data.length > 0){
          this.annual.push({
            type: 'ANNUAL',
            months: '1',
            total: data[0].annual[0].total_tuition_fee + data[0].annual[0].total_misc_fee,
            total_tuition_fee: data[0].annual[0].total_tuition_fee,
            total_misc_fee: data[0].annual[0].total_misc_fee,
            monthly_tuition_fee: data[0].annual[0].monthly_tuition_fee,
            monthly_misc_fee: data[0].annual[0].monthly_misc_fee,
            enrollment_tuition_fee: data[0].annual[0].enrollment_tuition_fee,
            enrollment_misc_fee: data[0].annual[0].enrollment_misc_fee,
          });
          this.semestral.push({
            type: 'SEMESTRAL',
            months: '2',
            total: data[0].semestral[0].total_tuition_fee + data[0].semestral[0].total_misc_fee,
            total_tuition_fee: data[0].semestral[0].total_tuition_fee,
            total_misc_fee: data[0].semestral[0].total_misc_fee,
            monthly_tuition_fee: data[0].semestral[0].monthly_tuition_fee,
            monthly_misc_fee: data[0].semestral[0].monthly_misc_fee,
            enrollment_tuition_fee: data[0].semestral[0].enrollment_tuition_fee,
            enrollment_misc_fee: data[0].semestral[0].enrollment_misc_fee,
          });
          this.quarterly.push({
            type: 'QUARTERLY',
            months: '4',
            total: data[0].quarterly[0].total_tuition_fee + data[0].quarterly[0].total_misc_fee,
            total_tuition_fee: data[0].quarterly[0].total_tuition_fee,
            total_misc_fee: data[0].quarterly[0].total_misc_fee,
            monthly_tuition_fee: data[0].quarterly[0].monthly_tuition_fee,
            monthly_misc_fee: data[0].quarterly[0].monthly_misc_fee,
            enrollment_tuition_fee: data[0].quarterly[0].enrollment_tuition_fee,
            enrollment_misc_fee: data[0].quarterly[0].enrollment_misc_fee,
          });
          this.monthly.push({
            type: 'MONTHLY',
            months: '9',
            total: data[0].monthly[0].total_tuition_fee + data[0].monthly[0].total_misc_fee,
            total_tuition_fee: data[0].monthly[0].total_tuition_fee,
            total_misc_fee: data[0].monthly[0].total_misc_fee,
            monthly_tuition_fee: data[0].monthly[0].monthly_tuition_fee,
            monthly_misc_fee: data[0].monthly[0].monthly_misc_fee,
            enrollment_tuition_fee: data[0].monthly[0].enrollment_tuition_fee,
            enrollment_misc_fee: data[0].monthly[0].enrollment_misc_fee,
          });
        } else {
          this.$message({
            message: 'Tuition not found.',
            type: 'error',
          });
        }
      }
    },
    enterReservation(){

    },
    async searchStudent() {
      const { data } = await studentResource.list({
        student_no: this.enrollment.student_no,
        student_id: this.enrollment.student_id,
      });
      this.student = Object.assign({}, data[0]);
      if (data.length === 0) {
        this.$message({
          message: 'Student details does not exist.',
          type: 'error',
        });
        var temp_type = this.enrollment.student_type;
        var temp_reservation = this.enrollment.with_reservation;
        var temp_stud_no = this.enrollment.student_no;
        this.$refs['postForm'].resetFields();
        this.enrollment = {
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
          with_reservation: temp_reservation,
        };
      } else {
        this.reservationConfirmDialog = true;
      }
    },
    confirmReservation(){
      this.reservationConfirmDialog = false;
      this.enrollment.student_id = this.student.id;
      this.enrollment.first_name = this.student.first_name;
      this.enrollment.middle_name = this.student.middle_name;
      this.enrollment.last_name = this.student.last_name;
      this.enrollment.birthdate = this.student.birthdate;
      this.enrollment.mobile = this.student.mobile;
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
        this.$alert('No Active School Year. Please Configure First.', 'Enrollment Error', {
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
        this.enrollment.school_year_id = this.school_year.id;
      }
    },
    async getDepartments() {
      const { data } = await departmentResource.list({});
      this.departments = data;
      var temp_dept = [];
      if (this.enrollment.school_year_id !== '') {
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
      }
    },
    async filterGrade() {
      this.enrollment.grade = '';
      this.enrollment.section = '';
      this.getGrades();
    },
    async filterSection() {
      this.enrollment.section = this.student_reservation.section_id;
      this.getSections();
    },
    async getGrades() {
      const { data } = await gradeResource.list({ dept_id: this.enrollment.department });
      this.grades = data;
    },
    async getSections() {
      const { data } = await sectionResource.list({ grade_id: this.enrollment.grade });
      this.sections = data;
    },
    /* old methods */
    async checkDuplicateEnrollment() {
      const { data } = await enrollmentResource.list({
        student_no: this.enrollment.student_no, student_id: this.enrollment.student_id, school_year_id: this.enrollment.school_year_id,
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
          message: 'Student Enrollment already exists.',
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
                'Do you want to create ' + this.enrollment.first_name + "'s record?",
                'Confirm Student Enrollment Details',
                {
                  confirmButtonText: 'OK',
                  cancelButtonText: 'Cancel',
                  center: true,
                }
              )
                .then(() => {
                  enrollmentResource
                    .store(this.enrollment)
                    .then((response) => {
                      this.$message({
                        message:
                          'New Student ' +
                          this.enrollment.first_name +
                          ' ' +
                          this.enrollment.last_name +
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
                    message: 'Enrollment cancelled',
                  });
                });
            } else {
              this.$confirm(
                'Do you want to update ' + this.enrollment.first_name + "'s record?",
                'Confirm Save Student Details',
                {
                  confirmButtonText: 'OK',
                  cancelButtonText: 'Cancel',
                  center: true,
                }
              )
                .then(() => {
                  enrollmentResource
                    .update(this.enrollment.id, this.enrollment)
                    .then((response) => {
                      this.$message({
                        message:
                          'Student ' +
                          this.enrollment.first_name +
                          ' ' +
                          this.enrollment.last_name +
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
                    message: 'Update canceled',
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
        this.enrollment.student_no === '' &&
        this.enrollment.student_type === 'Old Student' &&
        this.hasReservation === false
      ) {
        this.$message({
          message: 'Student number is required.',
          type: 'error',
        });
        return;
      } else {
        this.checkDuplicateEnrollment().then(this.doWork.bind(null, 'text'));
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
      if ((this.enrollment.student_no !== '') || (this.enrollment.first_name !== '') || (this.enrollment.middle_name !== '') || (this.enrollment.last_name !== '') || (this.enrollment.birthdate !== '') || (this.enrollment.mobile !== '')) {
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
              var temp_type = this.enrollment.student_type;
              this.enrollment = {
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
              this.enrollment.student_no = this.student.student_no;
            } else {
              if (this.enrollment.student_type === 'Old Student') {
                this.enrollment.student_type = 'New Student';
              } else {
                this.enrollment.student_type = 'Old Student';
              }
            }
            console.error(err);
          });
      }
      if (this.enrollment.student_type === 'New Student') {
        this.enrollment.fee = '2000';
      } else if (this.enrollment.student_type === 'Old Student') {
        this.enrollment.fee = '1000';
      } else {
        this.enrollment.fee = '0';
      }
    },
    // cancel checker
    changeStudentNo() {
      if (this.enrollment.student_no !== this.student.student_no && this.student.student_no !== undefined) {
        this.changeStudNo = true;
        this.confirmChange();
      }
    },
    /* load edit data to form data */
    checkIfEdit() {
      if (this.isEdit === true) {
        this.enrollment.id = this.editReservation.id;
        if (this.enrollment.student === null) {
          this.enrollment.student_no = this.editReservation.student.student_no;
        } else {
          this.enrollment.student_no = '';
          this.student.first_name = this.editReservation.first_name;
          this.student.middle_name = this.editReservation.middle_name;
          this.student.last_name = this.editReservation.last_name;
          this.student.mobile = this.editReservation.mobile;
          this.student.birthdate = this.editReservation.birthdate;
        }
        this.enrollment.student_id = this.editReservation.student_id;
        this.enrollment.first_name = this.editReservation.first_name;
        this.enrollment.middle_name = this.editReservation.middle_name;
        this.enrollment.last_name = this.editReservation.last_name;
        this.enrollment.mobile = this.editReservation.mobile;
        this.enrollment.birthdate = this.editReservation.birthdate;
        this.enrollment.department = this.editReservation.department_id;
        this.enrollment.grade = this.editReservation.grade_id;
        this.enrollment.section = this.editReservation.section_id;
        this.enrollment.remarks = this.editReservation.remarks;
        if (this.editReservation.type === 'OLD'){
          this.enrollment.student_type = 'Old Student';
        } else {
          this.enrollment.student_type = 'New Student';
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
