<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm')->unique();
            $table->string('gelar_kehormatan')->nullable();
            $table->string('nama_lengkap')->index();
            $table->char('nik', 16)->default('9999999999999999')->index();
            $table->char('no_bpjs', 13)->nullable()->unique();
            $table->string('no_identitas_lain')->nullable();
            $table->boolean('status_merokok')->default(false)->comment('0=Tidak, 1=Ya');
            $table->string('nama_ibu_kandung');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir')->index();
            $table->tinyInteger('jenis_kelamin')->comment('0=Tidak diketahui, 1=Laki-laki, 2=Perempuan, 3=Tidak ditentukan, 4=Tidak mengisi');
            $table->tinyInteger('agama')->comment('1-8');
            $table->string('agama_lainnya')->nullable();
            $table->string('suku');
            $table->string('bahasa_dikuasai');
            $table->string('no_telepon_rumah')->nullable();
            $table->string('no_hp');
            $table->tinyInteger('pendidikan')->comment('0-8');
            $table->tinyInteger('pekerjaan')->comment('0-5');
            $table->string('pekerjaan_lainnya')->nullable();
            $table->tinyInteger('status_pernikahan')->comment('1-4');

            // Emergency Contact Data
            $table->string('emergency_nama');
            $table->string('emergency_hubungan');
            $table->char('emergency_no_ktp', 16);
            $table->string('emergency_no_hp');
            $table->text('emergency_alamat');
            
            // Alamat KTP
            $table->text('alamat_ktp');
            $table->char('rt_ktp', 3);
            $table->char('rw_ktp', 3);
            $table->string('kelurahan_ktp');
            $table->string('kecamatan_ktp');
            $table->string('kabupaten_ktp');
            $table->string('provinsi_ktp');
            $table->string('kode_pos_ktp');
            $table->string('negara_ktp');

            // Alamat Domisili
            $table->text('alamat_domisili');
            $table->char('rt_domisili', 3);
            $table->char('rw_domisili', 3);
            $table->string('kelurahan_domisili');
            $table->string('kecamatan_domisili');
            $table->string('kabupaten_domisili');
            $table->string('provinsi_domisili');
            $table->string('kode_pos_domisili');
            $table->string('negara_domisili');

            $table->string('penjamin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
