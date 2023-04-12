<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list' => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];
    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public array $request = [
        'user_id' => [
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Vui lòng chọn nhân viên.',
            ],
        ],
        'absent_type'=> [
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Vui lòng chọn loại yêu cầu.',
            ],
        ],
        'request_date' => [
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Vui lòng nhập ngày nghỉ phép.',
            ],
        ],
    ];

}
