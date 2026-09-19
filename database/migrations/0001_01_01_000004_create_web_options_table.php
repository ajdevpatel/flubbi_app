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
        Schema::create("web_options", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("op_group", 80)->nullable();
            $table->string("op_label", 120)->nullable();
            $table->string("op_key", 120)->unique()->nullable();
            $table->text("op_value")->nullable();
            $table->text("note")->nullable();
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-active, 1-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->integer("updated_by")->index("updated_by")->default(0);
            $table->timestamp("updated_at")->useCurrent();
        });

        Schema::create("message_configuration", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("op_group", 80)->nullable();
            $table->string("op_label", 120)->nullable();
            $table->string("op_key", 120)->nullable();
            $table->text("op_value")->nullable();
            $table->text("note")->nullable();
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-active, 1-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->integer("updated_by")->index("updated_by")->default(0);
            $table->timestamp("updated_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("web_options");
        Schema::dropIfExists("message_configuration");
    }
};
