export default [
    {
        ignores: [
            'node_modules/**',
            'public/build/**',
            'public/hot',
            'storage/**',
            'vendor/**',
            'bootstrap/cache/**',
        ],
    },
    {
        files: ['resources/js/**/*.js'],
        languageOptions: {
            ecmaVersion: 'latest',
            sourceType: 'module',
            globals: {
                window: 'readonly',
                document: 'readonly',
            },
        },
        rules: {
            'no-undef': 'error',
            'no-unused-vars': ['error', { argsIgnorePattern: '^_' }],
        },
    },
];
