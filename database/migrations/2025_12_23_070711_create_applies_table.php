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
        Schema::create('applies', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_name');
            $table->string('email')->unique();
            $table->string('designation')->nullable();
            $table->string('member_since');
            $table->string('member_till');
            $table->text('impact')->nullable();
            
            $table->text('certificate_text')->nullable();
            $table->string('certificate_status')->default('pending'); // pending, verified, revision/approved/declined 
            $table->string('template_id')->nullable(); // template uid
            $table->string('issued_by')->nullable(); // advisor_uid
            $table->text('note')->nullable();
            $table->timestamp('issue_date')->nullable();

            $table->string('certificate_positions')->nullable();
            $table->string('certificate_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applies');
    }
};
