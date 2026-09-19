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
        Schema::create("user_documents", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->integer("user_id")->index("user_id")->default(0);
            $table->string("profilephoto")->nullable();
            $table->enum("profilephoto_verify", [0, 1])->index("profilephoto_verify")->default(0);
            $table->string("aadharcard")->nullable();
            $table->string("aadharcard_number")->nullable();
            $table->enum("aadharcard_verify", [0, 1])->index("aadharcard_verify")->default(0);
            $table->string("pancard")->nullable();
            $table->string("pancard_number")->nullable();
            $table->enum("pancard_verify", [0, 1])->index("pancard_verify")->default(0);
            $table->string("cancelcheque")->nullable();
            $table->enum("cancelcheque_verify", [0, 1])->index("cancelcheque_verify")->default(0);
            $table->string("lightbill")->nullable();
            $table->enum("lightbill_verify", [0, 1])->index("lightbill_verify")->default(0);
            $table->string("bankstatement")->nullable();
            $table->enum("bankstatement_verify", [0, 1])->index("bankstatement_verify")->default(0);
            $table->string("formsixteen")->nullable();
            $table->enum("formsixteen_verify", [0, 1])->index("formsixteen_verify")->default(0);
            $table->string("addressproof")->nullable();
            $table->enum("addressproof_verify", [0, 1])->index("addressproof_verify")->default(0);
            $table->string("salaryslip")->nullable();
            $table->enum("salaryslip_verify", [0, 1])->index("salaryslip_verify")->default(0);
            $table->string("businessproof")->nullable();
            $table->enum("businessproof_verify", [0, 1])->index("businessproof_verify")->default(0);
            $table->string("itreturn")->nullable();
            $table->enum("itreturn_verify", [0, 1])->index("itreturn_verify")->default(0);
            $table->longText("remarks")->nullable();
            $table->enum("is_verified", [0, 1])->index("is_verified")->default(0);
            $table->bigInteger("updated_by")->index("updated_by")->default(0);
            $table->timestamp("updated_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_documents");
    }
};
