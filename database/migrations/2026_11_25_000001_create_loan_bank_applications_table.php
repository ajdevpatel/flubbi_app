<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable("loan_bank_applications")) {
            return;
        }

        Schema::create("loan_bank_applications", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedBigInteger("loan_application_id");
            $table->unsignedBigInteger("self_login_bank_id");
            $table->unsignedBigInteger("user_id");
            $table->integer("apply_count")->default(1);
            $table->timestamp("first_applied_at")->nullable();
            $table->timestamp("last_applied_at")->nullable();
            $table->timestamps();

            $table->unique(["loan_application_id", "self_login_bank_id"], "loan_bank_app_unique");
            $table->index("loan_application_id");
            $table->index("self_login_bank_id");
            $table->index("user_id");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("loan_bank_applications");
    }
};
