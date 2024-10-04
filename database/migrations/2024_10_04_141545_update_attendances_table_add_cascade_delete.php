<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['meeting_id']);
            $table->foreign('meeting_id')
                  ->references('id')->on('meetings')
                  ->onDelete('set null');
            $table->unsignedBigInteger('meeting_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['meeting_id']);
            $table->foreign('meeting_id')
                  ->references('id')->on('meetings');
            $table->unsignedBigInteger('meeting_id')->nullable(false)->change();
        });
    }
};