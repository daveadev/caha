-- Set the school year variable
SET @school_year = 2023;

-- Main query
SELECT
    s.id,
    s.sno,
    s.last_name,
    s.first_name,
    yl.description as year_level,
    s2.name as section_name,
    ROUND(cb.esp) as last_sy_enrolled,
    s.elgb_school as last_school_attended,
    s.elgb_school_id as last_school_id,
    h.mobile_number as household_mobile,
    GROUP_CONCAT(DISTINCT CONCAT(g.last_name, ' ', g.first_name, '-', g.rel) ORDER BY g.last_name, g.first_name SEPARATOR ', ') AS guardians 
FROM
    classlist_blocks cb
INNER JOIN students s ON
    s.id = cb.student_id
INNER JOIN sections s2 ON
    cb.section_id = s2.id
INNER JOIN year_levels yl ON
    yl.id = s2.year_level_id
INNER JOIN (
    -- Subquery to get the latest enrolled year per student since the given school year
    SELECT
        student_id,
        MAX(esp) AS max_esp
    FROM
        classlist_blocks
    WHERE
        classlist_blocks.esp >= @school_year
    GROUP BY
        student_id
) max_esp_per_student ON
    cb.student_id = max_esp_per_student.student_id
    AND cb.esp = max_esp_per_student.max_esp
LEFT JOIN household_members hm ON
    hm.entity_id = s.id
LEFT JOIN households h ON
    hm.household_id = h.id
LEFT JOIN household_members hg ON
    hg.household_id = hm.household_id
    AND hg.type = 'GRD'
LEFT JOIN guardians g ON
    g.id = hg.entity_id
GROUP BY
    s.id, cb.esp, yl.id 
ORDER BY
    cb.esp, yl.id, yl.name, s.gender DESC, s.last_name, s.first_name;
