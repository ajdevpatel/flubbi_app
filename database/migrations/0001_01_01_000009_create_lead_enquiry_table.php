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
        Schema::create("lead_type", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 120);
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-active, 1-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
        });

        Schema::create("lead_amount", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 120);
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-active, 1-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
        });

        Schema::create("lead_enquiry", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->integer("type_id")->index("type_id")->default(0)->comment("lead_type.id");
            $table->enum("type", [0, 1, 2])->index("type")->default(0)->comment("0-chat enquiry, 1-blog enquiry, 2-sidebar enquiry");
            $table->string("name", 120);
            $table->string("phone", 12)->nullable();
            $table->integer("amount")->index("amount")->default(0)->comment("lead_amount.id");
            $table->longText("note")->nullable();
            $table->string("ip_address", 100)->nullable();
            $table->enum("status", [0, 1, 2])->index("status")->default(0)->comment("0-Pending, 1-Progress, 2-Done");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->bigInteger("updated_by")->index("updated_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("lead_type");
        Schema::dropIfExists("lead_amount");
        Schema::dropIfExists("lead_enquiry");
    }
};