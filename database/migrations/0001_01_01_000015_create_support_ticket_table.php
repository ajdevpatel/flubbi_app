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
        Schema::create("support_reasons", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 160)->nullable();
            $table->string("class", 40)->nullable();
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
        });

        Schema::create("support_tickets", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->integer("user_id")->index("user_id")->default(0)->comment("0-gest user");
            $table->integer("reason_id")->index("reason_id")->default(0)->comment("support_reasons.id");
            $table->string("auto_number", 60)->unique();
            $table->string("name", 120);
            $table->string("phone", 12)->nullable();
            $table->string("mail", 120);
            $table->longText("description")->nullable();
            $table->string("ip_address", 100)->nullable();
            $table->enum("status", [0, 1, 2, 3, 4, 5])->index("status")->default(0)->comment("0-open, 1-processing, 2-close/no response, 3-hold, 4-reopen 5-solve");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->bigInteger("created_by")->index("created_by")->default(0);
            $table->bigInteger("updated_by")->index("updated_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });

        Schema::create("support_tickets_log", function (Blueprint $table) {
            $table->id();
            $table->integer("support_tickets_id")->index("support_tickets_id")->default(0);
            $table->longText("description")->nullable();
            $table->enum("status", [0, 1, 2, 3, 4, 5])->index("status")->default(0)->comment("0-open, 1-processing, 2-close/no response, 3-hold, 4-reopen 5-solve");
            $table->bigInteger("created_by")->index("created_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("support_reasons");
        Schema::dropIfExists("support_tickets");
        Schema::dropIfExists("support_ticket_log");
    }
};
