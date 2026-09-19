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

        Schema::create("channel_users", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->string("fname", 120)->nullable();
            $table->string("lname", 120)->nullable();
            $table->string("email", 120)->index("email")->unique()->nullable();
            $table->string("phone", 12)->index("phone")->unique();
            $table->integer("state_id")->default(0)->comment("states.id");
            $table->string("city", 120)->nullable();
            $table->decimal("monthly_earning", 10, 2)->index("income")->default(0)->comment("monthly earning");
            $table->text("remark")->nullable();
            $table->enum("i_agree", [0, 1])->index("i_agree")->default(0)->comment("0-No, 1-Yes");
            $table->enum("persontype", [0, 1])->index("persontype")->default(1)->comment("0-Salaried, 1-Self Employed");
            $table->enum("qualification", [0, 1, 2])->index("qualification")->default(1)->comment("0-Secondary (10th Pass), 1-Higher Secondary (12th Pass), 2-Diploma, 3-Bachelor Degree, 4-Master Degree, 5-Doctorate");
            $table->enum("status", [0, 1, 2])->index("status")->default(1)->comment("0-pending, 1-active, 2-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->bigInteger("created_by")->index("created_by")->default(0);
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
        Schema::dropIfExists("channel_users");
    }
};
