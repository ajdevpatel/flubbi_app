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
        Schema::create("roi_packages", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->integer("type_id")->index("type_id")->default(0)->comment("loan_types.id");
            $table->integer("partner_id")->index("partner_id")->default(0)->comment("our_partners.id");
            $table->decimal("min_roi", 5, 2)->default(0);
            $table->decimal("max_roi", 5, 2)->default(0);
            $table->decimal("roi", 5, 2)->default(0);
            $table->decimal("processing_fee", 10, 2)->default(0);
            $table->integer("terms_years")->index("terms_years")->default(0);
            $table->integer("terms_months")->index("terms_months")->default(0);
            $table->string("label", 160)->nullable();
            $table->text("description")->nullable();
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-active, 1-deactive");
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
        Schema::dropIfExists("roi_packages");
    }
};
