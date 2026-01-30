CREATE OR REPLACE VIEW _presensi AS
SELECT 
    data_presensi.id_employee,
    data_presensi.name,
    data_presensi.jabatan,
    SUM(data_presensi.hadir) as total_hadir,
    SUM(data_presensi.cuti) as total_cuti,
    SUM(data_presensi.izin) as total_izin,
    employees.kuota_cuti,
    employees.kuota_izin,
    CASE 
        WHEN SUM(data_presensi.hadir) >= 22 THEN 'Baik'
        WHEN SUM(data_presensi.hadir) >= 15 THEN 'Cukup'
        ELSE 'Kurang'
    END as status_hadir,
FROM data_presensi 
LEFT JOIN employees ON data_presensi.id_employee = employees.id
GROUP BY data_presensi.id_employee, data_presensi.name, data_presensi.jabatan;