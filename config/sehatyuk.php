<?php

return [
    'clinic' => [
        'name'    => 'Klinik Pratama & SehatYuk',
        'about'   => 'Melayani pasien umum, BPJS, dan asuransi rekanan. Buka setiap Senin - Sabtu, pukul 08:00 - 20:00 WIB.',
        'hours'   => 'Senin - Sabtu: 08:00 - 20:00 WIB',
        'address' => 'Jl. Teuku Muhammad Daud, Beureuh, Bandar Baru, Kec. Kuta Alam, Kota Banda Aceh, Aceh 23126',
        'hotline' => '(+62) 851-1769-0565',
    ],
    'gemini' => [
        'key'   => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-flash-latest'),
    ],
];
