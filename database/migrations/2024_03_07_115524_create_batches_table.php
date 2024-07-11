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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id');
            $table->integer('course_id');
            $table->string('name');
            $table->integer('manager_id');
            $table->tinyInteger('attendance_setting');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('allow_edit_class_time');
            $table->tinyInteger('allow_edit_class_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
