module.exports = api => {
    const isTest = api.env('test');

    return {
        presets: [
            [
                '@babel/preset-env',
                {
                    'corejs': '3',
                    'useBuiltIns': 'entry'
                }
            ],
        ],
        plugins: [
            'babel-plugin-syntax-dynamic-import',
            '@babel/plugin-syntax-dynamic-import',
            '@babel/plugin-transform-runtime',
            'babel-plugin-transform-vue-jsx'
        ]
    }
}
