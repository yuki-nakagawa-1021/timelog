<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_request_id')->constrained()->cascadeOnDelete();
            $table->string('field');
            $table->string('old_value')->nullable();
            $table->string('new_value');
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
        Schema::dropIfExists('attendance_request_items');
    }
}
