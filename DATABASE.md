## Struktur Database Kepegawaian

# dari preset
users
    - id
    - id_role
    - id_employee
    - name
    - phone
    - username
    - email
    - email_verified_at
    - password
    - status
    - remember_token

roles
    - id
    - name

menus
    - id_menu
    - name
    - type
    - status
    - route_name
    - route_params
    - href
    - sort
    - icon
    - target

accesses
    - id
    - id_role
    - id_menu
    - read
    - view
    - create
    - update
    - delete
    - publish

file
    - id
    - name
    - mime
    - size
    - path
    - parent_id
    - parent_table
    - parent_field
    - created_at
    - updated_at
    - created_by
    - updated_by

# main table

otp
    - id
    - id_user
    - code
    - expired_at
    - is_used
    - created_at

data_master
    - id
    - tipe (kelurahan, kecamatan, kabupaten, provinsi, base fare)
    - name
    - description

data_employee
    - id
    - nip
    - name
    - email
    - no_hp
    - tanggal_lahir
    - status_kawin
    - status
    - jumlah_anak
    - tanggal_masuk
    - jabatan
    - jenis_pagawai
    - departemen
    - usia
    - alamat_provinsi_id
    - alamat_kabupaten_id
    - alamat_kecamatan_id
    - alamat_kelurahan_id
    - alamat_detail
    - latitude
    - longitude
    - tempat_lahir_kabupaten_id
    - kuota_cuti
    - kuota_izin
    - created_by
    - updated_by

tunjangan_transports
    - id
    - employee_id
    - base_fare
    - jarak
    - hari_kerja
    - jarak_bulat
    - tunjangan
    - keterangan
    - created_at
    - updated_at

  logs 
    - id
    - tanggal
    - jam
    - user_id
    - username
    - deskripsi
    - modul
    - aksi
    - created_at
    - updated_at

data_presensi
    - id
    - id_employee
    - lokasi_absen (Gedung Utama,Gedung A,Gedung B)
    - checkin
    - checkout
    - name
    - jabatan
    - hadir
    - cuti
    - kuota_cuti
    - izin
    - kuota_izin
    - durasi
    - durasi_hadir
    - verifikasi (disetujui,ditolak)
    - verifikator
    - keterangan


