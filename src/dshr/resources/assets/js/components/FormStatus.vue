<template>
<div v-if="status" class="alert alert-success alert-dismissable fade show" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4><i class="icon fa fa-check fa-fw" aria-hidden="true"></i> Success</h4>
    {{ status }}
</div>
</template>

<script>
export default {
  name: "FormStatus",
  data: () => ({
    status: '',
  }),
  mounted() {
    this.$root.$on('list-all-alert', (data) => {
        //this.status = data.msg;
        $('#show-mess').html('<p class="alert alert-success">Create successfully.</p>');
        setTimeout(function () {
            $('#show-mess').html('');
        }, 5000);
    })
  },
  methods: {
    
  }
};
</script>
<style scoped>
<style>