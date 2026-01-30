 CREATE OR REPLACE VIEW _wilayah AS
 SELECT 
    current.id, 
    current.id_parent, 
    CASE
        WHEN current.tipe = 'kecamatan' THEN CONCAT(current.name, ' - ', parent.name)
        ELSE current.name
    END AS name,
    current.tipe,
    parent.id_parent AS parent_id_parent,
    parent.name AS parent_name,
    parent.tipe AS parent_tipe,
    grandparent.id AS id_grandparent
FROM data_masters current
LEFT JOIN data_masters parent ON parent.id = current.id_parent
LEFT JOIN data_masters grandparent ON grandparent.id = parent.id_parent