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
        Schema::create("otp_logs", function (Blueprint $table) {
            $table->id();
            $table->string("phone", 12)->default(0);
            $table->integer("code")->index("code")->default(0);
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-unused, 1-used");
            $table->text("token")->nullable();
            $table->smallInteger("resend")->index("resend")->default(1)->comment("count send & resend sms");
            $table->enum("type", [0, 1, 2, 3])->index("type")->default(0)->comment("0-registration, 1-login, 2-Forgot Password, 3-loan apply-user");
            $table->timestamp("created_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("otp_logs");
    }
};
