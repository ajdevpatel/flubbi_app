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
        Schema::create("loan_applications", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->bigInteger("user_id")->index("user_id")->default(0);
            $table->integer("type_id")->index("type_id")->default(0)->comment("loan_types.id");
            $table->timestamp("rec_date")->useCurrent();
            $table->decimal("loan_amount", 12, 2)->index("loan_amount")->default(0);
            $table->integer("cibil_score_id")->index("cibil_score_id")->default(0)->comment("cibil_scores.id");
            $table->integer("cibil_scores")->index("cibil_scores")->default(0);
            $table->integer("loan_purposes")->index("loan_purposes")->default(0)->comment("loan_purposes.id");
            $table->decimal("income", 10, 2)->index("income")->default(0)->comment("monthly income");
            $table->decimal("emi_paying", 10, 2)->index("emi_paying")->default(0)->comment("current emi paying");
            $table->enum("emi_bounce", [0, 1])->index("emi_bounce")->default(0)->comment("0-No, 1-Yes");
            $table->integer("loantenure")->index("loantenure")->default(0);
            $table->integer("user_type")->index("user_type")->default(0)->comment("1-Salaried Person, 2-Self Employed Person");
            $table->string("ip_address", 100)->nullable();
            $table->longText("note")->nullable();
            $table->enum("is_manual", [0, 1])->index("is_manual")->default(0)->comment("0-No, 1-Yes");
            $table->enum("is_locked", [0, 1])->index("is_locked")->default(0)->comment("0-No, 1-Yes");
            $table->integer("emp_id")->index("emp_id")->default(0)->comment("assigned to application");
            $table->enum("is_social", [0, 1])->index("is_social")->default(0)->comment("0-website, 1-facebook");
            $table->integer("status")->index("status")->default(1)->comment("loan_status.id");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->bigInteger("created_by")->index("created_by")->default(0);
            $table->bigInteger("updated_by")->index("updated_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });

        Schema::create("application_status_history", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->timestamp("rec_date")->useCurrent();
            $table->integer("application_id")->index("application_id")->default(0);
            $table->integer("bank_id")->index("bank_id")->default(0);
            $table->longText("remarks")->nullable();
            $table->enum("is_show", [0, 1])->index("is_show")->default(0)->comment("0-No, 1-Yes");
            $table->integer("status_id")->index("status_id")->default(0);
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->integer("created_by")->index("created_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
        });

        Schema::create("application_extra_information", function (Blueprint $table) {
            $table->id();
            $table->integer("application_id")->index("application_id")->default(0);
            $table->integer("application_status_id")->index("application_status_id")->default(0);
            $table->decimal("loan_amount", 12, 2)->index("loan_amount")->default(0);
            $table->decimal("process_fees", 10, 2)->index("process_fees")->default(0);
            $table->decimal("loan_roi", 5, 2)->index("loan_roi")->default(0);
            $table->integer("loantenure")->index("loantenure")->default(0);
            $table->decimal("insurance", 10, 2)->index("insurance")->default(0);
            $table->decimal("monthly_emi", 10, 2)->index("monthly_emi")->default(0);
            $table->string("sanction_letter")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("loan_applications");
        Schema::dropIfExists("application_status_history");
        Schema::dropIfExists("application_extra_information");
    }
};
