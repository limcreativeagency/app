<?php

return [
    'accepted'             => ':attribute kabul edilmelidir.',
    'active_url'           => ':attribute geçerli bir URL olmalıdır.',
    'after'                => ':attribute :date tarihinden sonraki bir tarih olmalıdır.',
    'after_or_equal'       => ':attribute :date tarihinden sonra veya aynı tarihte olmalıdır.',
    'alpha'                => ':attribute sadece harflerden oluşmalıdır.',
    'alpha_dash'           => ':attribute sadece harfler, rakamlar ve tirelerden oluşmalıdır.',
    'alpha_num'            => ':attribute sadece harfler ve rakamlar içermelidir.',
    'array'                => ':attribute bir dizi olmalıdır.',
    'before'               => ':attribute :date tarihinden önceki bir tarih olmalıdır.',
    'before_or_equal'      => ':attribute :date tarihinden önce veya aynı tarihte olmalıdır.',
    'between'              => [
        'numeric' => ':attribute :min - :max arasında olmalıdır.',
        'file'    => ':attribute :min - :max kilobayt arasında olmalıdır.',
        'string'  => ':attribute :min - :max karakter arasında olmalıdır.',
        'array'   => ':attribute :min - :max arasında öğe içermelidir.',
    ],
    'boolean'              => ':attribute alanı true veya false olmalıdır.',
    'confirmed'            => ':attribute tekrarı eşleşmiyor.',
    'date'                 => ':attribute geçerli bir tarih olmalıdır.',
    'date_equals'          => ':attribute :date ile aynı tarihte olmalıdır.',
    'date_format'          => ':attribute :format formatına uygun olmalıdır.',
    'different'            => ':attribute ile :other birbirinden farklı olmalıdır.',
    'digits'               => ':attribute :digits rakam olmalıdır.',
    'digits_between'       => ':attribute :min ile :max arasında rakam olmalıdır.',
    'dimensions'           => ':attribute geçersiz resim ölçülerine sahiptir.',
    'distinct'             => ':attribute alanı yinelenen bir değere sahiptir.',
    'email'                => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'ends_with'            => ':attribute şunlardan biriyle bitmelidir: :values',
    'exists'               => 'Seçili :attribute geçersiz.',
    'file'                 => ':attribute bir dosya olmalıdır.',
    'filled'               => ':attribute alanı bir değer içermelidir.',
    'gt'                   => [
        'numeric' => ':attribute, :value değerinden büyük olmalıdır.',
        'file'    => ':attribute, :value kilobayttan büyük olmalıdır.',
        'string'  => ':attribute, :value karakterden uzun olmalıdır.',
        'array'   => ':attribute, :value adetten fazla öğe içermelidir.',
    ],
    'gte'                  => [
        'numeric' => ':attribute, :value değerinden büyük veya eşit olmalıdır.',
        'file'    => ':attribute, :value kilobayt veya daha büyük olmalıdır.',
        'string'  => ':attribute, :value karakter veya daha uzun olmalıdır.',
        'array'   => ':attribute, :value veya daha fazla öğe içermelidir.',
    ],
    'image'                => ':attribute bir resim dosyası olmalıdır.',
    'in'                   => 'Seçili :attribute geçersiz.',
    'in_array'             => ':attribute :other içinde mevcut değil.',
    'integer'              => ':attribute tam sayı olmalıdır.',
    'ip'                   => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4'                 => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6'                 => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json'                 => ':attribute geçerli bir JSON dizesi olmalıdır.',
    'lt'                   => [
        'numeric' => ':attribute, :value değerinden küçük olmalıdır.',
        'file'    => ':attribute, :value kilobayttan küçük olmalıdır.',
        'string'  => ':attribute, :value karakterden kısa olmalıdır.',
        'array'   => ':attribute, :value adetten az öğe içermelidir.',
    ],
    'lte'                  => [
        'numeric' => ':attribute, :value değerinden küçük veya eşit olmalıdır.',
        'file'    => ':attribute, :value kilobayt veya daha küçük olmalıdır.',
        'string'  => ':attribute, :value karakter veya daha kısa olmalıdır.',
        'array'   => ':attribute, :value veya daha az öğe içermelidir.',
    ],
    'max'                  => [
        'numeric' => ':attribute değeri :max değerinden büyük olmamalıdır.',
        'file'    => ':attribute değeri :max kilobayt değerinden büyük olmamalıdır.',
        'string'  => ':attribute değeri en fazla :max karakter olmalıdır.',
        'array'   => ':attribute değeri :max adetten fazla öğe içermemelidir.',
    ],
    'mimes'                => ':attribute dosya biçimi :values olmalıdır.',
    'mimetypes'            => ':attribute dosya biçimi :values olmalıdır.',
    'min'                  => [
        'numeric' => ':attribute değeri en az :min olmalıdır.',
        'file'    => ':attribute değeri en az :min kilobayt olmalıdır.',
        'string'  => ':attribute değeri en az :min karakter olmalıdır.',
        'array'   => ':attribute en az :min öğe içermelidir.',
    ],
    'not_in'               => 'Seçili :attribute geçersiz.',
    'not_regex'            => ':attribute biçimi geçersiz.',
    'numeric'              => ':attribute sayı olmalıdır.',
    'password'             => 'Parola hatalı.',
    'present'              => ':attribute alanı mevcut olmalıdır.',
    'regex'                => ':attribute biçimi geçersiz.',
    'required'             => ':attribute alanı gereklidir.',
    'required_if'          => ':attribute alanı, :other :value değerine sahip olduğunda zorunludur.',
    'required_unless'      => ':attribute alanı, :other :values değerlerine sahip olmadığında zorunludur.',
    'required_with'        => ':attribute alanı :values varken zorunludur.',
    'required_with_all'    => ':attribute alanı :values varken zorunludur.',
    'required_without'     => ':attribute alanı :values yokken zorunludur.',
    'required_without_all' => ':attribute alanı :values değerlerinin hiçbiri yokken zorunludur.',
    'same'                 => ':attribute ile :other eşleşmelidir.',
    'size'                 => [
        'numeric' => ':attribute :size olmalıdır.',
        'file'    => ':attribute :size kilobayt olmalıdır.',
        'string'  => ':attribute :size karakter olmalıdır.',
        'array'   => ':attribute :size öğe içermelidir.',
    ],
    'starts_with'          => ':attribute şunlardan biri ile başlamalıdır: :values',
    'string'               => ':attribute karakterlerden oluşmalıdır.',
    'timezone'             => ':attribute geçerli bir zaman bölgesi olmalıdır.',
    'unique'               => ':attribute daha önceden kayıt edilmiş.',
    'uploaded'             => ':attribute yüklenirken hata oluştu.',
    'url'                  => ':attribute biçimi geçersiz.',
    'uuid'                 => ':attribute geçerli bir UUID olmalıdır.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'emergency_contacts.*.name' => [
            'required' => 'Acil durum kişisinin adı gereklidir.',
        ],
        'emergency_contacts.*.phone' => [
            'required' => 'Acil durum kişisinin telefon numarası gereklidir.',
        ],
        'emergency_contacts.*.relation' => [
            'required' => 'Acil durum kişisinin yakınlık derecesi gereklidir.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'emergency_contacts.*.name' => 'Acil durum kişisi adı',
        'emergency_contacts.*.phone' => 'Acil durum kişisi telefonu',
        'emergency_contacts.*.relation' => 'Acil durum kişisi yakınlık derecesi',
    ],
]; 