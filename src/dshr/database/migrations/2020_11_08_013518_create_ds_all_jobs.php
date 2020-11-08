<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDsAllJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ds_all_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('job_id');
            $table->integer('user_id');
            $table->integer('status');
            $table->boolean('workTime_confirmed');
            $table->string('real_start');
            $table->string('real_end');
            $table->string('timestamp');
            $table->string('attendenceStatus');
            $table->string('attendenceTimeStamp');
            $table->string('paidTimeIn');
            $table->string('paidTimeOut');
            $table->float('breakTime');
            $table->float('totalHours');
            $table->string('remarks');
            $table->integer('rwsConfirmed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ds_all_jobs');
    }
}
