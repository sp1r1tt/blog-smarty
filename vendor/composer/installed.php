<?php return array(
    'root' => array(
        'name' => '__root__',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => null,
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        '__root__' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => null,
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'smarty/smarty' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '383e20e6b43a8c7e367af505f19a230c632a5e36',
            'type' => 'library',
            'install_path' => __DIR__ . '/../smarty/smarty',
            'aliases' => array(
                0 => '5.0.x-dev',
            ),
            'dev_requirement' => false,
        ),
        'symfony/polyfill-mbstring' => array(
            'pretty_version' => 'v1.37.0',
            'version' => '1.37.0.0',
            'reference' => '6a21eb99c6973357967f6ce3708cd55a6bec6315',
            'type' => 'library',
            'install_path' => __DIR__ . '/../symfony/polyfill-mbstring',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
