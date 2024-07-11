<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
			$table->integer('user_type_id')->nullable();
			$table->string('registration_number')->nullable();
            $table->date('birthday')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('gender')->nullable();
            $table->integer('qualification_id')->nullable();
            $table->string('year_of_passed_out')->nullable();
            $table->text('address')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->string('pincode')->nullable();
            $table->string('school')->nullable();
            $table->string('aadhaar_number')->nullable();            
            $table->string('linkedin_profile')->nullable();
            $table->string('upload_resume')->nullable();
            $table->string('upload_aadhar')->nullable();
            $table->string('upload_picture')->nullable();
            $table->json('upload_marklist')->nullable();
            $table->json('upload_agreement')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_contact')->nullable();
            $table->string('parent_email')->nullable();
            $table->string('parent_aadhar')->nullable();
            $table->string('parent_occupation')->nullable();
            $table->text('residential_address')->nullable();
			$table->string('designation')->nullable();
			$table->string('experience')->nullable();
			$table->integer('domain_id')->nullable();
			$table->json('subject')->nullable();
			
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
