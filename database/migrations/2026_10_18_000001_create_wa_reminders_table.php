<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable("wa_reminders")) {
            Schema::create("wa_reminders", function (Blueprint $table) {
                $table->bigIncrements("id");
                $table->unsignedBigInteger("loan_application_id");
                $table->string("event", 60);
                $table->string("phone", 20)->nullable();
                $table->timestamp("sent_at")->nullable();
                $table->unique(["loan_application_id", "event"], "wa_reminders_app_event_unique");
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists("wa_reminders");
    }
};
