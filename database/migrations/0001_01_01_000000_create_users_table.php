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
        Schema::create("roles", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 40)->nullable();
            $table->string("description")->nullable();
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-Active, 1-DeActive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
        });

        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->timestamp("rec_date")->useCurrent();
            $table->string("name", 120)->nullable();
            $table->string("email", 120)->index("email")->unique()->nullable();
            $table->string("phone", 12)->index("phone")->unique();
            $table->enum("role", [0, 1, 2, 3, 4])->index("role")->default(4)->comment("roles.id");
            $table->integer("state_id")->default(0)->comment("states.id");
            $table->string("city", 120)->nullable();
            $table->integer("pincode")->default(0);
            $table->string("gstno", 20)->nullable();
            $table->string("password");
            $table->enum("i_agree", [0, 1])->index("i_agree")->default(0)->comment("0-No, 1-Yes");
            $table->enum("is_manual", [0, 1])->index("is_manual")->default(0)->comment("0-No, 1-Yes");
            $table->enum("is_dnd", [0, 1])->index("is_dnd")->default(0)->comment("0-No, 1-Yes");
            $table->enum("is_social", [0, 1])->index("is_social")->default(0)->comment("0-website, 1-facebook");
            $table->timestamp("dob")->nullable();
            $table->timestamp("dnd_at")->useCurrent();
            $table->string("doc_dir")->unique();
            $table->enum("status", [0, 1, 2])->index("status")->default(1)->comment("0-pending, 1-active, 2-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->rememberToken();
            $table->timestamp("email_verified_at")->nullable();
            $table->bigInteger("created_by")->index("created_by")->default(0);
            $table->bigInteger("updated_by")->index("updated_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });

        Schema::create("password_reset_tokens", function (Blueprint $table) {
            $table->string("email")->primary();
            $table->string("token");
            $table->timestamp("created_at")->nullable();
        });

        Schema::create("sessions", function (Blueprint $table) {
            $table->string("id")->primary();
            $table->foreignId("user_id")->nullable()->index();
            $table->string("ip_address", 45)->nullable();
            $table->text("user_agent")->nullable();
            $table->longText("payload");
            $table->integer("last_activity")->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("roles");
        Schema::dropIfExists("users");
        Schema::dropIfExists("password_reset_tokens");
        Schema::dropIfExists("sessions");
        Schema::dropIfExists("states");
    }
};