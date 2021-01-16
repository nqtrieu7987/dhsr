<template>
<div class="form-row">
	<div class="col-md-1">
        <i style="font-size: 24px" class="fa fa-trash-o fa-fw" @click="removeItem" aria-hidden="true"></i>
        <i style="font-size: 24px" class="fa fa-fw fa-save" @click="saveAndClose" aria-hidden="true"></i>
    </div>
    <div class="col-md-3">
        <v-select :class="isWarningHotel ? 'require-select' : ''" v-model="hotel" label="name" :options="hotels" 
            @input="hotelSelected" placeholder="Select hotel">
            <span class="txt-color-red" slot="no-options">No data</span>
        </v-select>
    </div>
    <div class="col-md-2">
        <v-select :class="isWarningType ? 'require-select' : ''" v-model="type" label="name" :options="types" 
            @input="typeSelected" placeholder="Select job">
            <span class="txt-color-red" slot="no-options">No data</span>
        </v-select>
    </div>
    <div class="col-md-2">
        <v-select :class="isWarningView ? 'require-select' : ''" v-model="view" :options="typeArr" label="text" 
            @input="viewSelected" placeholder="View type">
            <span class="txt-color-red" slot="no-options">No data</span>
        </v-select>
    </div>
    <div class="col-md-1">
        <input type="number" v-model="slot" value="1" min="1" style="width: 100%">
    </div>
    <div class="col-md-1">
        <date-picker :class="isWarningStartTime ? 'require-select' : ''" @input="startTimeSelected"
            v-model="start_time"
            :minute-step="5"
            format="HH:mm"
            value-type="format"
            type="time"
            placeholder="Start time" style="width:102%; margin-right: 10px">
        </date-picker>
    </div>
    <div class="col-md-1">
        <date-picker :class="isWarningEndTime ? 'require-select' : ''" @input="endTimeSelected"
            v-model="end_time"
            :minute-step="5"
            format="HH:mm"
            value-type="format"
            type="time"
            placeholder="End time" style="width:100%; margin-right: 10px">
        </date-picker>
    </div>
    <div class="col-md-1">
        <date-picker :class="isWarningStartDate ? 'require-select' : ''" @input="startDateSelected"
        v-model="start_date" style="width:120px" valueType="format" placeholder="Start date"></date-picker>
    </div>
</div>
</template>

<script>
import "vue-select/src/scss/vue-select.scss";
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import Helper from '../helper/helper';
export default {
    components: { DatePicker },
    name: "JobItem",
    mixins: [Helper],
    props: {
        pkey: '',
        hotels: Array,
        types: Array,
        view_type: Array,
        showErrorItem: Boolean,
    },
  data: () => ({
    start_time: '',
    end_time: '',
    start_date: null,
    hotel: '',
    type: '',
    view: '',
    typeArr: [],
    slot: 1,
    isWarningHotel: false,
    isWarningType: false,
    isWarningView: false,
    isWarningStartTime: false,
    isWarningEndTime: false,
    isWarningStartDate: false,
  }),
  mounted() {
    this.addViewType();
  },
  methods: {
    moment: function () {
        return moment();
    },
    changeTime() {
        if (this.job.time.end) {
            if (this.job.time.start > this.job.time.end) {
                this.job.time.end = '';
            }
        }
    },
    hotelSelected(value) {
        if(value) {
            this.isWarningHotel = false;
        } else {
            this.isWarningHotel = true;
            return false;
        }
    },
    typeSelected(value) {
        if(value) {
            this.isWarningType = false;
        } else {
            this.isWarningType = true;
            return false;
        }
    },
    viewSelected(value) {
        if(value) {
            this.isWarningView = false;
        } else {
            this.isWarningView = true;
            return false;
        }
    },
    startTimeSelected(value) {
        if(value) {
            this.isWarningStartTime = false;
        } else {
            this.isWarningStartTime = true;
            return false;
        }
    },
    endTimeSelected(value) {
        if(value) {
            this.isWarningEndTime = false;
        } else {
            this.isWarningEndTime = true;
            return false;
        }
    },
    startDateSelected(value) {
        if(value) {
            this.isWarningStartDate = false;
        } else {
            this.isWarningStartDate = true;
            return false;
        }
    },
    addViewType() {
        var userTemp = [];
        for (const [key, name] of Object.entries(this.view_type)) {
            userTemp.push({
                value: key,
                text: name
            });
        }
        this.typeArr = userTemp;
    },
    addExperience () {
      this.workExperiences.push({
        company: '',
        title: ''
      })
    },

    submit () {
      const data = {
        workExperiences: this.workExperiences
      }
      alert(JSON.stringify(data, null, 2))
    },
    saveAndClose(){
        if(this.hotel == null || this.hotel ==''){
            this.isWarningHotel = true;
            return false;
        }
        if(this.type == null || this.type ==''){
            this.isWarningType = true;
            return false;
        }
        if(this.view == null || this.view ==''){
            this.isWarningView = true;
            return false;
        }
        if(this.start_time == null || this.start_time ==''){
            this.isWarningStartTime = true;
            return false;
        }
        if(this.end_time == null || this.end_time ==''){
            this.isWarningEndTime = true;
            return false;
        }
        if(this.start_date == null || this.start_date ==''){
            this.isWarningStartDate = true;
            return false;
        }

        axios.post('/job-create-multi', {hotel: this.hotel, type: this.type, view: this.view, slot: this.slot, start_time: this.start_time, end_time: this.end_time, start_date: this.start_date}).then(res => {
            this.$root.$emit('list-all-alert', {msg: 'Create successfully'});
        }).catch(error => {
        });
    },
    removeItem(){
        this.$root.$emit('removeItem', this.pkey);
    }
  }
};
</script>
<style scoped>
.mx-datepicker {
    width: 120px !important;
}

<style>