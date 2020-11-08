<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->bigInteger('timeStamp');
            $table->string('visitTimeStamp');
            $table->boolean('studentType');
            $table->string('userName')->nullable();
            $table->string('userNRIC')->nullable();
            $table->date('userBirthday')->nullable();
            $table->boolean('userGender')->nullable();
            $table->boolean('studentStatus')->nullable();
            $table->string('currentSchool')->nullable();
            $table->string('contactNo')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('emergencyContactNo')->nullable();
            $table->string('emergencyContactName')->nullable();
            $table->string('relationToEmergencyContact')->nullable();
            $table->string('bankName')->nullable();
            $table->string('accountNo')->nullable();
            $table->boolean('asWaiter')->nullable();
            $table->boolean('dyedHair')->nullable();
            $table->boolean('visibleTattoo')->nullable();
            $table->string('workPassPhoto')->nullable();
            $table->string('studentCardFront')->nullable();
            $table->string('studentCardBack')->nullable();
            $table->string('NRICFront')->nullable();
            $table->string('NRICBack')->nullable();
            $table->integer('jobsDone')->nullable();
            $table->integer('userConfirmed')->nullable();
            $table->string('userPants')->nullable();
            $table->string('userShoes')->nullable();
            $table->boolean('userShoesApproved')->nullable();
            $table->boolean('userPantsApproved')->nullable();
            $table->boolean('isFavourite')->nullable();
            $table->boolean('isWarned')->nullable();
            $table->string('verifyCode')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('chatURL')->nullable();
            $table->integer('__v')->nullable();
            $table->string('feedback')->nullable();
            $table->boolean('TCC')->nullable();
            $table->boolean('isDiamond')->nullable();
            $table->string('referralCode')->nullable();
            $table->string('expiryDateOfStudentPass')->nullable();
            $table->boolean('isJCActive')->nullable();
            $table->boolean('isW')->nullable();
            $table->boolean('isMO')->nullable();
            $table->boolean('isMC')->nullable();
            $table->boolean('isRWS')->nullable();
            $table->boolean('isKempinski')->nullable();
            $table->boolean('isHilton')->nullable();
            $table->boolean('isGWP')->nullable();
            $table->boolean('isRaffles')->nullable();
            $table->longText('comments')->nullable();
            $table->boolean('activated')->nullable();
            /*$table->dateTime('deleted_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();*/
            $table->string('status_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
