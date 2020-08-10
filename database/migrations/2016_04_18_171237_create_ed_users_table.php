<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;

    class CreateEdUsersTable extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email', 255);
                $table->string('name', 255);
                $table->string('password', 64);
                $table->string('phone', 15)->nullable();
                $table->integer('user_type_id')->comment = "References User Type Table";
                $table->string('security_code', 20)->nullable();
                $table->integer('user_status')->default(1)->comment = "References User Status Table";
                $table->string('created_by');
                $table->string('updated_by')->nullable();
                $table->datetime('email_verified_at')->nullable();
                $table->integer('user_id')->comment = "References Customer,Medical Professional, Admin Table based on user type";
                $table->string('remember_token', 64)->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::drop('users');
        }

    }
