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
        Schema::create("loan_types", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 40)->comment("use slug/url - Please don't use space or special characters. only a-z and 0-9 use.");
            $table->decimal("annual_rate", 5, 2)->default(0);
            $table->decimal("price", 8, 2)->default(0);
            $table->decimal("s_price", 8, 2)->default(0);
            $table->string("card_heading", 120)->nullable();
            $table->string("inv_prefix", 8)->nullable();
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("is_default", [0, 1])->index("is_default")->default(0)->comment("0-No, 1-Yes");
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-active, 1-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("loan_types");
    }
};
