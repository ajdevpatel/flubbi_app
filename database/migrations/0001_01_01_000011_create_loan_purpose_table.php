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
        Schema::create("loan_purposes", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 120);
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-active, 1-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
        });

        Schema::create("cibil_scores", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 120);
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-active, 1-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
        });

        Schema::create("loan_status", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 120)->nullable();
            $table->string("class", 40)->nullable();
            $table->text("note")->nullable();
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
        });

        Schema::create("loan_remarks_message", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 120)->nullable();
            $table->text("message")->nullable();
            $table->text("note")->nullable();
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("loan_purposes");
        Schema::dropIfExists("cibil_scores");
        Schema::dropIfExists("loan_status");
        Schema::dropIfExists("loan_remarks_message");
    }
};
