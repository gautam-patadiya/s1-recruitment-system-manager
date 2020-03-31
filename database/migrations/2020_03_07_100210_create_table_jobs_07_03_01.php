<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableJobs070301 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->char('guid', 36)->unique()->index();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('job_department_id')->nullable()->index();
            $table->tinyInteger('status')->nullable()->default(0)->comment('1.Open 2.Closed');
            $table->integer('open_vacancies')->nullable()->default(0);
            $table->string('title')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->text('job_description')->nullable();
            $table->text('company_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('archived_at')->nullable();
        });

        Schema::create('job_experience_levels', function (Blueprint $table) {
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('experience_level_id')->nullable();
        });

        Schema::create('job_qualifications', function (Blueprint $table) {
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('qualification_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_experience_levels');
        Schema::dropIfExists('job_qualifications');
    }
}
