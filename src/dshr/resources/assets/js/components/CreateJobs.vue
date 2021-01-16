<template>
  <div id="app" class="container">
    <form>
        <h3>Create Multiple Jobs
        <button @click="addExperience" type="button" class="btn btn-primary float-right"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add</button>
        </h3>
        <div class="work-experiences">
            <div v-for="(experience, index) in workExperiences" :pkey="index">
                <job-item :hotels="hotels" :types="types" :view_type="view_type"></job-item>
            </div>
        </div>
        <hr>
        <!-- <div class="form-group">
            <button @click="submit" type="button" class="btn btn-primary">Submit</button>
        </div> -->
    </form>
  </div>
</template>

<script>
export default {
    name: "App",
    props: {
        hotels: Array,
        types: Array,
        view_type: Array,
        showErrorItem: Boolean,
    },
    //props: ['hotels', 'types','showErrorItem','view_type'],
  data: () => ({
    hotel: '',
    position: '',
    workExperiences: [
      {
        title: "Job 1"
      },
      {
        title: "Job 2"
      },
      {
        title: "Job 3"
      },
      {
        title: "Job 4"
      },
      {
        title: "Job 5"
      }
    ]
  }),
  mounted() {
    this.$root.$on('removeItem', (index) => {
        this.workExperiences.splice(index, 1);
    });
  },
  methods: {
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
    }
  }
};
</script>

<style>
.work-experiences > div {
  margin: 20px 0;
  padding-bottom: 10px;
}
.work-experiences > div:not(:last-child) {
  border-bottom: 1px solid rgb(206, 212, 218);
}
</style>