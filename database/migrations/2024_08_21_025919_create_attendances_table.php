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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('check_in');
            $table->timestamp('check_out')->nullable();
            $table->foreignId('location_id')->constrained('locations');
            $table->foreignId('attachment_id')->nullable()->constrained('attachments');
            $table->foreignId('meeting_id')->nullable()->constrained('meetings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
