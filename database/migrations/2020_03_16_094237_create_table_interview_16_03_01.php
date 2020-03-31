<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInterview160301 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->char('guid', 36)->unique()->index();
            $table->unsignedBigInteger('job_application_id')->index()->nullable();
            $table->unsignedBigInteger('candidate_id')->index();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('interview_stage_id')->index()->nullable();
            $table->tinyInteger('status')->nullable()->default(0)->comment('pending | interview_scheduled | on_hold | rejected | hired');
            $table->string('interviewer_name')->nullable();
            $table->dateTime('interview_date')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->dateTime('hired_at')->nullable();
            $table->dateTime('joining_date')->nullable();
            $table->float('salary')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('interview_stage_histories', function (Blueprint $table){
            $table->bigIncrements('id')->index();
            $table->unsignedBigInteger('interview_id')->index();
            $table->unsignedBigInteger('interview_stage_id')->index()->nullable();
            $table->timestamps();
        });

        Schema::create('interview_status_histories', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('interview_id')->index();
            $table->tinyInteger('status')->default('1')->comment('pending | interview_scheduled | on_hold | rejected | hired');
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
        Schema::dropIfExists('interviews');
        Schema::dropIfExists('interview_stage_histories');
        Schema::dropIfExists('interview_status_histories');
    }
}
