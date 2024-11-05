<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('resume');
            $table->text('message');
            $table->boolean('is_hired');
            $table->unsignedBigInteger('candidate_id');
            $table->foreignId('company_job_id')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_candidates');
    }
};
