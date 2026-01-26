CREATE OR REPLACE VIEW _presensi AS
SELECT 
    id_employee,
    name,
    jabatan,
    SUM(hadir) as total_hadir,
    SUM(cuti) as total_cuti,
    SUM(izin) as total_izin,
    MAX(kuota_cuti) as kuota_cuti,
    MAX(kuota_izin) as kuota_izin,
    CASE 
        WHEN SUM(hadir) >= 22 THEN 'Baik'
        WHEN SUM(hadir) >= 15 THEN 'Cukup'
        ELSE 'Kurang'
    END as status_hadir
FROM data_presensi 
GROUP BY id_employee, name, jabatan;