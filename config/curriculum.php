<?php

/*
|--------------------------------------------------------------------------
| Strengthened SHS Curriculum — v2
|--------------------------------------------------------------------------
|
| Structure change vs v1: each subject code may appear at BOTH grades
| (e.g. AF-CROP is G11 core-elective AND G12 advanced). Use composite
| keys like 'AF-CROP-11' and 'AF-CROP-12'. Each entry declares
| 'subject_code' which is the actual DB lookup code.
|
| Weekly load:
|   G11: 5 cores × 5 sessions = 25   + 2 electives × (3+2) =  5  → 30
|   G12: 6 electives × 5 sessions                    = 30  → 30
|
| All sessions are 60 minutes. The generator can extend to 120/180 by
| claiming adjacent slots (not used by any current subject).
|
*/

return [

    /* ═══════════════════════════════════════════════════════════════
     | DEFAULT TEACHERS — keyed by subject_code
     |═══════════════════════════════════════════════════════════════ */

    'default_teachers' => [
        'CORE-COMM'      => 'jocelyn.fernandez@salawagshs.deped.gov.ph',
        'CORE-MATH'      => 'antonio.cruz@salawagshs.deped.gov.ph',
        'CORE-SCI'       => 'rebecca.luna@salawagshs.deped.gov.ph',
        'CORE-LCS'       => 'benjamin.ocampo@salawagshs.deped.gov.ph',
        'CORE-KASP'      => 'lourdes.santos@salawagshs.deped.gov.ph',

        'STEM-PCALC'     => 'ramon.villareal@salawagshs.deped.gov.ph',
        'STEM-BCALC'     => 'ramon.villareal@salawagshs.deped.gov.ph',
        'STEM-BIO12'     => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-CHEM12'    => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-PHYS12'    => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-RESMETH'   => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-DBMGMT'    => 'mark.reyes@salawagshs.deped.gov.ph',
        'STEM-DATA'      => 'mark.reyes@salawagshs.deped.gov.ph',
        'STEM-EMPowTECH' => 'mark.reyes@salawagshs.deped.gov.ph',

        'ICT-CSS'        => 'mark.reyes@salawagshs.deped.gov.ph',
        'ICT-PROGJAVA'   => 'mark.reyes@salawagshs.deped.gov.ph',
        'ICT-PROGDOTNET' => 'christian.bautista@salawagshs.deped.gov.ph',
        'ICT-BROADBAND'  => 'christian.bautista@salawagshs.deped.gov.ph',
        'ICT-CONTACT'    => 'christian.bautista@salawagshs.deped.gov.ph',

        'HO-FBO'         => 'marilou.delacruz@salawagshs.deped.gov.ph',
        'HO-BAKERY'      => 'ernesto.padilla@salawagshs.deped.gov.ph',
        'HO-KITCHEN'     => 'marilou.delacruz@salawagshs.deped.gov.ph',
        'HO-HOUSEKEEP'   => 'ernesto.padilla@salawagshs.deped.gov.ph',
        'HO-BARTEND'     => 'ernesto.padilla@salawagshs.deped.gov.ph',
        'HO-EVENTS'      => 'marilou.delacruz@salawagshs.deped.gov.ph',

        'ASH-CREATW1'    => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-WORLDREL'   => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-DISCSOC'    => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-DISCAPP'    => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-PHILHUM'    => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-TRENDS'     => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-COMMENG'    => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-CREATIND'   => 'hannah.bautista@salawagshs.deped.gov.ph',
        'ASH-MEDARTS'    => 'hannah.bautista@salawagshs.deped.gov.ph',
        'ASH-ARTSAPP'    => 'hannah.bautista@salawagshs.deped.gov.ph',

        'BE-BASACC'      => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'BE-BUSFIN'      => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'BE-CONTMKT'     => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'BE-ENTREP'      => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'BE-ORG-MGMT'    => 'elizabeth.reyes@salawagshs.deped.gov.ph',

        'SW-EXSPORT'     => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-HUMANMOV'    => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-SPORTOFF'    => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-SAFEFIRST'   => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-SPORTCOACH'  => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-SPORTMGMT'   => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-PEFIT'       => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-PESPORT'     => 'daniel.lim@salawagshs.deped.gov.ph',

        'AF-CROP'        => 'rogelio.fernandez@salawagshs.deped.gov.ph',
        'AF-AGROENT'     => 'rogelio.fernandez@salawagshs.deped.gov.ph',
        'AF-AQUA'        => 'rogelio.fernandez@salawagshs.deped.gov.ph',
        'AF-FOODINNO'    => 'rogelio.fernandez@salawagshs.deped.gov.ph',

        'IN-REFRIG'      => 'rodolfo.villamor@salawagshs.deped.gov.ph',
        'IN-AIRCON'      => 'rodolfo.villamor@salawagshs.deped.gov.ph',
        'IN-ELEC'        => 'rodolfo.villamor@salawagshs.deped.gov.ph',
        'IN-SOLAR'       => 'rodolfo.villamor@salawagshs.deped.gov.ph',

        'AU-AUTO'        => 'mario.villanueva@salawagshs.deped.gov.ph',
        'AU-SMALLENG'    => 'mario.villanueva@salawagshs.deped.gov.ph',
        'CO-CARP'        => 'mario.villanueva@salawagshs.deped.gov.ph',
        'CO-MASON'       => 'mario.villanueva@salawagshs.deped.gov.ph',
        'CO-PLUMB'       => 'mario.villanueva@salawagshs.deped.gov.ph',

        'CA-VISGRAPH'    => 'hannah.bautista@salawagshs.deped.gov.ph',
        'CA-ANIM'        => 'hannah.bautista@salawagshs.deped.gov.ph',
        'CA-MUSIC'       => 'hannah.bautista@salawagshs.deped.gov.ph',

        'AR-GARMENTS'    => 'marilou.delacruz@salawagshs.deped.gov.ph',
        'AR-FASHACC'     => 'marilou.delacruz@salawagshs.deped.gov.ph',

        'AW-BEAUTY'      => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'AW-BARBER'      => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'AW-CAREADULT'   => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'AW-CARECHILD'   => 'elizabeth.reyes@salawagshs.deped.gov.ph',

        'MA-MARENG'      => 'rodolfo.villamor@salawagshs.deped.gov.ph',
        'MA-FIELDEXP'    => 'rodolfo.villamor@salawagshs.deped.gov.ph',

        'FE-RESMETH'     => 'marilou.aquino@salawagshs.deped.gov.ph',
        'FE-DESIGN'      => 'hannah.bautista@salawagshs.deped.gov.ph',
        'FE-CREATPROD'   => 'hannah.bautista@salawagshs.deped.gov.ph',
        'FE-WORKIMM'     => 'marilou.aquino@salawagshs.deped.gov.ph',
    ],

    /* ═══════════════════════════════════════════════════════════════
     | SUBJECTS — composite keys, {CODE}-{GRADE}
     |═══════════════════════════════════════════════════════════════ */

    'subjects' => [

        // ─── G11 CORES (all clusters, 5 sessions) ───────────────────
        'CORE-COMM-11' => ['subject_code' => 'CORE-COMM', 'grade' => 11, 'clusters' => '*', 'room_type' => 'regular', 'sessions_per_week' => 5],
        'CORE-MATH-11' => ['subject_code' => 'CORE-MATH', 'grade' => 11, 'clusters' => '*', 'room_type' => 'regular', 'sessions_per_week' => 5],
        'CORE-SCI-11'  => ['subject_code' => 'CORE-SCI',  'grade' => 11, 'clusters' => '*', 'room_type' => 'regular', 'sessions_per_week' => 5],
        'CORE-LCS-11'  => ['subject_code' => 'CORE-LCS',  'grade' => 11, 'clusters' => '*', 'room_type' => 'regular', 'sessions_per_week' => 5],
        'CORE-KASP-11' => ['subject_code' => 'CORE-KASP', 'grade' => 11, 'clusters' => '*', 'room_type' => 'regular', 'sessions_per_week' => 5],

        // ─── G11 ELECTIVES — 3+2 split per cluster ──────────────────
        'STEM-PCALC-11'   => ['subject_code' => 'STEM-PCALC',   'grade' => 11, 'clusters' => ['STEM'],              'room_type' => 'regular',      'sessions_per_week' => 3],
        'STEM-BIO12-11'   => ['subject_code' => 'STEM-BIO12',   'grade' => 11, 'clusters' => ['STEM'],              'room_type' => 'science_lab',  'sessions_per_week' => 2],
        'ICT-CSS-11'      => ['subject_code' => 'ICT-CSS',      'grade' => 11, 'clusters' => ['ICT'],               'room_type' => 'computer_lab', 'sessions_per_week' => 3],
        'ICT-PROGJAVA-11' => ['subject_code' => 'ICT-PROGJAVA', 'grade' => 11, 'clusters' => ['ICT'],               'room_type' => 'computer_lab', 'sessions_per_week' => 2],
        'HO-FBO-11'       => ['subject_code' => 'HO-FBO',       'grade' => 11, 'clusters' => ['HOSPITALITY'],       'room_type' => 'laboratory',   'sessions_per_week' => 3],
        'HO-BAKERY-11'    => ['subject_code' => 'HO-BAKERY',    'grade' => 11, 'clusters' => ['HOSPITALITY'],       'room_type' => 'laboratory',   'sessions_per_week' => 2],
        'ASH-CREATW1-11'  => ['subject_code' => 'ASH-CREATW1',  'grade' => 11, 'clusters' => ['ARTS-SOC-HUM'],      'room_type' => 'regular',      'sessions_per_week' => 3],
        'ASH-WORLDREL-11' => ['subject_code' => 'ASH-WORLDREL', 'grade' => 11, 'clusters' => ['ARTS-SOC-HUM'],      'room_type' => 'regular',      'sessions_per_week' => 2],
        'BE-BASACC-11'    => ['subject_code' => 'BE-BASACC',    'grade' => 11, 'clusters' => ['BUSINESS-ENTREP'],   'room_type' => 'regular',      'sessions_per_week' => 3],
        'BE-ORG-MGMT-11'  => ['subject_code' => 'BE-ORG-MGMT',  'grade' => 11, 'clusters' => ['BUSINESS-ENTREP'],   'room_type' => 'regular',      'sessions_per_week' => 2],
        'SW-EXSPORT-11'   => ['subject_code' => 'SW-EXSPORT',   'grade' => 11, 'clusters' => ['SPORTS-WELLNESS'],   'room_type' => 'lecture',      'sessions_per_week' => 3],
        'SW-HUMANMOV-11'  => ['subject_code' => 'SW-HUMANMOV',  'grade' => 11, 'clusters' => ['SPORTS-WELLNESS'],   'room_type' => 'lecture',      'sessions_per_week' => 2],
        'AF-CROP-11'      => ['subject_code' => 'AF-CROP',      'grade' => 11, 'clusters' => ['AGRI-FISHERY'],      'room_type' => 'workshop',     'sessions_per_week' => 3],
        'AF-AGROENT-11'   => ['subject_code' => 'AF-AGROENT',   'grade' => 11, 'clusters' => ['AGRI-FISHERY'],      'room_type' => 'workshop',     'sessions_per_week' => 2],
        'IN-REFRIG-11'    => ['subject_code' => 'IN-REFRIG',    'grade' => 11, 'clusters' => ['INDUSTRIAL'],        'room_type' => 'workshop',     'sessions_per_week' => 3],
        'IN-AIRCON-11'    => ['subject_code' => 'IN-AIRCON',    'grade' => 11, 'clusters' => ['INDUSTRIAL'],        'room_type' => 'workshop',     'sessions_per_week' => 2],
        'AU-AUTO-11'      => ['subject_code' => 'AU-AUTO',      'grade' => 11, 'clusters' => ['AUTOMOTIVE'],        'room_type' => 'workshop',     'sessions_per_week' => 3],
        'AU-SMALLENG-11'  => ['subject_code' => 'AU-SMALLENG',  'grade' => 11, 'clusters' => ['AUTOMOTIVE'],        'room_type' => 'workshop',     'sessions_per_week' => 2],
        'CO-CARP-11'      => ['subject_code' => 'CO-CARP',      'grade' => 11, 'clusters' => ['CONSTRUCTION'],      'room_type' => 'workshop',     'sessions_per_week' => 3],
        'CO-MASON-11'     => ['subject_code' => 'CO-MASON',     'grade' => 11, 'clusters' => ['CONSTRUCTION'],      'room_type' => 'workshop',     'sessions_per_week' => 2],
        'CA-VISGRAPH-11'  => ['subject_code' => 'CA-VISGRAPH',  'grade' => 11, 'clusters' => ['CREATIVE-ARTS'],     'room_type' => 'workshop',     'sessions_per_week' => 3],
        'CA-ANIM-11'      => ['subject_code' => 'CA-ANIM',      'grade' => 11, 'clusters' => ['CREATIVE-ARTS'],     'room_type' => 'workshop',     'sessions_per_week' => 2],
        'AR-GARMENTS-11'  => ['subject_code' => 'AR-GARMENTS',  'grade' => 11, 'clusters' => ['ARTISANRY'],         'room_type' => 'workshop',     'sessions_per_week' => 3],
        'AR-FASHACC-11'   => ['subject_code' => 'AR-FASHACC',   'grade' => 11, 'clusters' => ['ARTISANRY'],         'room_type' => 'workshop',     'sessions_per_week' => 2],
        'AW-BEAUTY-11'    => ['subject_code' => 'AW-BEAUTY',    'grade' => 11, 'clusters' => ['AESTHETIC-WELLNESS'],'room_type' => 'laboratory',   'sessions_per_week' => 3],
        'AW-CAREADULT-11' => ['subject_code' => 'AW-CAREADULT', 'grade' => 11, 'clusters' => ['AESTHETIC-WELLNESS'],'room_type' => 'laboratory',   'sessions_per_week' => 2],
        'MA-MARENG-11'    => ['subject_code' => 'MA-MARENG',    'grade' => 11, 'clusters' => ['MARITIME'],          'room_type' => 'laboratory',   'sessions_per_week' => 3],
        'MA-FIELDEXP-11'  => ['subject_code' => 'MA-FIELDEXP',  'grade' => 11, 'clusters' => ['MARITIME'],          'room_type' => 'laboratory',   'sessions_per_week' => 2],
        'FE-RESMETH-11'   => ['subject_code' => 'FE-RESMETH',   'grade' => 11, 'clusters' => ['FIELD-EXP'],         'room_type' => 'regular',      'sessions_per_week' => 3],
        'FE-DESIGN-11'    => ['subject_code' => 'FE-DESIGN',    'grade' => 11, 'clusters' => ['FIELD-EXP'],         'room_type' => 'regular',      'sessions_per_week' => 2],

        // ─── G12 ELECTIVES — 5 sessions each ────────────────────────
        'STEM-BCALC-12'      => ['subject_code' => 'STEM-BCALC',      'grade' => 12, 'clusters' => ['STEM'],         'room_type' => 'regular',      'sessions_per_week' => 5],
        'STEM-CHEM12-12'     => ['subject_code' => 'STEM-CHEM12',     'grade' => 12, 'clusters' => ['STEM'],         'room_type' => 'science_lab',  'sessions_per_week' => 5],
        'STEM-PHYS12-12'     => ['subject_code' => 'STEM-PHYS12',     'grade' => 12, 'clusters' => ['STEM'],         'room_type' => 'science_lab',  'sessions_per_week' => 5],
        'STEM-DATA-12'       => ['subject_code' => 'STEM-DATA',       'grade' => 12, 'clusters' => ['STEM'],         'room_type' => 'computer_lab', 'sessions_per_week' => 5],
        'STEM-RESMETH-12'    => ['subject_code' => 'STEM-RESMETH',    'grade' => 12, 'clusters' => ['STEM', 'AGRI-FISHERY', 'INDUSTRIAL', 'AUTOMOTIVE', 'CONSTRUCTION', 'ARTISANRY', 'AESTHETIC-WELLNESS', 'MARITIME', 'FIELD-EXP'], 'room_type' => 'regular', 'sessions_per_week' => 5],
        'STEM-DBMGMT-12'     => ['subject_code' => 'STEM-DBMGMT',     'grade' => 12, 'clusters' => ['STEM', 'ICT', 'BUSINESS-ENTREP'], 'room_type' => 'computer_lab', 'sessions_per_week' => 5],
        'STEM-EMPowTECH-12'  => ['subject_code' => 'STEM-EMPowTECH',  'grade' => 12, 'clusters' => ['ICT', 'BUSINESS-ENTREP'],         'room_type' => 'computer_lab', 'sessions_per_week' => 5],

        'ICT-PROGDOTNET-12'  => ['subject_code' => 'ICT-PROGDOTNET',  'grade' => 12, 'clusters' => ['ICT'], 'room_type' => 'computer_lab', 'sessions_per_week' => 5],
        'ICT-BROADBAND-12'   => ['subject_code' => 'ICT-BROADBAND',   'grade' => 12, 'clusters' => ['ICT'], 'room_type' => 'computer_lab', 'sessions_per_week' => 5],
        'ICT-CONTACT-12'     => ['subject_code' => 'ICT-CONTACT',     'grade' => 12, 'clusters' => ['ICT'], 'room_type' => 'computer_lab', 'sessions_per_week' => 5],

        'HO-KITCHEN-12'      => ['subject_code' => 'HO-KITCHEN',      'grade' => 12, 'clusters' => ['HOSPITALITY'], 'room_type' => 'laboratory', 'sessions_per_week' => 5],
        'HO-HOUSEKEEP-12'    => ['subject_code' => 'HO-HOUSEKEEP',    'grade' => 12, 'clusters' => ['HOSPITALITY'], 'room_type' => 'laboratory', 'sessions_per_week' => 5],
        'HO-BARTEND-12'      => ['subject_code' => 'HO-BARTEND',      'grade' => 12, 'clusters' => ['HOSPITALITY'], 'room_type' => 'laboratory', 'sessions_per_week' => 5],
        'HO-EVENTS-12'       => ['subject_code' => 'HO-EVENTS',       'grade' => 12, 'clusters' => ['HOSPITALITY'], 'room_type' => 'regular',    'sessions_per_week' => 5],

        'BE-ORG-MGMT-12'     => ['subject_code' => 'BE-ORG-MGMT',     'grade' => 12, 'clusters' => ['HOSPITALITY', 'ARTISANRY'], 'room_type' => 'regular', 'sessions_per_week' => 5],
        'BE-ENTREP-12'       => ['subject_code' => 'BE-ENTREP',       'grade' => 12, 'clusters' => ['HOSPITALITY', 'ICT', 'BUSINESS-ENTREP', 'AGRI-FISHERY', 'INDUSTRIAL', 'AUTOMOTIVE', 'CONSTRUCTION', 'ARTISANRY', 'AESTHETIC-WELLNESS', 'MARITIME', 'FIELD-EXP'], 'room_type' => 'regular', 'sessions_per_week' => 5],
        'BE-BUSFIN-12'       => ['subject_code' => 'BE-BUSFIN',       'grade' => 12, 'clusters' => ['BUSINESS-ENTREP'], 'room_type' => 'regular', 'sessions_per_week' => 5],
        'BE-CONTMKT-12'      => ['subject_code' => 'BE-CONTMKT',      'grade' => 12, 'clusters' => ['BUSINESS-ENTREP'], 'room_type' => 'regular', 'sessions_per_week' => 5],

        'ASH-DISCSOC-12'     => ['subject_code' => 'ASH-DISCSOC',     'grade' => 12, 'clusters' => ['ARTS-SOC-HUM'], 'room_type' => 'regular',  'sessions_per_week' => 5],
        'ASH-DISCAPP-12'     => ['subject_code' => 'ASH-DISCAPP',     'grade' => 12, 'clusters' => ['ARTS-SOC-HUM'], 'room_type' => 'regular',  'sessions_per_week' => 5],
        'ASH-PHILHUM-12'     => ['subject_code' => 'ASH-PHILHUM',     'grade' => 12, 'clusters' => ['ARTS-SOC-HUM'], 'room_type' => 'regular',  'sessions_per_week' => 5],
        'ASH-TRENDS-12'      => ['subject_code' => 'ASH-TRENDS',      'grade' => 12, 'clusters' => ['ARTS-SOC-HUM'], 'room_type' => 'regular',  'sessions_per_week' => 5],
        'ASH-COMMENG-12'     => ['subject_code' => 'ASH-COMMENG',     'grade' => 12, 'clusters' => ['ARTS-SOC-HUM', 'BUSINESS-ENTREP', 'FIELD-EXP'], 'room_type' => 'regular', 'sessions_per_week' => 5],
        'ASH-CREATIND-12'    => ['subject_code' => 'ASH-CREATIND',    'grade' => 12, 'clusters' => ['ARTS-SOC-HUM', 'CREATIVE-ARTS', 'ARTISANRY'],   'room_type' => 'workshop', 'sessions_per_week' => 5],
        'ASH-MEDARTS-12'     => ['subject_code' => 'ASH-MEDARTS',     'grade' => 12, 'clusters' => ['CREATIVE-ARTS'], 'room_type' => 'workshop', 'sessions_per_week' => 5],
        'ASH-ARTSAPP-12'     => ['subject_code' => 'ASH-ARTSAPP',     'grade' => 12, 'clusters' => ['CREATIVE-ARTS'], 'room_type' => 'workshop', 'sessions_per_week' => 5],

        'SW-SPORTOFF-12'     => ['subject_code' => 'SW-SPORTOFF',     'grade' => 12, 'clusters' => ['SPORTS-WELLNESS'], 'room_type' => 'lecture',  'sessions_per_week' => 5],
        'SW-SAFEFIRST-12'    => ['subject_code' => 'SW-SAFEFIRST',    'grade' => 12, 'clusters' => ['SPORTS-WELLNESS'], 'room_type' => 'lecture',  'sessions_per_week' => 5],
        'SW-SPORTCOACH-12'   => ['subject_code' => 'SW-SPORTCOACH',   'grade' => 12, 'clusters' => ['SPORTS-WELLNESS'], 'room_type' => 'lecture',  'sessions_per_week' => 5],
        'SW-SPORTMGMT-12'    => ['subject_code' => 'SW-SPORTMGMT',    'grade' => 12, 'clusters' => ['SPORTS-WELLNESS'], 'room_type' => 'regular',  'sessions_per_week' => 5],
        'SW-PEFIT-12'        => ['subject_code' => 'SW-PEFIT',        'grade' => 12, 'clusters' => ['SPORTS-WELLNESS'], 'room_type' => 'lecture',  'sessions_per_week' => 5],
        'SW-PESPORT-12'      => ['subject_code' => 'SW-PESPORT',      'grade' => 12, 'clusters' => ['SPORTS-WELLNESS'], 'room_type' => 'lecture',  'sessions_per_week' => 5],

        'AF-AQUA-12'         => ['subject_code' => 'AF-AQUA',         'grade' => 12, 'clusters' => ['AGRI-FISHERY'], 'room_type' => 'workshop',   'sessions_per_week' => 5],
        'AF-FOODINNO-12'     => ['subject_code' => 'AF-FOODINNO',     'grade' => 12, 'clusters' => ['AGRI-FISHERY'], 'room_type' => 'laboratory', 'sessions_per_week' => 5],
        'AF-CROP-12'         => ['subject_code' => 'AF-CROP',         'grade' => 12, 'clusters' => ['AGRI-FISHERY'], 'room_type' => 'workshop',   'sessions_per_week' => 5],
        'AF-AGROENT-12'      => ['subject_code' => 'AF-AGROENT',      'grade' => 12, 'clusters' => ['AGRI-FISHERY'], 'room_type' => 'workshop',   'sessions_per_week' => 5],

        'IN-ELEC-12'         => ['subject_code' => 'IN-ELEC',         'grade' => 12, 'clusters' => ['INDUSTRIAL', 'AUTOMOTIVE', 'CONSTRUCTION', 'MARITIME'], 'room_type' => 'workshop', 'sessions_per_week' => 5],
        'IN-SOLAR-12'        => ['subject_code' => 'IN-SOLAR',        'grade' => 12, 'clusters' => ['INDUSTRIAL'], 'room_type' => 'workshop', 'sessions_per_week' => 5],
        'IN-REFRIG-12'       => ['subject_code' => 'IN-REFRIG',       'grade' => 12, 'clusters' => ['AUTOMOTIVE', 'MARITIME'], 'room_type' => 'workshop', 'sessions_per_week' => 5],

        'AU-AUTO-12'         => ['subject_code' => 'AU-AUTO',         'grade' => 12, 'clusters' => ['AUTOMOTIVE'], 'room_type' => 'workshop', 'sessions_per_week' => 5],
        'AU-SMALLENG-12'     => ['subject_code' => 'AU-SMALLENG',     'grade' => 12, 'clusters' => ['AUTOMOTIVE'], 'room_type' => 'workshop', 'sessions_per_week' => 5],

        'CO-PLUMB-12'        => ['subject_code' => 'CO-PLUMB',        'grade' => 12, 'clusters' => ['CONSTRUCTION'], 'room_type' => 'workshop', 'sessions_per_week' => 5],
        'CO-CARP-12'         => ['subject_code' => 'CO-CARP',         'grade' => 12, 'clusters' => ['CONSTRUCTION'], 'room_type' => 'workshop', 'sessions_per_week' => 5],
        'CO-MASON-12'        => ['subject_code' => 'CO-MASON',        'grade' => 12, 'clusters' => ['CONSTRUCTION'], 'room_type' => 'workshop', 'sessions_per_week' => 5],

        'CA-MUSIC-12'        => ['subject_code' => 'CA-MUSIC',        'grade' => 12, 'clusters' => ['CREATIVE-ARTS'], 'room_type' => 'workshop', 'sessions_per_week' => 5],
        'CA-VISGRAPH-12'     => ['subject_code' => 'CA-VISGRAPH',     'grade' => 12, 'clusters' => ['CREATIVE-ARTS'], 'room_type' => 'workshop', 'sessions_per_week' => 5],
        'CA-ANIM-12'         => ['subject_code' => 'CA-ANIM',         'grade' => 12, 'clusters' => ['CREATIVE-ARTS'], 'room_type' => 'workshop', 'sessions_per_week' => 5],

        'AR-GARMENTS-12'     => ['subject_code' => 'AR-GARMENTS',     'grade' => 12, 'clusters' => ['ARTISANRY'], 'room_type' => 'workshop', 'sessions_per_week' => 5],
        'AR-FASHACC-12'      => ['subject_code' => 'AR-FASHACC',      'grade' => 12, 'clusters' => ['ARTISANRY'], 'room_type' => 'workshop', 'sessions_per_week' => 5],

        'AW-BARBER-12'       => ['subject_code' => 'AW-BARBER',       'grade' => 12, 'clusters' => ['AESTHETIC-WELLNESS'], 'room_type' => 'laboratory', 'sessions_per_week' => 5],
        'AW-CARECHILD-12'    => ['subject_code' => 'AW-CARECHILD',    'grade' => 12, 'clusters' => ['AESTHETIC-WELLNESS'], 'room_type' => 'laboratory', 'sessions_per_week' => 5],
        'AW-BEAUTY-12'       => ['subject_code' => 'AW-BEAUTY',       'grade' => 12, 'clusters' => ['AESTHETIC-WELLNESS'], 'room_type' => 'laboratory', 'sessions_per_week' => 5],
        'AW-CAREADULT-12'    => ['subject_code' => 'AW-CAREADULT',    'grade' => 12, 'clusters' => ['AESTHETIC-WELLNESS'], 'room_type' => 'laboratory', 'sessions_per_week' => 5],

        'MA-MARENG-12'       => ['subject_code' => 'MA-MARENG',       'grade' => 12, 'clusters' => ['MARITIME'], 'room_type' => 'laboratory', 'sessions_per_week' => 5],
        'MA-FIELDEXP-12'     => ['subject_code' => 'MA-FIELDEXP',     'grade' => 12, 'clusters' => ['MARITIME'], 'room_type' => 'laboratory', 'sessions_per_week' => 5],

        'FE-WORKIMM-12'      => ['subject_code' => 'FE-WORKIMM',      'grade' => 12, 'clusters' => ['FIELD-EXP'], 'room_type' => 'regular', 'sessions_per_week' => 5],
        'FE-DESIGN-12'       => ['subject_code' => 'FE-DESIGN',       'grade' => 12, 'clusters' => ['FIELD-EXP'], 'room_type' => 'regular', 'sessions_per_week' => 5],
        'FE-CREATPROD-12'    => ['subject_code' => 'FE-CREATPROD',    'grade' => 12, 'clusters' => ['FIELD-EXP'], 'room_type' => 'regular', 'sessions_per_week' => 5],
    ],
];