<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations');
            $table->foreignId('attachment_id')->nullable()->constrained('attachments');
            $table->foreignId('meeting_id')->nullable()->constrained('meetings');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
